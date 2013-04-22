<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('user.dashboard', 'user/dashboard')
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

Route::set('item.admin.avatar.retrieve', 'admin/avatar/retrieve')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Avatars',
	'action'     => 'retrieve',
));
Route::set('item.admin.avatar.paginate', 'admin/avatar/paginate')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Avatars',
	'action'     => 'paginate',
));
Route::set('item.admin.avatar.save', 'admin/avatar/save')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Avatars',
	'action'     => 'save',
));
Route::set('item.admin.avatar.delete', 'admin/avatar/remove')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Avatars',
	'action'     => 'delete',
));
Route::set('item.admin.avatar.index', 'admin/avatar')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Avatars',
	'action'     => 'index',
));

Route::set('user.admin.index', 'Admin/User')
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
Route::set('user.admin.user.paginate', 'admin/user/user/paginate')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'User',
	'action'     => 'paginate',
));
Route::set('user.admin.user.retrieve', 'admin/user/user/retrieve')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'User',
	'action'     => 'retrieve',
));
Route::set('user.admin.user.delete', 'admin/user/user/remove')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'User',
	'action'     => 'delete',
));
Route::set('user.admin.user.save', 'admin/user/user/save')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'User',
	'action'     => 'save',
));
Route::set('user.admin.user.role.load', 'admin/user/user/role/load')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'User',
	'action'     => 'role_load',
));
Route::set('user.admin.user.role.update', 'admin/user/user/role/update')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'User',
	'action'     => 'role_update',
));
Route::set('user.admin.user.role.delete', 'admin/user/user/role/delete')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'User',
	'action'     => 'role_delete',
));
Route::set('user.admin.user.index', 'admin/user/user')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'User',
	'action'     => 'index',
));
Route::set('user.admin.role.paginate', 'admin/user/role/paginate')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'role',
	'action'     => 'paginate',
));
Route::set('user.admin.role.retrieve', 'admin/user/role/retrieve')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'role',
	'action'     => 'retrieve',
));
Route::set('user.admin.role.delete', 'admin/user/role/remove')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'role',
	'action'     => 'delete',
));
Route::set('user.admin.role.save', 'admin/user/role/save')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'role',
	'action'     => 'save',
));
Route::set('user.admin.role.index', 'admin/user/role')
	->defaults(array(
	'directory'  => 'Admin/User',
	'controller' => 'role',
	'action'     => 'index',
));

// Add link to manage users in admin
Event::listen('admin.nav_list', function ()
{
	return array(
		'title' => 'User',
		'link'  => URL::site('Admin/User'),
		'icon'  => 'icon-user'
	);
});
