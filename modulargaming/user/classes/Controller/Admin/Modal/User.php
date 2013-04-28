<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Modal_User extends Abstract_Controller_Modal {

	public function action_messages()
	{
		$this->view = new View_Admin_User_Modal_Messages;
		$this->view->data_sources = array(
			array(
				'id' => 'sent',
				'url' => Route::url('admin.user.modal.messages.sent', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'received',
				'url' => Route::url('admin.user.modal.messages.received', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'send',
				'url' => Route::url('admin.user.modal.messages.send', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'delete',
				'url' => Route::url('admin.user.modal.messages.delete', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'read',
				'url' => Route::url('admin.user.modal.messages.read', array('user_id' => $this->_player->id))
			)
		);
	}

	public function action_messages_sent()
	{
		$this->view = null;

		if (DataTables::is_request())
		{
			$orm = ORM::factory('Message')
				->where('sender_id', '=', $this->_player->id);

			$paginate = Paginate::factory($orm)
				->columns(array('receiver_id', 'created', 'subject', 'id'))
				->search_columns(array('receiver.username', 'subject'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $record)
			{
				$datatables->add_row(
					array(
						$record->id,
						$record->receiver->username,
						$record->subject,
						date('jS M \'Y G:i', $record->created),
						$record->id
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

	public function action_messages_received()
	{
		$this->view = null;

		if (DataTables::is_request())
		{
			$orm = ORM::factory('Message')
				->where('receiver_id', '=', $this->_player->id);

			$paginate = Paginate::factory($orm)
				->columns(array('sender_id', 'created', 'subject', 'id'))
				->search_columns(array('sender.username', 'subject'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $record)
			{
				$datatables->add_row(
					array(
						$record->id,
						$record->sender->username,
						$record->subject,
						date('jS M \'Y G:i', $record->created),
						$record->id
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

	public function action_messages_send()
	{
		$this->view = null;

		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				$receiver = $this->_player;

				$message_data = Arr::merge($this->request->post(), array(
					'sender_id' => $this->user->id,
					'receiver_id' => $receiver->id,
				));

				ORM::factory('Message')
					->create_message($message_data, array(
					'receiver_id',
					'subject',
					'content',
					'sender_id',
				));

				Journal::log('admin.user.msg.'.$receiver->id, 'admin', ':username sent :other_user a message through the admin', array(':other_user' => $receiver->username));

				$this->response->headers('Content-Type', 'application/json');
				$this->response->body(json_encode(array(
					'status' => 'success'
				)));
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->response->headers('Content-Type', 'application/json');
				$this->response->body(json_encode(array(
					'status' => 'error',
					'errors' => $e->errors('models')
				)));
			}

		}
	}

	public function action_messages_read() {
		$id = $this->request->query('id');

		$msg = ORM::factory('Message', $id);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array(
			'status' => 'success',
			'fields' => array(
				'sender' => $msg->sender->username,
				'receiver' => $msg->receiver->username,
				'subject' => $msg->subject,
				'message' => $msg->content,
				'id' => $msg->id
			)
		)));
	}

	public function action_messages_delete() {
		$id = $this->request->post('id');

		$msg = ORM::factory('Message', $id);
		$msg->delete();

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array(
			'status' => 'success'
		)));
	}

	public function action_avatars()
	{
		$this->view = new View_Admin_User_Modal_Avatars;
		$this->view->data_sources = array(
			array(
				'id' => 'load',
				'url' => Route::url('admin.user.modal.avatars.load', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'search',
				'url' => Route::url('admin.user.modal.avatars.search', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'give',
				'url' => Route::url('admin.user.modal.avatars.give', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'remove',
				'url' => Route::url('admin.user.modal.avatars.remove', array('user_id' => $this->_player->id))
			)
		);
	}

	public function action_avatars_load()
	{
		$this->view = null;

		if (DataTables::is_request())
		{
			$orm = $this->_player->avatars;

			$paginate = Paginate::factory($orm)
				->columns(array('avatar.img', 'avatar.title'))
				->search_columns(array('avatar.title'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $record)
			{
				$datatables->add_row(
					array(
						$record->id,
						$record->img(),
						$record->title,
						$record->id
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

	public function action_avatars_search() {
		$title = $this->request->post('tile');

		$avatars = ORM::factory('Avatar')
			->where('title', 'LIKE', '%'.$title.'%');

		$list = array();

		if($avatars->count_all() > 0)
		{
			foreach($avatars as $av)
			{
				$list[] = $av->title;
			}
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}

	public function action_avatars_give() {
		$title = $this->request->post('tile');

		try {
			$avatar = ORM::factory('Avatar')
				->where('title', '=', $title);

			if(! $this->_player->has('avatars', $avatar))
			{
				$this->_player->add('avatars', $avatar);
				$return = array('status' => 'success', 'name' => $title);
			}
		}
		catch (ORM_Validation_Exception $e)
		{
			$return = array(
				'status' => 'error',
				'errors' => $e->errors('models')
			);
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($return));
	}

	public function action_avatars_remove() {
		$id = $this->request->post('id');

		try {
			$avatar = ORM::factory('Avatar', $id);

			if($this->_player->has('avatars', $avatar))
			{
				$this->_player->remove('avatars', $avatar);
				$return = array('status' => 'success');
			}
		}
		catch (ORM_Validation_Exception $e)
		{
			$return = array(
				'status' => 'error',
				'errors' => $e->errors('models')
			);
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($return));
	}

	public function action_settings()
	{
		$this->view = new View_Admin_User_Modal_Settings;
		$this->view->data_sources = array(
			array(
				'id' => 'load',
				'url' => Route::url('admin.user.modal.settings.load', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'read',
				'url' => Route::url('admin.user.modal.settings.read', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'save',
				'url' => Route::url('admin.user.modal.settings.save', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'remove',
				'url' => Route::url('admin.user.modal.settings.remove', array('user_id' => $this->_player->id))
			)
		);
	}

	public function action_settings_load()
	{
		$this->view = null;

		if (DataTables::is_request())
		{
			$orm = ORM::factory('User_Property')
				->where('user_id', '=', $this->_player->id);

			$paginate = Paginate::factory($orm)
				->columns(array('key', 'value', 'id'))
				->search_columns(array('key'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $record)
			{
				$datatables->add_row(
					array(
						$record->key,
						$record->value,
						$record->id
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

	public function action_settings_read() {
		$id = $this->request->query('id');

		$setting = ORM::factory('User_Property', $id);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array(
			'id' => $setting->id,
			'name' => $setting->key,
			'content' => $setting->value
		)));
	}

	public function action_settings_save() {
		$id = $this->request->post('id');

		try {
			$setting = ORM::factory('User_Property', $id);

			$setting->value = $this->request->post('content');
			$setting->save();

			$return = array('status' => 'success', 'name' => $setting->key);

		}
		catch (ORM_Validation_Exception $e)
		{
			$return = array(
				'status' => 'error',
				'errors' => $e->errors('models')
			);
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($return));
	}

	public function action_settings_remove() {
		$name = $this->request->query('name');

		try {
			ORM::factory('User_Property')
				->where('user_id', '=', $this->_player->id)
				->where('key', '=', $name)
				->delete();

			$return = array('status' => 'success');
		}
		catch (ORM_Validation_Exception $e)
		{
			$return = array(
				'status' => 'error',
				'errors' => $e->errors('models')
			);
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($return));
	}

	public function action_password()
	{
		$this->view = new View_Admin_User_Modal_Password;
		$this->view->data_sources = array(
			array(
				'id' => 'save',
				'url' => Route::url('admin.user.modal.password.save', array('user_id' => $this->_player->id))
			)
		);
	}

	public function action_password_save()
	{
		$pwd = $this->request->post('password');

		try {
			$this->_player->password = $pwd;
			$this->_player->save();

			$return = array('status' => 'success');
		}
		catch(ORM_Validation_Exception $e) {
			$return = array('status' => 'error', 'errors' => $e->errors('models'));
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($return));
	}

	public function action_logs()
	{
		$this->view = new View_Admin_User_Modal_Logs;
		$this->view->data_sources = array(
			array(
				'id' => 'load',
				'url' => Route::url('admin.user.modal.logs.load', array('user_id' => $this->_player->id))
			),
			array(
				'id' => 'view',
				'url' => Route::url('admin.user.modal.logs.view', array('user_id' => $this->_player->id))
			)
		);
	}

	public function action_logs_load()
	{
		if (DataTables::is_request())
		{
			$orm = ORM::factory('Log')
				->where('user_id', '=', $this->_player->id);

			$paginate = Paginate::factory($orm)
				->columns(array('id', 'user_id', 'location', 'alias', 'time', 'type'))
				->search_columns(array('user.username', 'alias', 'location', 'type'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $log)
			{
				$datatables->add_row(array(
						$log->type,
						$log->alias,
						$log->location,
						$log->time,
						$log->id
					)
				);
			}

			$datatables->render($this->response);
		}
		else
		{
			throw HTTP_Exception::factory(500, 'error');
		}
	}

	public function action_logs_view()
	{
		$this->view = NULL;

		$log_id = $this->request->query('id');

		$log = ORM::factory('Log', $log_id);

		$list = array(
			'id'      => $log->id,
			'user_id'   => $log->user_id,
			'username' => $log->user->username,
			'type' => $log->type,
			'alias' => $log->alias,
			'location' => $log->location,
			'client' => array(
				'agent' => $log->agent,
				'ip' => $log->ip
			),
			'time' => date('l jS F G:i:s', $log->time),
			'message' => __($log->message, $log->params),
			'params' => $log->params
		);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}
}
