<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item inventory controller
 *
 * List and consume items
 *
 * @package    MG/Items
 * @category   Controller
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Controller_Item_Inventory extends Abstract_Controller_Frontend {
	protected $protected = TRUE;

	public function action_index()
	{
		$this->view = new View_Item_Inventory_Index;

		$config = Kohana::$config->load('items.inventory');
		$max_items = $config['pagination'];

		if ($config['ajax'] === TRUE)
		{
			$this->view->ajax = TRUE;
		}

		$items = Item::location('inventory');

		$paginate = Paginate::factory($items, array('total_items' => $max_items), $this->request)->execute();

		$this->view->pagination = $paginate->render();
		$this->view->items = $paginate->result();
		$this->view->links = array(
			array('name' => 'Safe', 'link' => Route::url('item.safe')),
			array('name' => 'Shop', 'link' => Route::url('item.user_shop.index')),
			array('name' => 'Cookbook', 'link' => Route::url('item.cookbook'))
		);
	}

	public function action_view()
	{
		$id = $this->request->param('id');

		$item = ORM::factory('User_Item', $id);

		$errors = array();

		if (!$item->loaded())
		{
			Hint::error('Item could not be found');
		}
		else if ($item->user_id != $this->user->id)
		{
			Hint::error('You can\'t access another player\'s item');
		}
		else if ($item->location != 'inventory')
		{
			Hint::error('The item you want to view is not located in your inventory.');
		}
		else
		{
			//generate action list
			$actions = array();
			$extra_action_fields = array();

			$default_command = Item_Command::factory($item->item->type->default_command);

			if ($default_command->pets_required() == TRUE)
			{
				$pets = ORM::factory('User_Pet')
					->where('user_id', '=', $this->user->id)
					->find_all();

				if (count($pets) > 0)
				{
					foreach ($pets as $pet)
					{
						$actions[$pet->id] = array(
							'item'  => __($item->item->type->action, array(':pet_name' => $pet->name)),
							'extra' => $default_command->inventory()
						);
					}
				}
			}
			else
			{
				$actions['consume'] = array(
					'item'  => $item->item->type->action,
					'extra' => $default_command->inventory()
				);
			}

			$actions['move_safe'] = array(
				'item'  => 'Move to safe',
				'extra' => Item_Command::factory('Move_Safe')->inventory()
			);

			$user_shop = ORM::factory('User_Shop')
				->where('user_id', '=', $this->user->id)
				->find();

			$shop_item = ORM::factory('User_Item')
				->where('user_id', '=', $this->user->id)
				->where('location', '=', 'shop')
				->where('item_id', '=', $item->item_id)
				->find();

			if ($user_shop->loaded() && ($user_shop->inventory_space() == TRUE || ($user_shop->inventory_space() == FALSE && $shop_item->loaded())))
			{
				$actions['move_shop'] = array(
					'item'  => 'Move to your shop',
					'extra' => Item_Command::factory('Move_Shop')->inventory()
				);
			}

			if ($item->item->transferable == TRUE)
			{
				$actions['gift'] = array(
					'item'  => 'Send as gift',
					'extra' => Item_Command::factory('General_Gift')->inventory()
				);
			}

			$actions['remove'] = array(
				'item'  => 'Remove item',
				'extra' => Item_Command::factory('General_Remove')->inventory()
			);

			$ajax = array();

			if ($this->request->is_ajax())
			{
				$ajax = array('actions' => $actions, 'name' => $item->item->name);
			}

		}

		//get a normalised array based on Hints' types
		$dump = Hint::dump();

		if ($this->request->is_ajax())
		{
			$this->response->headers('Content-Type', 'application/json');

			//Delete the Hints from sessions
			Hint::ajax_dump();

			//return normalized ajax response
			return $this->response->body(json_encode(array_merge($dump, $ajax)));
		}

		//if the response's status is an error there's nothing to show anymore
		if ($dump['status'] == 'error')
		{
			return $this->redirect(Route::get('item.inventory')->uri());
		}

		//otherwise render the page
		$this->view = new View_Item_Inventory_View;
		$this->view->item = $item;
		$this->view->action_list = $actions;
		//Assets::js('item.inventory', 'item/inventory/view.js');
	}

	public function action_consume()
	{
		$item = ORM::factory('User_Item', $this->request->param('id'));
		$action = $this->request->post('action');

		$errors = array();

		if (!$item->loaded())
		{
			Hint::error('You can\'t use an item that does not exist');
		}
		else if ($item->user_id != $this->user->id)
		{
			Hint::error('You can\'t access another player\'s item');
		}
		else if ($item->location != 'inventory')
		{
			Hint::error('The item you want to view is not located in your inventory');
		}
		else if ($action == NULL)
		{
			Hint::error('No action to perform has been specified');
		}
		else
		{
			$def_cmd = Item_Command::factory($item->item->type->default_command);

			if (Valid::digit($action))
			{
				//we'll want to perform an action on a pet
				$pet = ORM::factory('User_Pet', $action);

				if (!$pet->loaded())
				{
					Hint::error('No existing pet has been specified');
				}
				else if ($pet->user_id != $this->user->id)
				{
					Hint::error('You can\'t let a pet comsume this item if it\'s not yours');
				}
				else if ($def_cmd->pets_required() == FALSE)
				{
					Hint::error('can\'t perform this item action on a pet');
				}
				else
				{
					$commands = $item->item->commands;
					$results = array();

					$db = Database::instance();
					$db->begin();
					$error = FALSE;
					foreach ($commands as $command)
					{
						$cmd = Item_Command::factory($command['name']);
						$res = $cmd->perform($item, $command['param'], $pet);

						if ($res == FALSE)
						{
							//the command couldn't be performed, spit out error, rollback changes and break the loop
							Hint::error(__(':item_name could not be used on :pet_name', array(':item_name' => $item->item->name, ':pet_name' => $pet->name)));
						$error = TRUE;
							$db->rollback();
							break;
						}
						else
						{
							$results[] = $res;
						}
					}

					if ($error == FALSE)
					{
						$log = Journal::log('consume', 'item', ':item_name consumed', array(':item_name' => $item->item->name));
						$log->notify('consume'.$item->item_id, 'item', ':item_name consumed');

						if ($def_cmd->delete_after_consume == TRUE)
						{
							$item->amount('-', 1);
						}

						$db->commit();


					}
				}
			}
			else
			{
				$results = array();

				switch ($action)
				{
					case 'consume' :
						$commands = $item->item->commands;
						$results = array();

						$db = Database::instance();
						$db->begin();
						$error = FALSE;
						foreach ($commands as $command)
						{
							$cmd = Item_Command::factory($command['name']);
							$res = $cmd->perform($item, $command['param']);

							if ($res == FALSE)
							{
								//the command couldn't be performed, spit out error, rollback changes and break the loop
								Hint::error(__(':item_name could not be used', array(':item_name' => $item->name)));
								$db->rollback();
								$error = TRUE;
								break;
							}
							else
							{
								$results[] = $res;
							}
						}

						if ($error = FALSE)
						{
							Journal::log('consume'.$item->item_id, 'item', ':item_name consumed', array(':item_name' => $item->item->name));
							if ($def_cmd->delete_after_consume == TRUE)
							{
								$item->amount('-', 1);
							}

							$db->commit();
						}

						break;
					case 'remove' : //takes an amount
						$amount = $this->request->post('amount');

						if ($amount == NULL)
						{
							$amount = 1;
						}

						if (!Valid::digit($amount))
						{
							Hint::error('The amount you submitted isn\'t a number.');
						}
						else if ($amount <= 0 OR $amount > $item->amount)
						{
							Hint::error('You only have ' . $item->name() . ', not ' . $amount);
						}
						else
						{
							if ($amount > 1)
							{
								$name = Inflector::plural($item->name(), $amount);
								$verb = 'were';
							}
							else
							{
								$name = $item->item->name(1);
								$verb = 'was';
							}

							$item->amount('-', $amount);
							Journal::log('remove.'.$item->item_id, 'item', ':item_name removed', array(':item_name' => $name));
							$results = __(':item :verb deleted successfully', array(
								':verb' => $verb, ':item' => $name
							));
						}
						break;
					case 'gift' : //takes a username
						$username = $this->request->post('username');

						if ($this->user->username == $username)
						{
							Hint::error('You can\'t send a gift to yourself');
						}
						else
						{
							$user = ORM::factory('User')
								->where('username', '=', $username)
								->find();

							if ($user->loaded())
							{
								$log = $item->transfer($user);

								$log->notify($user, 'items.gift', array(':item_name' => $item->item->name(1)));

								$results = __('You\'ve successfully sent :item to :username', array(
									':item' => $item->item->name, ':username' => $user->username
								));
							}
							else
							{
								Hint::error(__('Couldn\'t find a user named ":username"', array(':username' => $username)));
							}
						}
						break;
					default :
						if (substr($action, 0, 5) == 'move_') //Moving items can take an amount
						{
							$location = substr($action, 5);
							$cmd = Item_Command::factory('Move_' . ucfirst($location));

							$amount = $this->request->post('amount');

							if ($amount == NULL)
							{
								$amount = 1;
							}

							if (!Valid::digit($amount))
							{
								Hint::error('The amount you submitted isn\'t a number.');
							}
							else if ($amount <= 0 OR $amount > $item->amount)
							{
								Hint::error('You only have ' . $item->name() . ', not ' . $amount);
							}
							else
							{
								$results = $cmd->perform($item, $amount);
							}

						}
						else //fallback to any unexisting item actions
						{
							Hint::error('The action you want to perform with this item does not exist');
						}
						break;
				}
			}
		}

		$show = Kohana::$config->load('items.inventory.consume_show_results');
		$output = array();

		if (!is_array($results))
		{
			$output[] = $results;
		}
		else if ($show == 'first')
		{
			$output[] = $results[0];
		}
		else if (!empty($results))
		{
			foreach ($results as $result)
				$output[] = $result;
		}

		if ($this->request->is_ajax())
		{
			$return = array();

			$return = Hint::dump();
			Hint::ajax_dump();

			if ($return['status'] == 'success')
			{
				$amount = ($item->loaded()) ? $item->name() : 0;

				$return = array_merge($return, array('result' => $output, 'new_amount' => $amount));
			}

			$this->response->headers('Content-Type', 'application/json');

			return $this->response->body(json_encode($return));
		}

		if (count($output) > 0)
		{
			foreach ($output as $result)
			{
				Hint::success($result);
			}
		}
		$this->redirect(Route::get('item.inventory')->uri());
	}
}

