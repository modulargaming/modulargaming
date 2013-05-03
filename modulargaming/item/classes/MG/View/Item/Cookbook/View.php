<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Cookbook_View extends Abstract_View_Inventory {

	public $title = 'Cook book';

	/**
	 * Id of the recipe's item we're trying to complete
	 * @var unknown_type
	 */
	public $id = NULL;

	/**
	 * Contains an Item_Recipe model
	 * @var Item_Recipe
	 */
	public $recipe = NULL;
	/**
	 * A list of materials needed to complete a recipe
	 * @var array
	 */
	public $materials = array();

	/**
	 * How many of the materials does the user have
	 * @var integer
	 */
	public $collected = 0;

	/**
	 * Build the item recipe info
	 * @return array
	 */
	public function recipe()
	{
		return array(
			'name' => $this->recipe->name,
			'img'  => $this->recipe->item->img(),
		);
	}

	/**
	 * Format the materials
	 * @return array
	 */
	public function materials()
	{

		if (count($this->materials) > 0)
		{
			foreach ($this->materials as $key => $material)
			{
				$this->materials[$key]['color'] = ($material['amount_needed'] == $material['amount_owned']) ? 'green' : 'red';
			}
		}

		return $this->materials;
	}

	/**
	 * If the user has all the materials build the button
	 * in order to complete the recipe.
	 *
	 * @return boolean|array
	 */
	public function collected()
	{
		if ($this->collected == FALSE)
		{
			return FALSE;
		}
		else
		{
			return array(
				'url'  => URL::site(Route::get('item.cookbook.complete')->uri(array('id' => $this->id))),
				'id'   => $this->id,
				'csrf' => $this->csrf()
			);
		}
	}

	protected function get_breadcrumb()
	{
		$item = $this->item;

		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Cookbook',
				'href'  => Route::url('item.cookbook', array('id' => $item->id))
			),
			array(
				'title' => $item->id,
				'href'  => Route::url('item.cookbook.view', array('id' => $item->id))
			)
		));
	}
}
