# Models

The model manages the behavior and data of the application domain, responds to requests for information about its state (usually from the view), and responds to instructions to change state (usually from the controller).


		<?php defined('SYSPATH') OR die('No direct access allowed.');
		
		//Extend ORM

		class Model_Module extends ORM
		{
		
		// Define the table columns

			protected $_table_columns = array(
				'id'          => NULL,
				'user_id'     => NULL,
			);

		}
