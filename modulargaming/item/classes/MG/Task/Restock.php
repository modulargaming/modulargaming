<?php defined('SYSPATH') or die('No direct script access.');

class MG_Task_Restock extends Minion_Task {
		protected $_options = array();

		/**
		 * Restock shops
		 *
		 * @return null
		 */
		protected function _execute(array $params)
		{
			$shops = array();

			//find items that need to be restocked
			$restocks = ORM::factory('Shop_Restock')
				->where('next_restock', '<=', time())
				->order_by(DB::expr('RAND()'))
				->find_all();

			foreach($restocks as $restock) {
				//cache the shop's cap limit and stock total
				if(!array_key_exists($restock->shop_id, $shops))
				{
					$shops[$restock->shop_id] = array(
						'cap' => $restock->shop->stock_cap,
						'stock' => ORM::factory('Shop_Inventory')
							->where('shop_id', '=', $restock->shop_id)
							->count_all()
					);
				}

				//only restock if we haven't reached the shop's item stack cap
				if($shops[$restock->shop_id]['cap'] != $shops[$restock->shop_id]['stock'])
				{
					//get randomised values for price and amount
					$price = mt_rand($restock->min_price, $restock->max_price);
					$amount = mt_rand($restock->min_amount, $restock->max_amount);

					$inventory = ORM::factory('Shop_Inventory')
						->where('shop_id', '=', $restock->shop_id)
						->where('item_id', '=', $restock->item_id)
						->find();

					//the item was still in stock, just update it
					if($inventory->loaded())
					{
						$amount = ($amount + $inventory->stock > $restock->cap_amount) ? $restock->cap_amount : $amount + $inventory->stock;
					}
					else
					{
						//add 1 to the shop's stock
						$shops[$restock->shop_id]['stock']++;

						//prepare the new item
						$inventory = ORM::factory('Shop_Inventory')
							->values(array('shop_id' => $restock->shop_id, 'item_id' => $restock->item_id));
					}

					//update stock & price
					$inventory->price = $price;
					$inventory->stock = $amount;
					$inventory->save();

					//set the next restock
					$restock->next_restock = time() + $restock->frequency;
					$restock->save();
				}
			}
		}
	}
