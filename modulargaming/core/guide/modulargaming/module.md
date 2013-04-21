# Writing a module for Modular Gaming

Modules are simply an addition to the [Cascading Filesystem](../kohana/files). A module can add any kind of file (controllers, views, models, classes, templates, config files, etc.)

## Setup

* Create a directory for your module in modulargaming/module

* Create a init.php file and specify your routes.

		<?php defined('SYSPATH') OR die('No direct script access.');

		/**
 		* Routes for Module.
 		*/
		Route::set('module.welcome', 'module/welcome')
			->defaults(array(
				'directory'  => 'Module',
				'controller' => 'Welcome',
				'action'     => 'index',
		));

* Create a Controller in modulargaming/module/classes/Controller/Module/Welcome.php

		<?php defined('SYSPATH') OR die('No direct script access.');

		class Controller_Module_Welcome extends Abstract_Controller_Frontend {
	
			/**
			 * Show the user dashboard.
		 	*/
			public function action_index()
			{
				$this->view = new View_Module_Welcome;
				$this->view->hello_world = 'Hello World';
			}

		}


* Create a View in modulargaming/module/classes/View/Module/Welcome.php

		<?php defined('SYSPATH') OR die('No direct script access.');
		
		class View_Module_Welcome extends Abstract_View {

			public $title = 'Module';

		}

* Create a Model in modulargaming/module/classes/Model/Module/Welcome.php

		<?php defined('SYSPATH') OR die('No direct script access.');
		
		class Model_Module extends ORM {


		}

* Create a template in modulargaming/module/template/module/welcome.mustache

		Welcome, {{hello_world}}


* Finally enable your module by adding it to application/bootstrap.php

		'module'    => MGPATH.'module',		

* You can now access your module using /module
