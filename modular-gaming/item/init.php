<?php defined('SYSPATH') OR die('No direct script access.');

Event::listen('admin.nav_list', function(){
	return array(
		'title' => 'Item',
		'link'  => URL::site('admin/item'),
		'icon'  => 'icon-shopping-cart'
	);
});

Route::set('item.admin.list.search', 'admin/item/search')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'search',
));
Route::set('item.admin.list.retrieve', 'admin/item/retrieve')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'retrieve',
));
Route::set('item.admin.list.save', 'admin/item/save')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'save',
));
Route::set('item.admin.list.delete', 'admin/item/delete')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'delete',
));
Route::set('item.admin.type.search', 'admin/item/types/search')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'search',
));
Route::set('item.admin.type.save', 'admin/item/types/save')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Types',
		'action'     => 'save',
));
Route::set('item.admin.type.retrieve', 'admin/item/types/retrieve')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Types',
		'action'     => 'retrieve',
));
Route::set('item.admin.type.delete', 'admin/item/types/delete')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Types',
		'action'     => 'delete',
));
Route::set('item.admin.recipe.search', 'admin/item/recipes/search')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'search',
));
Route::set('item.admin.recipe.retrieve', 'admin/item/recipes/retrieve')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Recipes',
		'action'     => 'retrieve',
));
Route::set('item.admin.recipe.save', 'admin/item/recipes/save')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Recipes',
		'action'     => 'save',
));
Route::set('item.admin.recipe.delete', 'admin/item/recipes/delete')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Recipes',
		'action'     => 'delete',
));
Route::set('item.admin.recipe.index', 'admin/item/recipes(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Recipes',
		'action'     => 'index',
));
Route::set('item.admin.types.index', 'admin/item/types(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Types',
		'action'     => 'index',
));
Route::set('item.admin.list.index', 'admin/item(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'index',
));
Route::set('item.inventory.view', 'inventory/view/<id>', array('id' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Inventory',
		'action'     => 'view',
));
Route::set('item.inventory.consume', 'inventory/consume/<id>', array('id' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Inventory',
		'action'     => 'consume',
));
Route::set('item.inventory.search', 'inventory/search')
	->defaults(array(
		'controller' => 'Search',
		'action'     => 'index',
));
Route::set('item.inventory', 'inventory(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Inventory',
		'action'     => 'index',
));
Route::set('item.cookbook.view', 'cookbook/view/<id>', array('id' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Cookbook',
		'action'     => 'view',
));
Route::set('item.cookbook.complete', 'cookbook/complete/<id>', array('id' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Cookbook',
		'action'     => 'complete',
));
Route::set('item.cookbook', 'cookbook')
	->defaults(array(
		'controller' => 'Cookbook',
		'action'     => 'index',
));
