<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('user.dashboard', 'user(/dashboard)')
	->defaults(array(
	'directory'  => 'User',
	'controller' => 'Dashboard',
	'action'     => 'index',
));

Route::set('user.profile', 'user/profile(/<id>(/<page>))', array('id' => '[0-9]+', 'page' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'User',
	'controller' => 'Profile',
	'action'     => 'index',
));

Route::set('user.settings', 'user/settings')
	->defaults(array(
	'directory'  => 'User',
	'controller' => 'Settings',
	'action'     => 'index',
));

Route::set('user.login', 'user/login')
	->defaults(array(
	'directory'  => 'User',
	'controller' => 'Login',
	'action'     => 'index',
));
Route::set('user.logout', 'user/logout')
	->defaults(array(
	'directory'  => 'User',
	'controller' => 'Logout',
	'action'     => 'index',
));
Route::set('user.register', 'user/register')
	->defaults(array(
	'directory'  => 'User',
	'controller' => 'Register',
	'action'     => 'index',
));

Route::set('user.reset', 'user/reset(/<token>)')
	->defaults(array(
	'directory'  => 'User',
	'controller' => 'Reset',
	'action'     => 'index',
));

Route::set('user', 'user(/<controller>(/<action>(/<id>)))')
	->defaults(array(
	'directory'  => 'User',
	'controller' => 'Dashboard',
	'action'     => 'index',
));

Route::set('item.admin.avatar.retrieve', 'admin/avatars/retrieve')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Avatars',
	'action'     => 'retrieve',
));
Route::set('item.admin.avatar.paginate', 'admin/avatars/paginate')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Avatars',
	'action'     => 'paginate',
));
Route::set('item.admin.avatar.save', 'admin/avatars/save')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Avatars',
	'action'     => 'save',
));
Route::set('item.admin.avatar.delete', 'admin/avatars/remove')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Avatars',
	'action'     => 'delete',
));
Route::set('item.admin.avatar.index', 'admin/avatars')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Avatars',
	'action'     => 'index',
));

Route::set('user.admin.roles.retrieve', 'admin/roles/retrieve')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Roles',
	'action'     => 'retrieve',
));
Route::set('user.admin.roles.paginate', 'admin/roles/paginate')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Roles',
	'action'     => 'paginate',
));
Route::set('user.admin.roles.save', 'admin/roles/save')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Roles',
	'action'     => 'save',
));
Route::set('user.admin.roles.delete', 'admin/roles/remove')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Roles',
	'action'     => 'delete',
));
Route::set('user.admin.roles.index', 'admin/roles')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Roles',
	'action'     => 'index',
));

//User admin rules
Route::set('user.admin.index', 'admin/user')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'User',
	'action'     => 'index',
));
Route::set('user.admin.search', 'admin/user/search')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'User',
	'action'     => 'search',
));
Route::set('user.admin.lookup', 'admin/user/lookup')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'User',
	'action'     => 'lookup',
));
Route::set('user.admin.view', 'admin/user/<id>/view')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'User',
	'action'     => 'view',
));
Route::set('user.admin.save', 'admin/user/<id>/save')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'User',
	'action'     => 'edit',
));
Route::set('user.admin.logs.index', 'admin/logs')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Logs',
	'action'     => 'index',
));
Route::set('user.admin.logs.retrieve', 'admin/logs/retrieve')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Logs',
	'action'     => 'retrieve',
));
Route::set('user.admin.logs.paginate', 'admin/logs/paginate')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Logs',
	'action'     => 'paginate',
));
// Add link to manage users in admin
Event::listen('admin.nav_list', function ()
{
	return array(
		'title' => 'User',
		'link'  => URL::site('admin/user'),
		'icon'  => 'icon-user',
		'items' => array(
			array(
				'title' => 'List',
				'link' => Route::url('user.admin.index')
			),
			array(
				'title' => 'Avatars',
				'link' => Route::url('item.admin.avatar.index')
			),
			array(
				'title' => 'Roles',
				'link' => Route::url('user.admin.roles.index')
			),
			array(
				'title' => 'Logs',
				'link' => Route::url('user.admin.logs.index')
			)
		)
	);
});

Route::set('admin.user.modal.settings', 'admin/user/modal/settings/<user_id>')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'settings',
));
Route::set('admin.user.modal.settings.load', 'admin/user/modal/settings/<user_id>/load')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'settings_load',
));
Route::set('admin.user.modal.settings.read', 'admin/user/modal/settings/<user_id>/read')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'settings_read',
));
Route::set('admin.user.modal.settings.remove', 'admin/user/modal/settings/<user_id>/remove')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'settings_remove',
));
Route::set('admin.user.modal.settings.save', 'admin/user/modal/settings/<user_id>/save')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'settings_save',
));
Route::set('admin.user.modal.avatars', 'admin/user/modal/avatars/<user_id>')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'avatars',
));
Route::set('admin.user.modal.avatars.load', 'admin/user/modal/avatars/<user_id>/load')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'avatars_load',
));
Route::set('admin.user.modal.avatars.search', 'admin/user/modal/avatars/<user_id>/search')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'avatars_search',
));
Route::set('admin.user.modal.avatars.give', 'admin/user/modal/avatars/<user_id>/give')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'avatars_give',
));
Route::set('admin.user.modal.avatars.remove', 'admin/user/modal/avatars/<user_id>/remove')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'avatars_remove',
));
Route::set('admin.user.modal.messages', 'admin/user/modal/messages/<user_id>')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'messages',
));
Route::set('admin.user.modal.messages.sent', 'admin/user/modal/messages/<user_id>/load/sent')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'messages_sent',
));
Route::set('admin.user.modal.messages.received', 'admin/user/modal/messages/<user_id>/load/received')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'messages_received',
));
Route::set('admin.user.modal.messages.send', 'admin/user/modal/messages/<user_id>/send')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'messages_send',
));
Route::set('admin.user.modal.messages.read', 'admin/user/modal/messages/<user_id>/read')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'messages_read',
));
Route::set('admin.user.modal.messages.delete', 'admin/user/modal/messages/<user_id>/delete')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'messages_delete',
));
Route::set('admin.user.modal.logs', 'admin/user/modal/logs/<user_id>')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'logs',
));
Route::set('admin.user.modal.logs.load', 'admin/user/modal/logs/<user_id>/load')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'logs_load',
));
Route::set('admin.user.modal.logs.view', 'admin/user/modal/logs/<user_id>/view')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'logs_view',
));
Route::set('admin.user.modal.password', 'admin/user/modal/password/<user_id>')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'password',
));
Route::set('admin.user.modal.password.save', 'admin/user/modal/password/<user_id>/save')
	->defaults(array(
	'directory'  => 'Admin/Modal',
	'controller' => 'User',
	'action'     => 'password_save',
));
Event::listen('admin.user.view', function($user)
{
	$id = $user->id;
	Assets::factory('body')->js('admin.user.modal.dataTable', 'plugins/jquery.dataTables.js');
	return array(
		'links' => array(
			array(
				'title' => 'Settings',
				'handler' => 'settings',
				'link' => Route::url('admin.user.modal.settings', array('user_id' => $id))
			),
			array(
				'title' => 'Avatars',
				'handler' => 'avatars',
				'link' => Route::url('admin.user.modal.avatars', array('user_id' => $id))
			),
			array(
				'title' => 'Messages',
				'handler' => 'messages',
				'link' => Route::url('admin.user.modal.messages', array('user_id' => $id))
			),
			array(
				'title' => 'Logs',
				'handler' => 'logs',
				'link' => Route::url('admin.user.modal.logs', array('user_id' => $id))
			)
		),
	);
});