<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item trade controller
 *
 * Trade items
 *
 * @package    ModularGaming/Items
 * @category   Controller
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class Controller_Trade extends Abstract_Controller_Frontend {
	protected $protected = TRUE;
	
	public function action_index()
	{
		$page = $this->request->param('page');
		
		$config = Kohana::$config->load('items.trade.lots');
		$max_lots = $config['max_results'];
		
		$lots = ORM::factory('User_Trade');
		
		$paginate = Paginate::factory($lots, array ('total_items' => $max_lots), $this->request)->execute();
		
		$this->view = new View_Item_Trade_Index;
		$this->view->pagination = $paginate->render();
		$this->view->lots = $paginate->result();
	}
	
	/**
	 * Create a new lot
	 */
	public function action_create() {		
		$items = Item::location('inventory', true)->find_all();
		
		$this->view = new View_Item_Trade_Create;
		$this->view->items = $items;
		$this->view->max_items = Kohana::$config->load('items.trade.lots.max_items');
		$this->view->max_type = (Kohana::$config->load('items.trade.lots.count_amount')) ? 'items' : 'stacks';
		$this->view->process_url = Route::url('item.trade.create.process');
	}
	
	/**
	 * Process a lot
	 */
	public function action_process_create() {
		$config = Kohana::$config->load('items.trade.lots');
		
		if($this->request->method() != HTTP_Request::POST)
			$this->redirect(Route::get('item.trade.create')->uri());
		
		if($config['max_items'] < count($this->request->post('items')))
		{
			Hint::error(__('You can\'t create a trading lot with more than :amount items', array(':amount' => $config['max_items'])));
		}
		else
		{
			$items = $this->request->post('items');
			$a_count = 0;
			$stored_items = array();
				
			Database::instance()->begin();
				
			//let's start by validating and moving the items away from the inventory
			foreach($items as $id => $amount) {
				
				if(!empty($amount) && $amount > 0)
				{
					$item = ORM::factory('User_Item', $id);
			
					if(!$item->loaded())
					{
						Hint::error('You want to trade an item that does not seem to exist.');
						break;
					}
					if($item->location != 'inventory')
					{
						Hint::error('You can only trade items from your inventory.');
						break;
					}
					if($item->user_id != $this->user->id)
					{
						Hint::error('You can\'t trade an item that isn\'t yours.');
						break;
					}
					if($item->amount < $amount)
					{
						Hint::error(__('You only have :items, you\'re trying to trade :amount', array(':items' => $item->name(), ':amount' => $amount)));
						break;
					}
			
					$a_count += $amount;
			
					if($config['count_amount'] == true && $a_count > $config['max_items'])
					{
						Hint::error(__('You can\'t trade more than a total of :amount items.', array(':amount' => $config['max_items'])));
						break;
					}
			
					$stored_items[] = $item->move('trade.lot', $amount, false);
				}
			}
				
			$dump = Hint::dump();
				
			if($dump['status'] == 'error')
			{
				Database::instance()->rollback();
				$this->redirect(Route::get('item.trade.create')->uri());
			}
			else {
				try {
					$lot = ORM::factory('User_Trade');
					$lot->user_id = $this->user->id;
					$lot->description = $this->request->post('description');
					$lot->save();
						
					//point the items to the created lot
					foreach($stored_items as $item) {
						$item->parameter_id = $lot->id;
						$item->save();
					}
				}
				catch (Kohana_ORM_Validation_Exception $e) {
					foreach($e->errors('models') as $error) {
						Hint::error($error);
					}
						
					Database::instance()->rollback();
						
					return $this->redirect(Route::get('item.trade.create')->uri());
				}
		
				Database::instance()->commit();
				Hint::success('You\'ve successfully created your trading lot!');
				return $this->redirect(Route::get('item.trade.lot')->uri(array('id' => $lot->id)));
			}
		}
	}
	
	/**
	 * Retract a bid
	 */
	public function action_delete() {
		$id = $this->request->param('id');
	
		$lot = ORM::factory('User_Trade', $id);
	
		if(!$lot->loaded()) {
			Hint::error('You tried deleting a lot that does not exists.');
			$this->redirect(Route::get('item.trade.index'));
		}
		else if($lot->user_id != $this->user_id) {
			Hint::error('You tried deleting a lot that isn\'t yours.');
			$this->redirect(Route::get('item.trade.index'));
		}
		
		$bids = $lot->bids->find_all();
	
		//remove all bids made to this lot
		if(count($bids) > 0) 
		{
			foreach($bids as $bid)
			{
				$items = $bid->items();
					
				foreach($items as $item) {
					$item->move('inventory', $item->amount);
				}
					
				if($bid->points > 0)
				{
					$this->user->points += $bid->points;
					$this->user->save();
				}
					
				$bid->delete();
			}
		}
	
		//move back the lot's items to the inventory
		foreach($lot->items() as $item) {
			$item->move('inventory', '*');
		}
		
		$lot_id = $lot->id;
		
		$lot->delete();
		
		Hint::success('You\'ve successfully cancelled lot #:lot', array(':lot' => $lot_id));
		$this->redirect(Route::get('item.trade.index'));
	}
	
	/**
	 * View a lot
	 */
	public function action_lot() {
		$id = $this->request->param('id');
		
		$lot = ORM::factory('User_Trade', $id);
		$this->view = new View_Item_Trade_Lot;
		
		if(!$lot->loaded())
		{
			Hint::error('The lot you want to load does not seem to exist.');
		}
		else {
			$this->view->lot = $lot;
			$this->view->currency_image = Kohana::$config->load('items.trade.currency_image');
			
			//let's see if the user has put down a bid on this lot
			if($this->user->id != $lot->user_id)
			{
				$bid = ORM::factory('User_Trade_bid')
					->where('user_id', '=', $this->user->id)
					->where('lot_id', '=', $lot->id)
					->find();
				
				if($bid->loaded())
				{
					$this->view->bid = $bid;
				}
			}
			else 
			{
				//the owner's view
				$this->view->owner_actions = true;
			}
		}
	}
	
	/**
	 * Bid on a lot
	 */
	public function action_bid() {
		$id = $this->request->param('id');
		
		$lot = ORM::factory('User_Trade', $id);
		$this->view = new View_Item_Trade_Bid;
		
		if(!$lot->loaded()) 
		{
			//@todo change to HTTP error
			Hint::error('No trade lot found to bid on.');
		}
		else {
			$this->view->lot = $lot;
			$this->view->items = Item::location('inventory', true)->find_all();
			$this->view->max_items = Kohana::$config->load('items.trade.bids.max_items') - 1;
			$this->view->max_type = (Kohana::$config->load('items.trade.bids.count_amount')) ? 'items' : 'stacks';
			$this->view->process_url = Route::url('item.trade.bid.process', array('id' => $lot->id));
		}
	}
	
	/**
	 * Process a bid
	 */
	public function action_process_bid() {
		$id = $this->request->param('id');
		
		$config = Kohana::$config->load('items.trade.bids');
		
		if($this->request->method() != HTTP_Request::POST)
			$this->redirect(Route::get('item.trade.bid')->uri(array('id' => $id)));
		
		$points = $this->request->post('points');
		
		if($config['max_items'] < count($this->request->post('items')))
		{
			Hint::error(__('You can\'t bid on a lot with more than :amount items', array(':amount' => $config['max_items'])));
		}
		else if(!empty($points) && (!Valid::digit($points) || points < 0))
		{
			Hint::error(__('If you want to add points to your bid specify a number (:points)', array(':points' => $points)));
		}
		else if(Valid::digit($points) && points > $this->user->points)
		{
			Hint::error(__('If you don\'t have enough points to add to this bid (:points)', array(':points' => $points)));
		}
		else
		{
			$items = $this->request->post('items');
			$a_count = 0;
			$stored_items = array();
		
			Database::instance()->begin();
		
			//let's start by validating and moving the items away from the inventory
			foreach($items as $id => $amount) {
		
				if(!empty($amount) && $amount > 0)
				{
					$item = ORM::factory('User_Item', $id);
						
					if(!$item->loaded())
					{
						Hint::error('You want to bid an item that does not seem to exist.');
						break;
					}
					if($item->location != 'inventory')
					{
						Hint::error('You can only bid items from your inventory.');
						break;
					}
					if($item->user_id != $this->user->id)
					{
						Hint::error('You can\'t bid an item that isn\'t yours.');
						break;
					}
					if($item->amount < $amount)
					{
						Hint::error(__('You only have :items, you\'re trying to bid :amount', array(':items' => $item->name(), ':amount' => $amount)));
						break;
					}
						
					$a_count += $amount;
						
					if($config['count_amount'] == true && $a_count >= $config['max_items'])
					{
						Hint::error(__('You can\'t bid more than a total of :amount items.', array(':amount' => $config['max_items'])));
						break;
					}
						
					$stored_items[] = $item->move('trade.bid', $amount, false);
				}
			}
		
			$dump = Hint::dump();
		
			if($dump['status'] == 'error')
			{
				Database::instance()->rollback();
				$this->redirect(Route::get('item.trade.bid')->uri(array('id' => $id)));
			}
			else {
				try {					
					$bid = ORM::factory('User_Trade_bid');
					$bid->lot_id = $id;
					$bid->user_id = $this->user->id;
					
					//deduct points if specified
					if(Valid::digit($points))
					{
						$user->points -= $points;
						$user->save();
						$bid->points = $points;
					}
					
					$bid->save();
		
					//point the items to the created lot
					foreach($stored_items as $item) {
						$item->parameter_id = $bid->id;
						$item->save();
					}
				}
				catch (Kohana_ORM_Validation_Exception $e) {
					foreach($e->errors('models') as $error) {
						Hint::error($error);
					}
		
					Database::instance()->rollback();
		
					return $this->redirect(Route::get('item.trade.bid')->uri(array('id' => $id)));
				}
		
				Database::instance()->commit();
				Hint::success('You\'ve successfully made a bid!');
				return $this->redirect(Route::get('item.trade.bids')->uri());
			}
		}
	}
	
	/**
	 * View all bids on a lot
	 */
	public function action_bids() {
		$bids = ORM::factory('User_Trade_Bid')
			->where('user_id', '=', $this->user->id)
			->find_all();
			
		$this->view = new View_Item_Trade_Bids;
		$this->view->currency_image = Kohana::$config->load('items.trade.currency_image');
		$this->view->bids = $bids;
		$this->view->count = count($bids);
	}
	
	/**
	 * Accept a bid
	 */
	public function action_accept() {
		$id = $this->request->param('id');
		
		$bid = ORM::factory('User_Trade_Bid', $id);
		
		if(!$bid->loaded())
		{
			//@todo change to HTTP exception
			return Hint::error('No bid found to reject');
		}
		else if($bid->trade->user_id != $this->user->id)
		{
			Hint::error('You can\'t accept a bid on a trade lot that isn\'t yours.');
			$this->redirect(Route::get('item.trade.lot', array('id' => $id)));
		}
		else
		{
			$lot = $bid->trade;
			
			//send offered items to the trade's owner
			$offered_items = $bid->items();
				
			foreach($offered_items as $item) {
				$item->transfer($lot->user, $item->amount);
			}
				
			//if points were added give them to the trade owner
			if($bid->points > 0)
			{
				$user = $lot->user;
				$user->points += $bid->points;
				$user->save();
			}
			
			//send the items up for trade to the winning bidder
			$lot_items = $lot->items();
			
			foreach($lot_items as $item) {
				$item->transfer($bid->user, $item->amount);
			}
				
			Hint::success('You\'ve accepted bid #:id made by :username', array(':id' => $bid->id, ':username' => $bid->user->username));
				
			$bid->delete();
		}
		
		$this->redirect(Route::get('item.trade.index')->uri());
	}
	
	/**
	 * Reject a bid
	 */
	public function action_reject() {
		$id = $this->request->param('id');
		
		$bid = ORM::factory('User_Trade_Bid', $id);
		
		if(!$bid->loaded())
		{
			//@todo change to HTTP exception
			return Hint::error('No bid found to reject');
		}
		
		$return = Route::get('item.trade.lot')->uri(array('id' => $bid->trade_id));
		
		if($bid->trade->user_id != $this->user->id)
		{
			Hint::error('You can\'t reject a bid on a trade lot that isn\'t yours.');
		}
		else 
		{
			$items = $bid->items();
			
			foreach($items as $item) {
				$item->move('inventory', $item->amount);
			}
			
			if($bid->points > 0)
			{
				$user = $bid->user;
				$user->points += $bid->points;
				$user->save();
			}
			
			Hint::success('You\'ve rejected bid #:id made by :username', array(':id' => $bid->id, ':username' => $user->username));
			
			$bid->delete();
		}
		
		$this->redirect($return);
	}
	
	/**
	 * Retract a bid
	 */
	public function action_retract() {
		$id = $this->request->param('id');
		
		$bid = ORM::factory('User_Trade_Bid', $id);
		
		if(!$bid->loaded())
		{
			//@todo change to HTTP exception
			return Hint::error('No bid found to reject');
		}
		
		$return = Route::get('item.trade.lot')->uri(array('id' => $bid->trade_id));
		
		if($bid->user_id != $this->user->id)
		{
			Hint::error('You can\'t retract a bid that isn\'t yours.');
		}
		else 
		{
			$items = $bid->items();
			
			foreach($items as $item) {
				$item->move('inventory', $item->amount);
			}
			
			if($bid->points > 0)
			{
				$this->user->points += $bid->points;
				$this->user->save();
			}
			
			Hint::success('You\'ve retracted your bid');
			
			$bid->delete();
		}
		
		$this->redirect($return);
	}
	
	/**
	 * Search lots
	 * @todo implement
	 */
	public function action_search() {
		
	}
	
	public function after() {
		$map = array('index', 'lots', 'bids', 'create', 'search');
		
		$links = array(
				array('name' => 'List', 'url' => Route::url('item.trade.index'), 'active'=>false),
				array('name' => 'Your lots', 'url' => Route::url('item.trade.lots'), 'active'=>false),
				array('name' => 'Your bids', 'url' => Route::url('item.trade.bids'), 'active'=>false),
				array('name' => 'Create', 'url' => Route::url('item.trade.create'), 'active'=>false),
				array('name' => 'Search', 'url' => Route::url('item.trade.search'), 'active'=>false),
		);
		
		if(in_array($this->request->action(), $map))
			$links[array_search($this->request->action(), $map)]['active'] = true;
		
		$this->view->trade_nav = $links;
		parent::after();
	}
}
