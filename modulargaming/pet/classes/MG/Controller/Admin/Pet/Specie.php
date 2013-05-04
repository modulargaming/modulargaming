<?php defined('SYSPATH') OR die('No direct script access.');

	class MG_Controller_Admin_Pet_Specie extends Controller_Admin_Pet {

		public function action_index()
		{

			if (!$this->user->can('Admin_Pet_Specie_Index'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pets specie index');
			}

			$species = ORM::factory('Pet_Specie')
				->find_all();

			$colours = ORM::factory('Pet_Colour')
				->find_all();

			$this->view = new View_Admin_Pet_Specie_Index;
			$this->_nav('pet', 'specie');
			$this->view->species = $species->as_array();
			$this->view->colours = $colours->as_array();
			$this->view->image_dim = Kohana::$config->load('pet.image');
		}

		public function action_paginate() {

			if (!$this->user->can('Admin_Pet_Specie_Paginate'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pets specie paginate');
			}

			if (DataTables::is_request())
			{
				$orm = ORM::factory('Pet_Specie');

				$paginate = Paginate::factory($orm)
					->columns(array('id', 'name'));

				$datatables = DataTables::factory($paginate)->execute();

				foreach ($datatables->result() as $specie)
				{
					$datatables->add_row(array (
							$specie->name,
							$specie->id
						)
					);
				}

				$datatables->render($this->response);
			}
			else
				throw new HTTP_Exception_500();
		}

		public function action_retrieve()
		{

			if (!$this->user->can('Admin_Pet_Specie_Retrieve'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pets specie retrieve');
			}

			$this->view = NULL;

			$item_id = $this->request->query('id');

			if ($item_id == NULL)
			{
				$specie = ORM::factory('Pet_Specie')
					->where('pet_specie.name', '=', $this->request->query('name'))
					->find();
			}
			else
			{
				$specie = ORM::factory('Pet_Specie', $item_id);
			}

			$list = array(
				'name'        => $specie->name,
				'id'          => $specie->id,
				'description' => $specie->description,
				'dir'         => $specie->dir
			);
			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode($list));
		}

		public function action_save()
		{

			if (!$this->user->can('Admin_Pet_Specie_Save'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pets specie save');
			}

			$values = $this->request->post();
			$this->view = NULL;

			if ($values['id'] == 0)
			{
				$values['id'] = NULL;
			}

			$this->response->headers('Content-Type', 'application/json');

			try
			{
				$specie = ORM::factory('Pet_Specie', $values['id']);
				$specie->values($values, array('name', 'description', 'dir'));
				$specie->save();

				$data = array(
					'action' => 'saved',
					'row'    => array(
						$specie->id,
						$specie->name
					)
				);
				$this->response->body(json_encode($data));
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

			if (!$this->user->can('Admin_Pet_Specie_Delete'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pets specie delete');
			}

			$this->view = NULL;
			$values = $this->request->post();

			$specie = ORM::factory('Pet_Specie', $values['id']);
			$specie->delete();

			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode(array('action' => 'deleted')));
		}

		public function action_colour_load()
		{

			if (!$this->user->can('Admin_Pet_Specie_Colour_Load'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pets specie colour load');
			}

			$specie = ORM::factory('Pet_Specie', $this->request->query('id'));

			$colours = $specie->colours->find_all();
			$list = array();

			foreach ($colours as $colour)
			{
				$list[] = $colour->id;
			}

			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode(array('colours' => $list)));
		}

		public function action_colour_delete()
		{

			if (!$this->user->can('Admin_Pet_Specie_Colour_Delete'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pets specie colour delete');
			}

			$specie = ORM::factory('Pet_Specie', $this->request->query('specie_id'));
			$colour = ORM::factory('Pet_Colour', $this->request->query('colour_id'));

			if ($specie->has('colours', $colour))
			{
				$specie->remove('colours', $colour);
			}

			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode(array('action' => 'deleted')));
		}

		public function action_colour_update()
		{

			if (!$this->user->can('Admin_Pet_Specie_Colour_Update'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pets specie colour update');
			}

			$specie = ORM::factory('Pet_Specie', $this->request->post('specie_id'));

			$colour = $this->request->post('colour_id');
			$c = ORM::factory('Pet_Colour', $colour);

			//handle upload
			$file = array('status' => 'empty', 'msg' => '');

			if (isset($_FILES['image']))
			{
				$image = $_FILES['image'];
				$cfg = Kohana::$config->load('pet.image');

				if (!Upload::valid($image))
				{
					//error not valid upload
					$file = array('status' => 'error', 'msg' => 'You did not provide a valid file to upload.');
				}
				else if (!Upload::image($image, $cfg['width'], $cfg['height'], TRUE))
				{
					//not the right image dimensions
					$file = array('status' => 'error', 'msg' => 'You need to provide a valid image (size: :width x :heigth.', array(
						':width' => $cfg['width'], ':height' => $cfg['height']
					));
				}
				else
				{
					$msg = '';
					if (file_exists(DOCROOT . 'media/image/pets/' . $specie->dir . $c->image))
					{
						//move the previously stored item to the graveyard
						$new_name = Text::random('alnum', 4) . $specie->name;
						copy(DOCROOT . 'media/image/pets/' . $specie->dir . $c->image, DOCROOT . 'assets/graveyard/pets/' . $new_name);
						unlink(DOCROOT . 'media/image/pets/' . $specie->dir . $c->image);
						$msg = 'The old image has been moved to the graveyard and renamed to ' . $new_name;
					}

					$up = Upload::save($image, $c->image, DOCROOT . 'media/image/pets/' . $specie->dir);

					if ($up != FALSE)
					{
						$file['status'] = 'success';
						$file['msg'] = 'You\'ve successfully uploaded your pet image';

						if (!empty($msg))
						{
							$file['msg'] .= '<br />' . $msg;
						}
					}
					else
					{
						$file = array('status' => 'error', 'msg' => 'There was an error uploading your file.');
					}
				}
			}

			//save colour
			$specie->add('colours', $c);

			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode($file));
		}
	}
