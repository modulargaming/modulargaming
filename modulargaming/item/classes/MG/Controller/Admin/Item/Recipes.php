<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item recipes admin controller
 *
 * Manage item recipes
 *
 * @package    MG/Items
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Controller_Admin_Item_Recipes extends Abstract_Controller_Admin {

	public function action_index()
	{
		if (!$this->user->can('Admin_Item_Recipes_Index'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item recipes index');
		}

		$this->view = new View_Admin_Item_Recipe;
		$this->_nav('items', 'recipes');

		$types = ORM::factory('Item_Recipe')
			->find_all();

		$this->view->recipes = $types;
	}

	public function action_paginate()
	{
		if (!$this->user->can('Admin_Item_Recipes_Paginate'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item recipies paginate');
		}

		if (DataTables::is_request())
		{
			$orm = ORM::factory('Item_Recipe');

			$paginate = Paginate::factory($orm)
				->columns(array('id', 'name', 'materials', 'crafted_item_id'))
				->search_columns(array('item.name'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $recipe)
			{
				$datatables->add_row(array(
						$recipe->name,
						$recipe->materials->count_all(),
						$recipe->item->img(),
						$recipe->id
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

	public function action_retrieve()
	{
		if (!$this->user->can('Admin_Item_Recipes_Retrieve'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item recipes retrieve');
		}

		$this->view = NULL;

		$item_id = $this->request->query('id');

		$item = ORM::factory('Item_Recipe', $item_id);


		$materials = $item->materials->find_all();
		$ingredients = array();

		foreach ($materials as $ingredient)
		{
			$ingredients[] = array(
				'id' => $ingredient->id,
				'name' => $ingredient->item->name,
				'amount' => $ingredient->amount
			);
		}

		$list = array(
			'id' => $item->id,
			'name' => $item->name,
			'description' => $item->description,
			'crafted_item' => $item->item->name,
			'materials' => $ingredients
		);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}

	public function action_save()
	{
		if (!$this->user->can('Admin_Item_Recipes_Save'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item recipes save');
		}

		$this->view = NULL;
		$values = $this->request->post();

		if ($values['id'] == 0)
		{
			$values['id'] = NULL;
		}

		$id = $values['id'];

		$this->response->headers('Content-Type', 'application/json');

		try
		{
			//validate crafted item
			$crafted = ORM::factory('Item')
				->where('item.name', '=', $values['crafted_item'])
				->find();


			if ($crafted->loaded())
			{
				//validate item materials
				$mat_fail = FALSE;

				if (count($values['materials']) > 0)
				{
					foreach ($values['materials'] as $index => $material)
					{
						$mat = ORM::factory('Item')
							->where('item.name', '=', $material['name'])
							->find();
						if (!$mat->loaded())
						{
							$mat_fail = $material['name'] . ' does not exist';
							break;
						}
						else if (!Valid::digit($material['amount']))
						{
							$mat_fail = $material['name'] . '\'s amount should be a number';
							break;
						}
						else
						{
							$values['materials'][$index]['item'] = $mat->id;
						}
					}
				}
				if ($mat_fail == FALSE)
				{
					$values['crafted_item_id'] = $crafted->id;

					$item = ORM::factory('Item_Recipe', $values['id']);
					$item->values($values, array('name', 'description', 'crafted_item_id'));
					$item->save();

					if (count($values['materials']) > 0)
					{
						//if we're updating delete old data
						if ($values['id'] != NULL)
						{
							foreach ($item->materials->find_all() as $mat)
							{
								$mat->delete();
							}
						}

						foreach ($values['materials'] as $key => $ingredient)
						{
							$mat = ORM::factory('Item_Recipe_Material');
							$mat->item_id = $ingredient['item'];
							$mat->amount = $ingredient['amount'];
							$mat->item_recipe_id = $item->id;
							$mat->save();
						}
					}
					$data = array(
						'action' => 'saved',
						'type' => ($id == NULL) ? 'new' : 'update',
						'row' => array(
							$item->name,
							$item->materials->count_all(),
							URL::base() . $item->item->img(),
							$item->id
						)
					);
					$this->response->body(json_encode($data));
				}
				else
				{
					return $this->response->body(json_encode(array('action' => 'error', 'errors' => array(array('field' => 'ingredients', 'msg' => array($mat_fail))))));
				}
			}
			else
			{
				return $this->response->body(json_encode(array('action' => 'error', 'errors' => array(array('field' => 'crafted_item', 'msg' => array('This item does not seem to exist.'))))));
			}
		} catch (ORM_Validation_Exception $e)
		{
			$errors = array();

			$list = $e->errors('models');

			foreach ($list as $field => $er)
			{
				if (!is_array($er))
				{
					$er = array($er);
				}

				$errors[] = array('field' => $field, 'msg' => $er);
			}

			$this->response->body(json_encode(array('action' => 'error', 'errors' => $errors)));
		}
	}

	public function action_delete()
	{
		if (!$this->user->can('Admin_Item_Recipes_Delete'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item recipes delete');
		}

		$this->view = NULL;
		$values = $this->request->post();

		$item = ORM::factory('Item_Recipe', $values['id']);
		$item->delete();

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}
}
