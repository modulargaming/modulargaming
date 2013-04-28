<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Modal_Item extends Abstract_Controller_Modal {

	public function action_shop()
	{
		$this->view = new View_Admin_User_Modal_Shop;
		$this->view->data_sources = array(
			array(
				'id' => 'stock',
				'url' => Route::url('admin.item.modal.shop.stock', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'update',
				'url' => Route::url('admin.item.modal.shop.update', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'load',
				'url' => Route::url('admin.item.modal.shop.load', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'reset',
				'url' => Route::url('admin.item.modal.shop.reset', array('user_id' => $this->_player->id))
			)
		);
	}

	public function action_shop_load() {
		$shop = ORM::factory('User_Shop')
			->where('user_id', '=', $this->_player->id)
			->find();

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array(
			'status' => 'success',
			'fields' => array(
				'name' => $shop->title,
				'description' => $shop->description,
				'size' => $shop->size
			)
		)));
	}

	public function action_shop_update() {
		$shop = ORM::factory('User_Shop')
			->where('user_id', '=', $this->_player->id)
			->find();

		$size = $shop->size;

		try
		{
			$shop->values($this->request->post(), array('title', 'size', 'description'));

			$shop->save();

			if($shop->size != $size)
			{
				Journal::log('admin.user.shop.size', 'admin', ':username changed :other_user\'s shop size to :size' , array(':other_user' => $this->_player->username, ':size' => $shop->size));
			}

			$this->response->body(json_encode(array(
				'status' => 'success'
			)));
		}
		catch (ORM_Validation_Exception $e)
		{
			$this->response->body(json_encode(array(
				'status' => 'error',
				'errors' => $e->errors('models')
			)));
		}

		$this->response->headers('Content-Type', 'application/json');

	}

	public function action_shop_reset() {
		$item = ORM::factory('User_Item', $this->request->post('id'));

		$this->response->headers('Content-Type', 'application/json');

		if($item->loaded())
		{
			$item->price = 0;
			$item->save();

			$this->response->body(json_encode(array(
				'status' => 'success'
			)));

			Journal::log('admin.user.shop.item.reset', 'admin', ':username reset the price on :item_name from :other_user through the admin', array(':other_user' => $this->_player->username, ':item_name' => $item->item->name));

		}
		else
		{
			$this->response->body(json_encode(array(
				'status' => 'error',
				'error' => 'No such item found'
			)));
		}
	}

	public function action_shop_stock()
	{
		$this->view = null;

		if (DataTables::is_request())
		{
			$orm = ORM::factory('User_Item')
				->where('user_id', '=', $this->_player->id)
				->where('location', '=', 'shop');

			$paginate = Paginate::factory($orm)
				->columns(array('item.name', 'item.image', 'amount', 'price', 'id'))
				->search_columns(array('item.name', 'amount', 'price'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $record)
			{
				$datatables->add_row(
					array(
						$record->img(),
						$record->item->name,
						$record->amount,
						$record->price,
						$record->id
					)
				);
			}

			$datatables->render($this->response);
		}
		else
		{
			throw new HTTP_Exception_500();
		}
	}

	public function action_items()
	{
		$this->view = new View_Admin_User_Modal_Items;

		$this->view->locations = DB::select('location')
			->distinct(true)
			->from('user_items')
			->where('user_id', '=', $this->_player->id)
			->execute();


		$this->view->data_sources = array(
			array(
				'id' => 'give',
				'url' => Route::url('admin.item.modal.items.give', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'modify',
				'url' => Route::url('admin.item.modal.items.modify', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'save',
				'url' => Route::url('admin.item.modal.items.save', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'search',
				'url' => Route::url('admin.item.modal.items.search', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'load',
				'url' => Route::url('admin.item.modal.items.load', array('user_id' => $this->_player->id))
			)
		);
	}

	public function action_items_load()
	{
		$this->view = null;

		if (DataTables::is_request())
		{
			$orm = ORM::factory('User_Item')
				->where('user_id', '=', $this->_player->id);

			$paginate = Paginate::factory($orm)
				->columns(array('item.name', 'item.image', 'amount', 'location', 'id'))
				->search_columns(array('item.name'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $record)
			{
				$datatables->add_row(
					array(
						$record->img(),
						$record->item->name,
						$record->location,
						$record->amount,
						$record->id
					)
				);
			}

			$datatables->render($this->response);
		}
		else
		{
			throw new HTTP_Exception_500();
		}
	}

	public function action_items_search() {
		$title = $this->request->post('tile');

		$avatars = ORM::factory('Item')
			->where('item.name', 'LIKE', '%'.$title.'%')
			->find_all();

		$list = array();

		if(count($avatars) > 0)
		{
			foreach($avatars as $av)
			{
				$list[] = $av->name;
			}
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}

	public function action_items_give() {
		$list = $this->request->post('items');

		foreach($list as $item)
		{
			$it = ORM::factory('Item')
				->where('item.name', '=', $item)
				->find();

			Item::factory($it)
				->to_user($this->_player, 'admin');
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array('status' => 'success')));
	}

	public function action_items_modify() {
		$id = $this->request->query('id');

		$item = ORM::factory('User_Item', $id);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array(
			'id' => $item->id,
			'location' => $item->location,
			'amount' => $item->amount,
			'name' => $item->item->name
		)));
	}

	public function action_trades()
	{
		$this->view = new View_Admin_User_Modal_Trades;

		$trades = ORM::factory('User_Trade')
			->where('user_id', '=', $this->_player->id)
			->count_all();

		$bids = ORM::factory('User_Trade_Bid')
			->where('user_id', '=', $this->_player->id)
			->count_all();

		$this->view->has_lot = ($trades > 0);
		$this->view->has_bid = ($bids > 0);

		$this->view->data_sources = array(
			array(
				'id' => 'lots',
				'url' => Route::url('admin.item.modal.trades.lots', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'bids',
				'url' => Route::url('admin.item.modal.trades.bids', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'cancel',
				'url' => Route::url('admin.item.modal.trades.cancel', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'load',
				'url' => Route::url('admin.item.modal.trades.lots.load', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'edit',
				'url' => Route::url('admin.item.modal.trades.lots.edit', array('user_id' => $this->_player->id))
			)
		);
	}

	public function action_trades_lots()
	{
		$this->view = null;

		if (DataTables::is_request())
		{
			$orm = ORM::factory('User_Trade')
				->where('user_id', '=', $this->_player->id);

			$paginate = Paginate::factory($orm)
				->columns(array('description', 'created', 'id'))
				->search_columns(array('description'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $record)
			{
				$datatables->add_row(
					array(
						$record->id,
						$record->description,
						date('jS  M i:H', $record->created),
						$record->id
					)
				);
			}

			$datatables->render($this->response);
		}
		else
		{
			throw new HTTP_Exception_500();
		}
	}

	public function action_trades_bids()
	{
		$this->view = null;

		if (DataTables::is_request())
		{
			$orm = ORM::factory('User_Trade_Bid')
				->where('user_id', '=', $this->_player->id);

			$paginate = Paginate::factory($orm)
				->columns(array('created', 'points', 'trade_id', 'id'))
				->search_columns(array('trade_id'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $record)
			{
				$datatables->add_row(
					array(
						$record->id,
						$record->trade_id,
						date('jS  M i:H', $record->created),
						$record->points,
						$record->id
					)
				);
			}

			$datatables->render($this->response);
		}
		else
		{
			throw new HTTP_Exception_500();
		}
	}

	public function action_trades_cancel() {
		$id = $this->request->post('id');
		$type = $this->request->post('type');

		switch($type)
		{
			case 'trade':
			{
				$record = ORM::factory('User_Trade', $id);

				$bids = $record->bids->find_all();

				if(count($bids) > 0)
				{
					foreach($bids as $bid) {
						$this->_cancel_bid($bid);
					}
				}

				$lot_items = $record->items();

				foreach ($lot_items as $item)
				{
					$item->move('inventory', $item->amount);
				}

				$log = Journal::log('item.trade.' . $bid->trade_id . '.delete', 'item', 'Retracted lot :id by an Admin', array(':id' => $record->id));
				$log->notify($this->_player, 'items.trades.delete', array(':lot' => $record->id, 'username' => 'admin'));

				$record->delete();
				break;
			}
			case 'bid':
			{
				$record = ORM::factory('User_Trade_Bid', $id);

				$this->_cancel_bid($record);

				$record->delete();
				break;
			}
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array('status' => 'success')));
	}

	protected function _cancel_bid($bid) {
		$items = $bid->items();

		foreach ($items as $item)
		{
			$item->move('inventory', '*');
		}

		if ($bid->points > 0)
		{
			$this->_player->points += $bid->points;
			$this->_player->save();
		}

		$log = Journal::log('item.trade.' . $bid->trade_id . '.retract', 'item', 'Retracted bid for :id by an Admin', array(':id' => $bid->trade_id));
		$log->notify($bid->user, 'items.trades.reject', array(':lot' => $bid->trade_id, 'username' => 'admin'));

		$bid->delete();
	}

	public function action_trades_lot_load() {
		$id = $this->request->query('id');

		$item = ORM::factory('User_Trade', $id);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array(
			'id' => $item->id,
			'description' => $item->location,
			'name' => $item->item->name
		)));
	}

	public function action_trades_lot_edit() {
		$id = $this->request->query('id');

		$lot = ORM::factory('User_Trade', $id);

		try
		{
			$lot->values($this->request->post(), array('description'));

			$lot->save();

			$this->response->body(json_encode(array(
				'status' => 'success'
			)));
		}
		catch (ORM_Validation_Exception $e)
		{
			$this->response->body(json_encode(array(
				'status' => 'error',
				'errors' => $e->errors('models')
			)));
		}

		$this->response->headers('Content-Type', 'application/json');

	}
}
