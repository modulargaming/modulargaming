<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item safe controller
 *
 * List and move items
 *
 * @package    MG/Items
 * @category   Controller
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Controller_Item_Safe extends Abstract_Controller_Frontend {
	protected $protected = TRUE;

	public function action_index()
	{
		$this->view = new View_Item_Safe_Index;

		$config = Kohana::$config->load('items.safe');
		$max_items = $config['pagination'];

		$items = Item::location('safe');

		$paginate = Paginate::factory($items, array('total_items' => $max_items), $this->request)->execute();

		$this->view->pagination = $paginate->render();
		$this->view->items = $paginate->result();

		$this->view->process_url = Route::url('item.safe.move');
		$this->view->shop = ORM::factory('User_Shop')
			->where('user_id', '=', $this->user->id)
			->find()
			->loaded();

		$this->view->links = array(
			array('name' => 'Inventory', 'link' => Route::url('item.inventory')),
			array('name' => 'Shop', 'link' => Route::url('item.user_shop.index')),
			array('name' => 'Cookbook', 'link' => Route::url('item.cookbook'))
		);
	}

	public function action_move()
	{
		$items = $this->request->post('items');

		if (count($items) > 0)
		{
			foreach ($items as $id => $item)
			{
				if ($item['amount'] > 0)
				{
					$i = ORM::factory('User_Item', $id);

					if ($i->loaded() && $i->location == 'safe' && $i->user_id == $this->user->id)
					{
						if ($item['amount'] > $i->amount)
						{
							Hint::error(__('You can\'t move :name, you only have :amount.',
									array(
										':amount' => $i->amount,
										':name' => $i->item->name($item['amount'])
									))
							);
						}
						else if ($item['location'] == 'shop')
						{
							$shop = ORM::factory('User_Shop')
								->where('user_id', '=', $this->user->id)
								->find();

							$shop_item = ORM::factory('User_Item')
								->where('user_id', '=', $this->user->id)
								->where('location', '=', 'shop')
								->where('item_id', '=', $i->item_id)
								->find();

							if (!$shop->loaded())
							{
								Hint::error('You don\'t have a shop yet.');
							}
							else if (!$shop->inventory_space() && !$shop_item->loaded())
							{
								Hint::error('Your shop is already full.');
							}
							else
							{
								$i->move('shop', $item['amount']);
								Hint::success(__('You\'ve moved :items to your shop.', array(':items' => $i->item->name($item['amount']))));
							}
						}
						else if ($item['location'] == 'inventory')
						{
							$i->move('inventory', $item['amount']);
							Hint::success(__('You\'ve moved :items to your inventory.', array(':items' => $i->item->name($item['amount']))));
						}
					}
				}
			}
		}

		$this->redirect(Route::get('item.safe')->uri());
	}
}
