<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Data extends Abstract_Controller_Admin {
	protected $_root_node = 'Game';
	protected $_node = 'Data';

	public function action_index() {
		$mig = new Model_Minion_Migration(Database::instance());

		$groups = array();
		$group_status = $mig->get_group_statuses();

		foreach($group_status as $g => $s)
		{
			$groups[$g] = array(
				'last_run' => ucfirst(str_replace('-', ' ', $s['description'])),
				'unrun' => array()
			);
		}

		$migrations = $mig->available_migrations();

		foreach($migrations as $migration)
		{
			$group = $migration['group'];
			$timestamp = $migration['timestamp'];
			if($group_status[$group]['timestamp'] < $timestamp)
			{
				$groups[$group]['unrun'][] = array(
					'description' => ucfirst(str_replace('-', ' ', $migration['description']))
				);
			}
		}

		$this->view = new View_Admin_Data;
		$this->view->groups = $groups;

		//google drive stuff
		include(Kohana::find_file('vendor', 'google/src/Google_Client'));
		include(Kohana::find_file('vendor', 'google/src/contrib/Google_DriveService'));

		$config = Kohana::$config->load('core.google_drive');
		$route = Route::url('core.google_drive.setup', array(), true);

		$client = new Google_Client();
		$client->setClientId($config['client_id']);
		$client->setClientSecret($config['client_secret']);
		$client->setRedirectUri($route);
		$client->setScopes(array('https://www.googleapis.com/auth/drive'));

		if(Session::instance()->get('driveAccessToken', null) == null)
		{
			$this->view->drive = array(
				'auth' => false,
				'link' => $client->createAuthUrl()
			);
			$this->view->tables = array();
			$this->view->table_requests = array();
		}
		else {
			$this->view->drive = array('auth' => true);

			$tables = DB::query(Database::SELECT, 'SHOW TABLE STATUS')->execute();

			$this->view->total_tables = $tables->count();

			$records_per_file = Kohana::$config->load('core.google_drive.records_per_file');
			$requests = array();
			$list = array();

			foreach($tables as $table) {
				$total = Database::instance()->count_records($table['Name']);
				$count = ($total > $records_per_file) ? ceil($total/$records_per_file) : 1;
				$leftover = $total;

				$split = array();

				for($i=1;$i<=$count;$i++)
				{
					$records = ($leftover >= $records_per_file) ? $records_per_file : $leftover;
					$leftover -= $records;

					$split[] = array(
						'num' => $i,
						'records' => $records,
						'id' => $table['Name'].$i
					);
					$requests[] = array(
						'url' => Route::url('core.admin.modules.data.backup', array('table' => $table['Name'], 'num' => $i)),
						'id' => $table['Name'].$i
					);
				}

				$list[] = array(
					'name' => $table['Name'],
					'total' => $total,
					'files' => $split
				);
			}

			$this->view->tables = $list;
			$this->view->table_requests = $requests;
		}

		Assets::factory('body')->js('qjax', 'plugins/jquery.qjax.min.js');
		Assets::factory('body')->js('data', 'admin/data.js');
	}

	public function action_run() {
		$group = $this->request->param('group');

		$manager = new Minion_Migration_Manager(Database::instance());
		$manager->run_migration($group);

		$executed = $manager->get_executed_migrations();

		$migrations = array();

		foreach($executed as $m)
		{
			$migrations[] = ucfirst(str_replace('-', ' ', $m['description']));
		}

		Hint::success('You\'ve successfully run the following migration(s): '.implode(', ', $migrations));
		$this->redirect(Route::url('core.admin.modules.data', array(), true));
	}

	public function action_google() {
		include(Kohana::find_file('vendor', 'google/src/Google_Client'));
		include(Kohana::find_file('vendor', 'google/src/contrib/Google_DriveService'));

		$config = Kohana::$config->load('core.google_drive');
		$route = Route::url('core.google_drive.setup', array(), true);

		$client = new Google_Client();
		$client->setClientId($config['client_id']);
		$client->setClientSecret($config['client_secret']);
		$client->setRedirectUri($route);
		$client->setScopes(array('https://www.googleapis.com/auth/drive'));

		if (isset($_GET['code'])) {
			Session::instance()->set('driveAccessToken', $client->authenticate($_GET['code']));
			$this->redirect(Route::url('core.admin.modules.data', array(), true).'#backup');
		} elseif (Session::instance()->get('driveAccessToken', null) == null) {
			$client->authenticate();
		}
	}

	public function action_backup() {
		include(Kohana::find_file('vendor', 'google/src/Google_Client'));
		include(Kohana::find_file('vendor', 'google/src/contrib/Google_DriveService'));

		$config = Kohana::$config->load('core.google_drive');
		$route = Route::url('core.google_drive.setup', array(), true);

		$client = new Google_Client();
		$client->setClientId($config['client_id']);
		$client->setClientSecret($config['client_secret']);
		$client->setRedirectUri($route);
		$client->setScopes(array('https://www.googleapis.com/auth/drive'));

		$client->setAccessToken(Session::instance()->get('driveAccessToken', null));

		$table = $this->request->param('table');
		$num = $this->request->param('num');

		$content = 'INSERT INTO '.$table.' VALUES';

		$records_per_page = Kohana::$config->load('core.google_drive.records_per_file');
		$page = ($num - 1) * $records_per_page;
		$results = DB::query(Database::SELECT, 'SELECT * FROM '.$table.' LIMIT '.$page.','.$records_per_page)->execute()->as_array();

		$c_results = count($results);
		$i=1;

		foreach($results as $r) {
			$content .= "\n(";
			$num_fields = count($r);
			$j=1;

			foreach($r as $f) {
				//add the field's value
				$content .= "'".addslashes($f)."'";

				//add a comma
				if($j < $num_fields)
					$content .=',';
				$j++;
			}

			$content .= ')';

			$content .= ($i < $c_results) ? ',' : ';';
			$i++;
		}

		$date = Date::formatted_time('now', 'Ymd');
		$service = new Google_DriveService($client);

		$folder_id = Session::instance()->get('drive.folder.'.$date, null);

		if($folder_id == null)
		{
			$folder = new Google_DriveFile();
			$folder->setTitle('MG backup - '.$date);
			$folder->setDescription('MG backup folder');
			$folder->setMimeType('application/vnd.google-apps.folder');
			$folder = $service->files->insert(
				$folder,
				array(
					'data' => null,
					'mimeType' => 'application/vnd.google-apps.folder'
				)
			);

			$folder_id = $folder['id'];
			Session::instance()->set('drive.folder.'.$date, $folder_id);
		}

		$file = new Google_DriveFile();
		$file->setTitle($date.'-'.$table.'-'.$num.'.sql');
		$file->setDescription('MG backup file for your '.$table.' table');
		$file->setMimeType('text/plain');

		$parent = new Google_ParentReference();
		$parent->setId($folder_id);
		$file->setParents(array($parent));

		$service->files->insert(
			$file,
			array(
				'data' => $content,
				'mimeType' => 'text/plain'
			)
		);

		$this->view = NULL;
		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array('status' => 'success')));
	}
}
