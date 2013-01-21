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
		'action'     => 'Search',
));
Route::set('item.admin.list.retrieve', 'admin/item/retrieve')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'Retrieve',
));
Route::set('item.admin.list.save', 'admin/item/save')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'Save',
));
Route::set('item.admin.list.delete', 'admin/item/delete')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'Delete',
));
Route::set('item.admin.type.search', 'admin/item/types/search')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'Search',
));
Route::set('item.admin.type.save', 'admin/item/types/save')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Types',
		'action'     => 'Save',
));
Route::set('item.admin.type.retrieve', 'admin/item/types/retrieve')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Types',
		'action'     => 'Retrieve',
));
Route::set('item.admin.type.delete', 'admin/item/types/delete')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Types',
		'action'     => 'Delete',
));
Route::set('item.admin.recipe.search', 'admin/item/recipes/search')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'Search',
));
Route::set('item.admin.recipe.retrieve', 'admin/item/recipes/retrieve')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Recipes',
		'action'     => 'Retrieve',
));
Route::set('item.admin.recipe.save', 'admin/item/recipes/save')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Recipes',
		'action'     => 'Save',
));
Route::set('item.admin.recipe.delete', 'admin/item/recipes/delete')
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Recipes',
		'action'     => 'Delete',
));
Route::set('item.admin.recipe.index', 'admin/item/recipes(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Recipes',
		'action'     => 'Index',
));
Route::set('item.admin.types.index', 'admin/item/types(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Admin/Item',
		'controller' => 'Types',
		'action'     => 'Index',
));
Route::set('item.admin.list.index', 'admin/item(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Item',
		'action'     => 'Index',
));
