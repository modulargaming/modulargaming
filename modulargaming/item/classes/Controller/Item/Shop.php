<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * User shop controller
 *
 * Let the user manage his/her shop,
 * buy items
 *
 * @package    MG/Items
 * @category   Controller
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class Controller_Item_Shop extends Abstract_Controller_Frontend {
	protected $protected = TRUE;
	protected $_shop = NULL;

	public function action_index()
	{
		$config = Kohana::$config->load('items.user_shop');

		$shop = $this->_check_shop();

		if ($shop == FALSE)
		{
			$this->redirect(Route::get('item.user_shop.create')->uri());
		}

		$this->view = new View_Item_Shop_Index;
		$this->view->shop = $this->_shop->as_array();

		$this->view->units = ($config['size']['active']) ? $config['size'] : FALSE;

	}

	public function action_upgrade()
	{
		$shop = $this->_check_shop();

		//if the user already has a shop redirect to index
		if ($shop == TRUE)
		{
			$this->redirect(Route::get('item.user_shop.index')->uri());
		}

		$config = Kohana::$config->load('items.user_shop.size');

		//if the shops are upgradeable
		if ($config['active'] == TRUE)
		{
			if ($this->user->get_property('points', 200) >= $config['unit_cost'])
			{
				$this->user->set_property('points') = $this->user->get_property('points', 200) - $config['unit_cost'];
				$this->user->save();

				$this->_shop->size = $this->_shop->size + 1;
				$this->_shop->save();

				Hint::success(__('Your shop can now offer a maximum of :limit items.', array(':limit' => $config['unit_size'] * $this->_shop->size)));
			}
			else
			{
				Hint::error('You don\'t have enough :currency to upgrade your shop');
			}
		}

		$this->redirect(Route::get('item.user_shop.index')->uri());
	}

	public function action_update()
	{
		$shop = $this->_check_shop();

		//if the user already has a shop redirect to index
		if ($shop == TRUE)
		{
			$this->redirect(Route::get('item.user_shop.index')->uri());
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				$this->_shop->values($this->request->post(), array('title', 'description'))
					->save();

				Hint::success('Your shop has been updated.');
			} catch (ORM_Validation_Exception $e)
			{
				$errors = $e->errors('models');

				foreach ($errors as $error)
				{
					Hint::error($error);
				}
			}
		}

		$this->redirect(Route::get('item.user_shop.index')->uri());
	}

	public function action_create()
	{
		$shop = $this->_check_shop();

		//if the user already has a shop redirect to index
		if ($shop == TRUE)
		{
			$this->redirect(Route::get('item.user_shop.index')->uri());
		}

		$config = Kohana::$config->load('items.user_shop');

		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				if ($config['creation_cost'] != FALSE || $config['creation_cost'] > 0)
				{
					if ($this->user->get_property('points', 200) < $config['creation_cost'])
					{
						Hint::error('You can\'t afford to open a shop!');

						return $this->redirect(Route::get('item.user_shop.create')->uri());
					}
	
			//		$this->user->set_property('shop.points', 
					$this->user->get_property('points', 200) - $config['creation_cost']);
					$this->user->set_property = $this->user->get_property('points', 200) - $config['creation_cost'];
					$this->user->save();
				}

				//create the shop
				$shop = ORM::factory('User_Shop')
					->values($this->request->post(), array('title', 'description'));

				$shop->user_id = $this->user->id;
				$shop->size = 1;
				$shop->save();

			} catch (ORM_Validation_Exception $e)
			{
				$errors = $e->errors('models');

				foreach ($errors as $error)
				{
					Hint::error($error);
				}

				return $this->redirect(Route::get('item.user_shop.create')->uri());
			}

			Hint::success('You\'ve successfully created your own shop, congratulations!');
			$this->redirect(Route::get('item.user_shop.index')->uri());
		}

		$this->view = new View_Item_Shop_Create;
		$this->view->creation = FALSE;

		if ($config['creation_cost'] != FALSE || $config['creation_cost'] > 0)
		{
			$this->view->creation = array('cost' => $config['creation_cost'], 'affordable' => ($this->user->get_property('points', 200) < $config['creation_cost']));
		}
	}

	public function action_stock()
	{
		$shop = $this->_check_shop();

		if ($shop == FALSE)
		{
			$this->redirect(Route::get('item.user_shop.create')->uri());
		}

		$this->view = new View_Item_Shop_Stock;

		$items = Item::location('shop');

		$pagination = Paginate::factory($items, array(), $this->request);

		$this->view->items = $pagination->result();
		$this->view->pagination = $pagination->render();
		$this->view->inventory_url = Route::url('item.user_shop.inventory');
	}

	public function action_inventory()
	{
		$shop = $this->_check_shop();

		if ($shop == FALSE)
		{
			$this->redirect(Route::get('item.user_shop.create')->uri());
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			$lost_items = 0;
			$errors = FALSE;
			foreach ($this->request->post('item') as $id => $param)
			{
				$item = ORM::factory('User_item', $id);

				if (!$item_loaded())
				{
					$lost_items++;
					$errors = TRUE;
				}
				else if ($item->user_id != $this->user->id)
				{
					Hint::error('you\'re trying to change an item you don\'t own');
					$errors = TRUE;
				}
				else if ($item->location != 'shop')
				{
					Hint::error('You\'re trying to change an item that\'s not located in your shop');
					$errors = TRUE;
				}
				else if ($param['remove'] == 1)
				{
					//move the item to the inventory
					$item->move('inventory', '*');
				}
				else if (Valid::digit($param['price']) AND $param['price'] > -1)
				{
					//update the item's price
					$item->parameter = $param['price'];
					$item->save();
				}
			}

			if ($lost_items > 0)
			{
				Hint::error('Some items don\'t seem to exist anymore.');
			}
			else if ($errors != TRUE)
			{
				Hint::success('You\'ve successfully updated your shop\'s stock.');
			}
		}

		$this->redirect(Route::get('item.user_shop.stock'), array('page' => $this->request->param('page')));
	}

	public function action_logs()
	{
		$shop = $this->_check_shop();

		if ($shop == FALSE)
		{
			$this->redirect(Route::get('item.user_shop.create')->uri());
		}

		$this->view = new View_Item_Shop_Logs;

		$logs = ORM::factory('Log')
			->where('alias', '=', 'user_shop.' . $shop->id)
			->where('time', '>', strtotime('-30 days'))
			->limit(Kohana::$config->load('items.user_shop.log_limit'))
			->find_all();

		$this->view->logs = $logs;
	}

	public function action_collect()
	{
		$shop = $this->_check_shop();

		if ($shop == FALSE)
		{
			$this->redirect(Route::get('item.user_shop.create')->uri());
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			$amount = $this->request->post('amount');

			if (!Valid::digit($amount) AND $amount < 0)
			{
				Hint::error('The specified amount is unreadable');
			}
			else if ($amount > $this->_shop->till)
			{
				Hint::error('You\'re trying to collect more :currency than you have in your shop till.');
			}
			else if ($amount > 0)
			{
				$this->user->set_property('points') = $this->user->get_property('points', 200) + $amount;
				$this->user->save();

				$this->_shop->till = $this->_shop->till - $amount;
				$this->_shop->save();

				Hint::success(__('You\'ve successfully withdrawn :amount :currency_short from your shop till.', array(':amount' => $amount)));
			}
		}

		$this->redirect(Route::get('item.user_shop.logs')->uri());
	}

	public function action_view()
	{
		$id = $this->request->param('id');

		$shop = ORM::factory('User_Shop', $id);

		$this->view = new View_Item_Shop_View;
		$this->view->shop = $shop->as_array();
		$this->view->owner = $shop->user->as_array();

		if ($shop->loaded())
		{
			$inventory = Item::location('shop')
				->where('parameter', '>', '0')
				->find_all();

			$this->view->items = $inventory;
		}
	}

	public function action_buy()
	{
		$shop = ORM::factory('User_Shop', $this->request->param('id'));

		//if no shop's found redirect to previous page
		if (!$shop->loaded())
		{
			$this->redirect($this->request->referrer());
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			$item_id = $this->request->post('item_id');

			$item = ORM::factory('User_item', $item_id);

			if (!$item->loaded() OR $item->location != 'shop')
			{
				Hint::error('This item is not in stock');
			}
			else if ($this->user->get_property('points', 200) < $item->parameter)
			{
				Hint::error(__('You don\'t have enough :currency to buy a ":item_name"', array(':item_name' => $item->item->name)));
			}
			else
			{
				$this->user->set_property('points') = $this->user->get_property('points', 200) - $item->parameter;
				$this->user->save();

				//log this action
				$log = Journal::log('user_shop.' . $shop->id, 'item', 'Bought 1 :item_name for :amount from :user', array(
					'item' => $item->item,
					'item_name' => $item->item->name,
					'user' => $item->user->username,
					'amount' => $item->parameter
				));

				$item->transfer($this->user);

				$log->notify($shop->user, 'user_shop.buy');

				Hint::success(__('You\'ve successfully bought :item_name from :shop_owner for :amount :currency_short', array(':owner' => $shop->user->username, ':item_name' => $item->item->name('1'))));
			}
		}

		$this->redirect(Route::get('items_user_shop.view')->uri(array('id' => $shop->id)));
	}

	public function after()
	{
		if ($this->view !== NULL)
		{
			$map_links = array('index' => 0, 'stock' => 1, 'logs' => 2);
			$this->view->links = array(
				array('name' => 'Shop', 'link' => Route::url('item.user_shop.index')),
				array('name' => 'Stock', 'link' => Route::url('item.user_shop.stock')),
				array('name' => 'Logs', 'link' => Route::url('item.user_shop.logs')),
				array('name' => 'View', 'link' => $this->_view_shop())
			);

			if (array_key_exists($this->request->action(), $map_links))
			{
				$this->view->links[$map_links[$this->request->action()]]['active'] = TRUE;
			}
		}
		parent::after();
	}

	protected function _view_shop()
	{
		if ($this->_shop != NULL && $this->_shop->loaded())
		{
			return Route::url('item.user_shop.view', array('id' => $this->_shop->id));
		}
		else
		{
			return NULL;
		}
	}

	protected function _check_shop()
	{
		$this->_shop = ORM::factory('User_Shop')
			->where('user_id', '=', $this->user->id)
			->find();

		return $this->_shop->loaded();
	}
}
