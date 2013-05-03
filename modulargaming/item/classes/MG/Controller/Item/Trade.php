<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item trade controller
 *
 * Trade items
 *
 * @package    MG/Items
 * @category   Controller
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Controller_Item_Trade extends Abstract_Controller_Frontend {
	protected $protected = TRUE;

	public function action_index()
	{
		$page = $this->request->param('page');

		$config = Kohana::$config->load('items.trade.lots');
		$max_lots = $config['max_results'];

		$lots = ORM::factory('User_Trade');

		$paginate = Paginate::factory($lots, array('total_items' => $max_lots), $this->request)->execute();

		$this->view = new View_Item_Trade_Index;
		$this->view->pagination = $paginate->render();
		$this->view->lots = $paginate->result();
	}

	public function action_lots()
	{
		$config = Kohana::$config->load('items.trade.lots');
		$max_lots = $config['max_results'];

		$lots = ORM::factory('User_Trade')
			->where('user_id', '=', $this->user->id);

		$paginate = Paginate::factory($lots, array('total_items' => $max_lots), $this->request)->execute();

		$this->view = new View_Item_Trade_Lots;
		$this->view->pagination = $paginate->render();
		$this->view->lots = $paginate->result();
	}

	/**
	 * Create a new lot
	 */
	public function action_create()
	{
		$items = Item::location('inventory', TRUE)->find_all();

		$this->view = new View_Item_Trade_Create;

		if (count($items) == 0)
		{
			Hint::error('You don\'t have any items in your invetnory to put up for trade.');
			$this->view->unable = TRUE;
		}
		else
		{
			$this->view->items = $items;
			$this->view->max_items = Kohana::$config->load('items.trade.lots.max_items');
			$this->view->max_type = (Kohana::$config->load('items.trade.lots.count_amount')) ? 'items' : 'stacks';
			$this->view->process_url = Route::url('item.trade.create.process');
		}
	}

	/**
	 * Process a lot
	 */
	public function action_process_create()
	{
		$config = Kohana::$config->load('items.trade.lots');

		if ($this->request->method() != HTTP_Request::POST)
		{
			$this->redirect(Route::get('item.trade.create')->uri());
		}

		if ($config['max_items'] < count($this->request->post('items')))
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
			foreach ($items as $id => $amount)
			{

				if (!empty($amount) && $amount > 0)
				{
					$item = ORM::factory('User_Item', $id);

					if (!$item->loaded())
					{
						Hint::error('You want to trade an item that does not seem to exist.');
						break;
					}
					if ($item->location != 'inventory')
					{
						Hint::error('You can only trade items from your inventory.');
						break;
					}
					if ($item->user_id != $this->user->id)
					{
						Hint::error('You can\'t trade an item that isn\'t yours.');
						break;
					}
					if ($item->amount < $amount)
					{
						Hint::error(__('You only have :items, you\'re trying to trade :amount', array(':items' => $item->name(), ':amount' => $amount)));
						break;
					}

					$a_count += $amount;

					if ($config['count_amount'] == TRUE && $a_count > $config['max_items'])
					{
						Hint::error(__('You can\'t trade more than a total of :amount items.', array(':amount' => $config['max_items'])));
						break;
					}

					$stored_items[] = $item->move('trade.lot', $amount, FALSE);
				}
			}

			$dump = Hint::dump();

			if ($dump['status'] == 'error')
			{
				Database::instance()->rollback();
				$this->redirect(Route::get('item.trade.create')->uri());
			}
			else
			{
				try
				{
					$lot = ORM::factory('User_Trade');
					$lot->user_id = $this->user->id;
					$lot->description = $this->request->post('description');
					$lot->save();

					//point the items to the created lot
					foreach ($stored_items as $item)
					{
						$item->parameter_id = $lot->id;
						$item->save();
					}
				} catch (Kohana_ORM_Validation_Exception $e)
				{
					foreach ($e->errors('models') as $error)
					{
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
	public function action_delete()
	{
		$id = $this->request->param('id');

		$lot = ORM::factory('User_Trade', $id);

		if (!$lot->loaded())
		{
			Hint::error('You tried deleting a lot that does not exists.');
			$this->redirect(Route::get('item.trade.index'));
		}
		else if ($lot->user_id != $this->user_id)
		{
			Hint::error('You tried deleting a lot that isn\'t yours.');
			$this->redirect(Route::get('item.trade.index'));
		}

		$bids = $lot->bids->find_all();

		//remove all bids made to this lot
		if (count($bids) > 0)
		{
			$log = Journal::log('item.trade.' . $id . '.delete', 'item', 'Trade #id deleted', array(':id' => $id));

			foreach ($bids as $bid)
			{
				$items = $bid->items();

				foreach ($items as $item)
				{
					$item->move('inventory', $item->amount);
				}

				if ($bid->points > 0)
				{
					$bid->user->points += $bid->points;
					$bid->user->save();
				}

				$log->notify($bid->user, 'item.trades.delete', array(':lot' => $id));

				$bid->delete();
			}
		}

		//move back the lot's items to the inventory
		foreach ($lot->items() as $item)
		{
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
	public function action_lot()
	{
		$id = $this->request->param('id');

		$lot = ORM::factory('User_Trade', $id);
		$this->view = new View_Item_Trade_Lot;

		if (!$lot->loaded())
		{
			Hint::error('The lot you want to load does not seem to exist.');
		}
		else
		{
			$this->view->lot = $lot;
			$this->view->currency_image = Kohana::$config->load('items.trade.currency_image');

			//let's see if the user has put down a bid on this lot
			if ($this->user->id != $lot->user_id)
			{
				$bid = ORM::factory('User_Trade_bid')
					->where('user_id', '=', $this->user->id)
					->where('lot_id', '=', $lot->id)
					->find();

				if ($bid->loaded())
				{
					$this->view->bid = $bid;
				}
			}
			else
			{
				//the owner's view
				$this->view->owner_actions = TRUE;
			}
		}
	}

	/**
	 * Bid on a lot
	 */
	public function action_bid()
	{
		$id = $this->request->param('id');

		$lot = ORM::factory('User_Trade', $id);
		$this->view = new View_Item_Trade_Bid;

		if (!$lot->loaded())
		{
			Hint::error('No trade lot found to bid on.');
			$this->view->unable = TRUE;
		}
		else if (count($items) == 0)
		{
			Hint::error('You don\'t have any items in your invetnory to put up for trade.');
			$this->view->unable = TRUE;
		}
		else
		{
			$this->view->lot = $lot;
			$this->view->items = Item::location('inventory', TRUE)->find_all();
			$this->view->max_items = Kohana::$config->load('items.trade.bids.max_items') - 1;
			$this->view->max_type = (Kohana::$config->load('items.trade.bids.count_amount')) ? 'items' : 'stacks';
			$this->view->process_url = Route::url('item.trade.bid.process', array('id' => $lot->id));
		}
	}

	/**
	 * Process a bid
	 */
	public function action_process_bid()
	{
		$id = $this->request->param('id');
		$config = Kohana::$config->load('items.trade.bids');

		if ($this->request->method() != HTTP_Request::POST)
		{
			$this->redirect(Route::get('item.trade.bid')->uri(array('id' => $id)));
		}

		$points = $this->request->post('points');

		if ($config['max_items'] < count($this->request->post('items')))
		{
			Hint::error(__('You can\'t bid on a lot with more than :amount items', array(':amount' => $config['max_items'])));
		}
		else if (!empty($points) && (!Valid::digit($points) || points < 0))
		{
			Hint::error(__('If you want to add points to your bid specify a number (:points)', array(':points' => $points)));
		}
		else if (Valid::digit($points) && points > $this->user->points)
		{
			Hint::error(__('You don\'t have enough points to add to this bid (:points)', array(':points' => $points)));
		}
		else
		{
			$items = $this->request->post('items');
			$a_count = 0;
			$stored_items = array();

			Database::instance()->begin();

			//let's start by validating and moving the items away from the inventory
			foreach ($items as $id => $amount)
			{

				if (!empty($amount) && $amount > 0)
				{
					$item = ORM::factory('User_Item', $id);

					if (!$item->loaded())
					{
						Hint::error('You want to bid an item that does not seem to exist.');
						break;
					}
					if ($item->location != 'inventory')
					{
						Hint::error('You can only bid items from your inventory.');
						break;
					}
					if ($item->user_id != $this->user->id)
					{
						Hint::error('You can\'t bid an item that isn\'t yours.');
						break;
					}
					if ($item->amount < $amount)
					{
						Hint::error(__('You only have :items, you\'re trying to bid :amount', array(':items' => $item->name(), ':amount' => $amount)));
						break;
					}

					$a_count += $amount;

					if ($config['count_amount'] == TRUE && $a_count > $config['max_items'])
					{
						Hint::error(__('You can\'t bid more than a total of :amount items.', array(':amount' => $config['max_items'])));
						break;
					}

					$stored_items[] = $item->move('trade.bid', $amount, FALSE);
				}
			}

			//check stack total if needed
			if ($config['count_amount'] == FALSE && count($stored_items) > $config['max_items'])
			{
				Hint::error(__('You can\'t bid more than a total of :amount different items.', array(':amount' => $config['max_items'])));
			}
			//check stack amount total if needed
			else if ($config['count_amount'] == FALSE && $a_count > $config['max_in_stack'])
			{
				Hint::error(__('You can\'t bid more than a total of :amount items.', array(':amount' => $config['max_in_stack'])));
			}

			$dump = Hint::dump();

			if ($dump['status'] == 'error')
			{
				Database::instance()->rollback();
				$this->redirect(Route::get('item.trade.bid')->uri(array('id' => $id)));
			}
			else
			{
				try
				{
					$bid = ORM::factory('User_Trade_bid');
					$bid->lot_id = $id;
					$bid->user_id = $this->user->id;

					//deduct points if specified
					if (Valid::digit($points))
					{
						$this->user->points -= $points;
						$this->user->save();
						$bid->points = $points;
					}

					$bid->save();

					$item_names = array();
					//point the items to the created lot
					foreach ($stored_items as $item)
					{
						$item->parameter_id = $bid->id;
						$item_names[] = $item->name();
						$item->save();
					}
				} catch (Kohana_ORM_Validation_Exception $e)
				{
					foreach ($e->errors('models') as $error)
					{
						Hint::error($error);
					}

					Database::instance()->rollback();

					return $this->redirect(Route::get('item.trade.bid')->uri(array('id' => $id)));
				}

				$log = Journal::log('item.trade.bid.' . $bid->lot_id, 'items', 'Made a bid with :amount items and :points points', array(
					':amount' => $a_count, ':points' => (int)$points, 'items' => $item_names));

				$log->notify($bid->lot_user, 'items.trades.bid', array(
					':user' => $this->user->username,
					':lot' => '<strong>#<a href="' . Route::url('item.trade.lot', array('id' => $bid->lot_id)) . '">' . $bid->lot_id . '</a></strong>'
				));

				Database::instance()->commit();
				Hint::success('You\'ve successfully made a bid!');

				return $this->redirect(Route::get('item.trade.bids')->uri());
			}
		}
	}

	/**
	 * View all bids on a lot
	 */
	public function action_bids()
	{
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
	public function action_accept()
	{
		$id = $this->request->param('id');

		$bid = ORM::factory('User_Trade_Bid', $id);

		if (!$bid->loaded())
		{
			Hint::error('No bid found to reject');
		}
		else if ($bid->trade->user_id != $this->user->id)
		{
			Hint::error('You can\'t accept a bid on a trade lot that isn\'t yours.');
			$this->redirect(Route::get('item.trade.lot', array('id' => $id)));
		}
		else
		{
			$lot = $bid->trade;

			//send offered items to the trade's owner
			$offered_items = $bid->items();

			foreach ($offered_items as $item)
			{
				$item->transfer($lot->user, $item->amount);
			}

			//if points were added give them to the trade owner
			if ($bid->points > 0)
			{
				$user = $lot->user;
				$user->points += $bid->points;
				$user->save();
			}

			//send the items up for trade to the winning bidder
			$lot_items = $lot->items();

			foreach ($lot_items as $item)
			{
				$item->transfer($bid->user, $item->amount);
			}

			$log = Journal::log('item.trade.' . $id . '.accept', 'item', 'Trade #id completed', array(':id' => $id));
			$log->notify($user, 'items.trades.accept', array(':username' => $this->user->username));

			Hint::success('You\'ve accepted bid #:id made by :username', array(':id' => $bid->id, ':username' => $bid->user->username));

			$bid->delete();
		}

		$this->redirect(Route::get('item.trade.index')->uri());
	}

	/**
	 * Reject a bid
	 */
	public function action_reject()
	{
		$id = $this->request->param('id');

		$bid = ORM::factory('User_Trade_Bid', $id);

		if (!$bid->loaded())
		{
			return Hint::error('No bid found to reject');
		}

		$return = Route::get('item.trade.lot')->uri(array('id' => $bid->trade_id));

		if ($bid->trade->user_id != $this->user->id)
		{
			Hint::error('You can\'t reject a bid on a trade lot that isn\'t yours.');
		}
		else
		{
			$items = $bid->items();

			foreach ($items as $item)
			{
				$item->move('inventory', $item->amount);
			}

			if ($bid->points > 0)
			{
				$user = $bid->user;
				$user->points += $bid->points;
				$user->save();
			}

			Hint::success('You\'ve rejected bid #:id made by :username', array(':id' => $bid->id, ':username' => $user->username));

			$log = Journal::log('item.trade.' . $id . '.reject', 'item', 'Bid from :user declined', array(':user' => $user->username));
			$log->notify($user, 'items.trades.reject', array(':lot' => $id));
			$bid->delete();
		}

		$this->redirect($return);
	}

	/**
	 * Retract a bid
	 */
	public function action_retract()
	{
		$id = $this->request->param('id');

		$bid = ORM::factory('User_Trade_Bid', $id);

		if (!$bid->loaded())
		{
			//@todo change to HTTP exception
			return Hint::error('No bid found to reject');
		}

		$return = Route::get('item.trade.lot')->uri(array('id' => $bid->trade_id));

		if ($bid->user_id != $this->user->id)
		{
			Hint::error('You can\'t retract a bid that isn\'t yours.');
		}
		else
		{
			$items = $bid->items();

			foreach ($items as $item)
			{
				$item->move('inventory', $item->amount);
			}

			if ($bid->points > 0)
			{
				$this->user->points += $bid->points;
				$this->user->save();
			}

			Hint::success('You\'ve retracted your bid');

			$log = Journal::log('item.trade.' . $id . '.retract', 'item', 'Retracted bid for :id', array(':id' => $id));
			$log->notify($log, $bid->lot->user, 'items.trades.retract', array(':lot' => $id, ':username' => $this->user->username));

			$bid->delete();
		}

		$this->redirect($return);
	}

	/**
	 * Search lots
	 * @todo implement search entries pp limit
	 */
	public function action_search()
	{
		$term = $this->request->query("t");
		$limit = $this->request->query("l");

		$items = ORM::factory('User_Item')
			->where('location', '=', 'trade.lot')
			->where('item.name', 'LIKE', '%' . $term . '%');

		$paginate = Paginate::factory($items, array('total_items' => $limit), $this->request, array('t', 'l'))->execute();

		$this->view = new View_Item_Trade_Search;
		$this->view->term = $term;
		$this->view->count_results = $paginate->count_total();
		$this->view->pagination = $paginate->render();
		$this->view->items = $paginate->result();
	}

	public function after()
	{
		$map = array('index', 'lots', 'bids', 'create');

		$links = array(
			array('name' => 'List', 'url' => Route::url('item.trade.index'), 'active' => FALSE),
			array('name' => 'Your lots', 'url' => Route::url('item.trade.lots'), 'active' => FALSE),
			array('name' => 'Your bids', 'url' => Route::url('item.trade.bids'), 'active' => FALSE),
			array('name' => 'Create', 'url' => Route::url('item.trade.create'), 'active' => FALSE),
		);

		if (in_array($this->request->action(), $map))
		{
			$links[array_search($this->request->action(), $map)]['active'] = TRUE;
		}

		$this->view->trade_nav = $links;
		$this->view->search_url = Route::url('item.trade.search');
		parent::after();
	}
}
