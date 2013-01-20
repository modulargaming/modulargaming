<?php defined('SYSPATH') OR die('No direct script access.');

Event::listen('admin.nav_list', function(){
	return array(
		'title' => 'Items',
		'link'  => URL::site('admin/item'),
		'icon'  => 'icon-shopping-cart'
	);
});

Route::set('item.admin.list.search', 'admin/item/search')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'item',
		'action'     => 'search',
));
Route::set('item.admin.list.retrieve', 'admin/item/retrieve')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'item',
		'action'     => 'retrieve',
));
Route::set('item.admin.list.save', 'admin/item/save')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'item',
		'action'     => 'save',
));
Route::set('item.admin.list.delete', 'admin/item/delete')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'item',
		'action'     => 'delete',
));
Route::set('item.admin.type.search', 'admin/item/types/search')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'item',
		'action'     => 'search',
));
Route::set('item.admin.type.save', 'admin/item/types/save')
	->defaults(array(
		'directory'  => 'admin/item',
		'controller' => 'types',
		'action'     => 'save',
));
Route::set('item.admin.type.retrieve', 'admin/item/types/retrieve')
	->defaults(array(
		'directory'  => 'admin/item',
		'controller' => 'types',
		'action'     => 'retrieve',
));
Route::set('item.admin.type.delete', 'admin/item/types/delete')
	->defaults(array(
		'directory'  => 'admin/item',
		'controller' => 'types',
		'action'     => 'delete',
));
Route::set('item.admin.recipe.search', 'admin/item/recipes/search')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'item',
		'action'     => 'search',
));
Route::set('item.admin.recipe.retrieve', 'admin/item/recipes/retrieve')
	->defaults(array(
		'directory'  => 'admin/item',
		'controller' => 'recipes',
		'action'     => 'retrieve',
));
Route::set('item.admin.recipe.save', 'admin/item/recipes/save')
	->defaults(array(
		'directory'  => 'admin/item',
		'controller' => 'recipes',
		'action'     => 'save',
));
Route::set('item.admin.recipe.delete', 'admin/item/recipes/delete')
	->defaults(array(
		'directory'  => 'admin/item',
		'controller' => 'recipes',
		'action'     => 'delete',
));
Route::set('item.admin.recipe.index', 'admin/item/recipes(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'admin/item',
		'controller' => 'recipes',
		'action'     => 'index',
));
Route::set('item.admin.types.index', 'admin/item/types(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'admin/item',
		'controller' => 'types',
		'action'     => 'index',
));
Route::set('item.admin.list.index', 'admin/item(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'item',
		'action'     => 'index',
));