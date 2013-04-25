# Views

Views are files that contain the display information for your application. This is most commonly HTML, CSS and Javascript but can be anything you require such as XML or JSON for AJAX output. The purpose of views is to keep this information separate from your application logic for easy reusability and cleaner code.

Views themselves can contain code used for displaying the data you pass into them. For example, looping through an array of product information and display each one on a new table row. Views are still PHP files so you can use any code you normally would. However, you should try to keep your views as "dumb" as possible and retreive all data you need in your controllers, then pass it to the view.

Views should be expressive and output all of the data sent from the Controller.


		<?php defined('SYSPATH') OR die('No direct script access.');

		class View_Module_Index extends Abstract_View {

			// Define title
			public $title = 'Example';

			// Define links
			public function links()
			{
				return array(
					'index' => Route::url('module.index'),
					'page' => Route::url('module.page'),
				);
			}

			public function module()
			{
				$module[] = array(
					'id' => $this->module->id,
					'user_id' => $this->module->user_id
					);
				return $module;
			}

		}
