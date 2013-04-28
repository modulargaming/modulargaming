<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('assets', 'assets/(<file>)', array('file' => '.+.(?:jpe?g|png|gif|css|js)'))
	->defaults(array(
		'controller' => 'Assets',
		'action'     => 'Index',
	));

Route::set('core.admin.modules.manage', 'admin/modules')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Modules',
	'action'     => 'index',
));
Route::set('core.admin.modules.manage.save', 'admin/modules/save')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Modules',
	'action'     => 'save',
));
Route::set('core.admin.modules.config', 'admin/config')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Config',
	'action'     => 'index',
));
Route::set('core.admin.modules.config.save', 'admin/config/save')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Config',
	'action'     => 'save',
));
Route::set('core.admin.modules.config.load', 'admin/modules/config/<module>')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Modules',
	'action'     => 'config_load',
));
Route::set('core.admin.modules.config.manage', 'admin/modules/config/<module>/<set>')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Modules',
	'action'     => 'config_manage',
));
Route::set('core.admin.modules.config.edit', 'admin/modules/config/<module>/<set>/edit')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Modules',
	'action'     => 'config_edit',
));
Route::set('core.admin.modules.data', 'admin/data')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Data',
	'action'     => 'index',
));
Route::set('core.admin.modules.data.backup', 'admin/data/backup/<table>/<num>')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Data',
	'action'     => 'backup',
	'num' => 1
));
Route::set('core.admin.modules.data.run', 'admin/data/<group>/run')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Data',
	'action'     => 'run',
));

Event::listen('admin.nav_list', function ()
{
	return array(
		'title' => 'Game',
		'link'  => Route::url('core.admin.modules.manage'),
		'icon'  => 'icon-wrench',
		'items' => array(
			array(
				'title' => 'Modules',
				'link' => Route::url('core.admin.modules.manage')
			),
			array(
				'title' => 'Configuration',
				'link' => Route::url('core.admin.modules.config')
			),
			array(
				'title' => 'Data',
				'link' => Route::url('core.admin.modules.data')
			)
		)
	);
});

Route::set('core.google_drive.setup', 'user/google')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Data',
	'action'     => 'google',
));

MG::currency(Kohana::$config->load('core.currency'));