<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Colour model, used for pets.
 *
 * @package    MG/Pet
 * @category   Model
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Model_Pet_Colour extends ORM {

	protected $_table_columns = array(
		'id'          => NULL,
		'locked'       => NULL,
		'name'    => NULL,
		'description'    => NULL,
		'image'      => NULL
	);

	protected $_has_many = array(
		'pets' => array(
			'model' => 'User_Pet',
			'foreign_key' => 'colour_id',
		),
		'species' => array(
			'model' => 'Pet_Specie',
			'through' => 'pet_species_colours',
			'foreign_key' => 'colour_id',
			'far_key' => 'specie_id'
		)
	);

}
