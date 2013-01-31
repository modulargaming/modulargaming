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
Route::set('item.inventory.view', 'inventory/view/<id>', array('id' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Inventory',
		'action'     => 'View',
));
Route::set('item.inventory.consume', 'inventory/consume/<id>', array('id' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Inventory',
		'action'     => 'Consume',
));
Route::set('item.inventory.search', 'inventory/search')
	->defaults(array(
		'controller' => 'Search',
		'action'     => 'Index',
));
Route::set('item.inventory', 'inventory(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Inventory',
		'action'     => 'Index',
));
Route::set('item.cookbook.view', 'cookbook/view/<id>', array('id' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Cookbook',
		'action'     => 'View',
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
Route::set('item.user_shop.create', 'shop/create')
	->defaults(array(
	'controller' => 'Shop',
	'action'     => 'create',
));
Route::set('item.user_shop.upgrade', 'shop/upgrade')
	->defaults(array(
	'controller' => 'Shop',
	'action'     => 'upgrade',
));
Route::set('item.user_shop.update', 'shop/update')
	->defaults(array(
		'controller' => 'Shop',
		'action'     => 'update',
));
Route::set('item.user_shop.stock', 'shop/stock(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Shop',
		'action'     => 'stock',
	));
Route::set('item.user_shop.inventory', 'shop/inventory')
	->defaults(array(
		'controller' => 'Shop',
		'action'     => 'inventory',
));
Route::set('item.user_shop.logs', 'shop/logs')
	->defaults(array(
		'controller' => 'Shop',
		'action'     => 'logs',
));
Route::set('item.user_shop.collect', 'shop/collect')
	->defaults(array(
		'controller' => 'Shop',
		'action'     => 'collect',
));
Route::set('item.user_shop.buy', 'shop/<id>/buy', array('id' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Shop',
		'action'     => 'buy',
));
Route::set('item.user_shop.view', 'shop/<id>', array('id' => '[0-9]+'))
	->defaults(array(
		'controller' => 'Shop',
		'action'     => 'view',
));
Route::set('item.user_shop.index', 'shop')
	->defaults(array(
		'controller' => 'Shop',
		'action'     => 'index',
));
Route::set('item.safe.move', 'safe/move')
	->defaults(array(
		'controller' => 'Safe',
		'action'     => 'move',
));
Route::set('item.safe', 'safe')
	->defaults(array(
		'controller' => 'Safe',
		'action'     => 'index',
));