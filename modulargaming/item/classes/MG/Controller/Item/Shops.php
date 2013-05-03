<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * NPC shop controller
 *
 * All shops get routed through here.
 *
 * @package    MG/Items
 * @category   Controller
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Controller_Item_Shops extends Abstract_Controller_Frontend {
	protected $protected = TRUE;

	//provide a list of all the shops
	public function action_index()
	{
		$this->view = new View_Item_Shops_Index;
		$this->view->shops = ORM::factory('Shop')
			->where('status', '=', 'open')
			->find_all();
	}

	public function action_view()
	{
		$id = $this->request->param('id');
		$shop = ORM::factory('Shop', $id);

		$this->view = new View_Item_Shops_View;

		if (!$shop->loaded())
		{
			Hint::error('No shop found');
		}
		elseif ($shop->status == 'closed')
		{
			//shop is closed
			Hint::error('The shop you want to visit seems to be closed.');
		}
		else
		{
			$this->view->shop_id = $id;
			$this->view->shop_title = $shop->title;
			$this->view->npc_img = $shop->img();
			$this->view->npc_text = $shop->npc_text;

			$inventory = ORM::factory('Shop_Inventory')
				->where('shop_id', '=', $shop->id)
				->find_all();

			$this->view->stock_count = count($inventory);
			$this->view->items = $inventory;
		}
	}

	public function action_buy()
	{

		$points = Kohana::$config->load('items.points');
		$initial_points = $points['initial'];

		$shop_id = $this->request->param('id');
		$shop = ORM::factory('Shop', $shop_id);

		if (!$shop->loaded())
		{
			Hint::error('You can\'t buy an item from a shop that does not exist.');
		}
		elseif ($shop->status == 'closed')
		{
			Hint::error('You\'re trying to buy an item from a closed shop.');
		}
		else
		{
			$item_id = $this->request->post('id');

			$item = ORM::factory('Shop_Inventory')
				->where('shop_id', '=', $shop->id)
				->where('item_id', '=', $item_id)
				->find();

			if (!$item->loaded())
			{
				Hint::error('The item you tried to buy has already been sold.');
			}
			elseif ($item->price > $this->user->get_property('points', $initial_points))
			{
				Hint::error('You don\'t have enough points to buy ' . $item->item->name);
			}
			else
			{
				//retract the points

				$this->user->set_property('points', $this->user->get_property('points', $initial_points) - $item->price);
				$this->user->save();

				//send over the item
				Item::factory($item->item)->to_user($this->user, 'shops.' . $shop_id);

				//remove from shop if needed
				if ($shop->stock_type != 'steady')
				{
					if ($item->stock - 1 == 0)
					{
						$item->delete();
					}
					else
					{
						$item->stock -= 1;
						$item->save();
					}
				}
				Hint::success('You\'ve successfully bought ' . $item->item->name);
			}
		}

		$this->redirect(Route::get('item.shops.view')->uri(array('id' => $shop_id)));
	}
}
