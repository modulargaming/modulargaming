# Controllers

A Controller is a class file that stands in between the models and the views in an application. It passes information on to the model when data needs to be changed and it requests information from the model when data needs to be loaded. Controllers then pass on the information of the model to the views where the final output can be rendered for the users. Controllers essentially control the flow of the application.

Controllers are called by the Request::execute() function based on the Route that the url matched. Be sure to read the routing page to understand how to use routes to map urls to your controllers.

		<?php defined('SYSPATH') OR die('No direct script access.');


		class Controller_Module_Index extends Abstract_Controller_Frontend {

			public function action_index()
			{

				$this->view = new View_Module_Index;

				$module = ORM::factory('Module')
					->where('user_id', '=', 1)
					->order_by('created', 'DESC');

				$paginate = Paginate::factory($module)
					->execute();

				$this->view->pagination = $paginate->render();
				$this->view->module = $paginate->result();

		}
