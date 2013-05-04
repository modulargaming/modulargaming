<?php defined('SYSPATH') OR die('No direct access allowed.');

	class MG_Model_User_Item extends ORM {


		protected $_table_columns = array(
		'id'          => NULL,
		'item_id'       => NULL,
		'user_id'    => NULL,
		'amount'    => NULL,
		'location' => NULL,
		'parameter'      => NULL,
		'parameter_id'     => NULL
		);

		protected $_belongs_to = array(
			'item' => array(
				'model'       => 'Item',
				'foreign_key' => 'item_id'
			),
			'user' => array(
				'model'       => 'User',
				'foreign_key' => 'user_id'
			),
		);

		protected $_load_with = array('item', 'user');

		public function rules()
		{
			return array(
				'amount'   => array(
					array('not_empty'),
					array('digit'),
				),
				'location' => array(
					array('not_empty'),
					array('max_length', array(':value', 60)),
				),
			);
		}

		/**
		 * Return the item's image url
		 *
		 * @return string
		 */
		public function img()
		{
			return $this->item->img();
		}

		/**
		 * Move the currently initialised item to a new location and update its quantity.
		 *
		 * Returns false if you're trying to move a higher amount of this item than you already have,
		 * if successful it will return the instance of the user item stack where the copies have moved to.
		 *
		 * @param string  $location     Location to send the item to
		 * @param integer $amount       How many are moving to $location
		 * @param boolean $single_stack Add to an existing stack or alway create a new stack
		 *
		 * @return boolean|Model_User_Item
		 */
		public function move($location, $amount = 1, $single_stack = TRUE)
		{
			if ($amount == '*')
			{
				$amount = $this->amount;
			}

			return $this->_relocate($this->user_id, $location, $amount, $single_stack);
		}

		/**
		 * Return the item's name prefixed with its amount
		 *
		 * @return string
		 */
		public function name()
		{
			if ($this->amount > 1)
			{
				return $this->amount . ' ' . Inflector::plural($this->item->name, $this->amount);
			}
			else
			{
				return $this->amount . ' ' . $this->item->name;
			}
		}

		/**
		 * Transfer the initialised item to a different user.
		 *
		 * Returns false if you're trying to transfer a higher amount of this item than the owner already has,
		 * if successfull it will return a user item instance of where the item copies transfered to.
		 *
		 * @param Model_User $user   A user model instance of the new owner
		 * @param Integer    $amount The amount of copies you want to transfer
		 *
		 * @throws Item_Exception When trying to transfer an untransferable item
		 * @return boolean|Model_User_Item
		 */
		public function transfer(Model_User $user, $amount = 1)
		{
			if ($this->item->transferable == FALSE)
			{
				Throw new Item_Exception('":item" is bound to your account only.', array(':item' => $this->item->name));
			}
			else
			{
				$this->_relocate($user->id, 'inventory', $amount);
					return Journal::log('transfer'.$this->item->id, 'items.gift', ':item_name transferred to :other_user',
						array(':item_name' => $this->item->name, ':other_user' => $user->username));
			}
		}

		/**
		 * Move the item to
		 * - a new location
		 * - a new user
		 *
		 * @param integer $user_id      The (new) owner of the item
		 * @param string  $location     The new location of the item
		 * @param integer $amount       The amount of copies to relocate
		 * @param boolean $single_stack Add to an existing stack or alway create a new stack
		 *
		 * @return boolean|Model_User_Item
		 */
		protected function _relocate($user_id, $location, $amount, $single_stack = TRUE)
		{
			if ($amount > $this->amount)
			{
				return FALSE;
			}

			$item = ORM::factory('User_Item');

			if ($single_stack == TRUE)
			{
				//check if the item already has a stack for its new location
				$item->where('user_id', '=', $user_id)
					->where('item_id', '=', $this->item_id)
					->where('location', '=', $location)
					->find();
			}

			if ($item->loaded())
			{
				$item->amount += $amount;
				$item->save();
			}
			else
			{ //doesn't seem like it, let's create a new stack
				$item = ORM::factory('User_Item');
				$item->user_id = $user_id;
				$item->item_id = $this->item_id;
				$item->location = $location;
				$item->amount = $amount;
				$item->save();
			}

			//update the amount
			$new_amount = $this->amount - $amount;

			if ($new_amount == 0)
			{
				$this->delete();
			}
			else
			{
				$this->amount = $new_amount;
				$this->save();
			}

			return $item;
		}

		/**
		 * Change the amount of the loaded item.
		 *
		 * @param string  $type (add|+|subtract|-)
		 * @param integer $amount
		 *
		 * @return boolean
		 */
		public function amount($type, $amount = 1)
		{
			if ($amount < 0)
			{
				return FALSE;
			}

			switch ($type)
			{
				case 'add':
				case '+':
					$this->amount = $this->amount + $amount;
					$this->save();

					return TRUE;
					break;
				case 'subtract':
				case '-':
					if ($this->amount > $amount)
					{
						$this->amount = $this->amount - $amount;
						$this->save();

						return TRUE;
					}
					else if ($amount == $this->amount)
					{
						$this->delete();

						return TRUE;
					}
					else
					{
						return FALSE;
					}
					break;
			}
		}

	} // End User_Item Model
