<?php defined('SYSPATH') OR die('No direct script access.');

Event::listen('admin.nav_list', function ()
{
	return array(
		'title' => 'Item',
		'link'  => URL::site('admin/item'),
		'icon'  => 'icon-shopping-cart'
	);
});
Route::set('item.admin.shop.index', 'admin/item/shops')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Shops',
	'action'     => 'index',
));
Route::set('item.admin.shop.retrieve', 'admin/item/shops/retrieve')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Shops',
	'action'     => 'retrieve',
));
Route::set('item.admin.shop.paginate', 'admin/item/shops/paginate')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Shops',
	'action'     => 'paginate',
));
Route::set('item.admin.shop.save', 'admin/item/shops/save')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Shops',
	'action'     => 'save',
));
Route::set('item.admin.shop.delete', 'admin/item/shops/remove')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Shops',
	'action'     => 'delete',
));
Route::set('item.admin.shop.stock', 'admin/item/shops/stock')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Shops',
	'action'     => 'stock',
));
Route::set('item.admin.shop.stock.load', 'admin/item/shops/stock/load')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Shops',
	'action'     => 'stock_item',
));
Route::set('item.admin.shop.stock.save', 'admin/item/shops/stock/save')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Shops',
	'action'     => 'stock_save',
));
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
Route::set('item.admin.list.paginate', 'admin/item/paginate')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Item',
	'action'     => 'paginate',
));
Route::set('item.admin.list.save', 'admin/item/save')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Item',
	'action'     => 'save',
));
Route::set('item.admin.list.delete', 'admin/item/remove')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Item',
	'action'     => 'delete',
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
Route::set('item.admin.type.delete', 'admin/item/types/remove')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Types',
	'action'     => 'delete',
));
Route::set('item.admin.type.paginate', 'admin/item/types/paginate')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Types',
	'action'     => 'paginate',
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
Route::set('item.admin.recipe.remove', 'admin/item/recipes/remove')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Recipes',
	'action'     => 'delete',
));
Route::set('item.admin.recipe.paginate', 'admin/item/recipes/paginate')
	->defaults(array(
	'directory'  => 'Admin/Item',
	'controller' => 'Recipes',
	'action'     => 'paginate',
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
	'directory'  => 'Item',
	'controller' => 'Inventory',
	'action'     => 'view',
));
Route::set('item.inventory.consume', 'inventory/consume/<id>', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Inventory',
	'action'     => 'consume',
));
Route::set('item.inventory.search', 'inventory/search')
	->defaults(array(
//	'directory'  => 'Item',
	'controller' => 'Search',
	'action'     => 'index',
));
Route::set('item.inventory', 'inventory(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Inventory',
	'action'     => 'index',
));
Route::set('item.cookbook.view', 'cookbook/view/<id>', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Cookbook',
	'action'     => 'view',
));
Route::set('item.cookbook.complete', 'cookbook/complete/<id>', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Cookbook',
	'action'     => 'complete',
));
Route::set('item.cookbook', 'cookbook')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Cookbook',
	'action'     => 'index',
));
Route::set('item.user_shop.create', 'shop/create')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shop',
	'action'     => 'create',
));
Route::set('item.user_shop.upgrade', 'shop/upgrade')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shop',
	'action'     => 'upgrade',
));
Route::set('item.user_shop.update', 'shop/update')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shop',
	'action'     => 'update',
));
Route::set('item.user_shop.stock', 'shop/stock(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shop',
	'action'     => 'stock',
));
Route::set('item.user_shop.inventory', 'shop/inventory')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shop',
	'action'     => 'inventory',
));
Route::set('item.user_shop.logs', 'shop/logs')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shop',
	'action'     => 'logs',
));
Route::set('item.user_shop.collect', 'shop/collect')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shop',
	'action'     => 'collect',
));
Route::set('item.user_shop.buy', 'shop/<id>/buy', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shop',
	'action'     => 'buy',
));
Route::set('item.user_shop.view', 'shop/<id>', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shop',
	'action'     => 'view',
));
Route::set('item.user_shop.index', 'shop')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shop',
	'action'     => 'index',
));
Route::set('item.safe.move', 'safe/move')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Safe',
	'action'     => 'move',
));
Route::set('item.safe', 'safe')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Safe',
	'action'     => 'index',
));
Route::set('item.trade.index', 'trade')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'index',
));
Route::set('item.trade.create', 'trade/create')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'create',
));
Route::set('item.trade.create.process', 'trade/create/process')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'process_create',
));
Route::set('item.trade.lots', 'trade/lots')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'lots',
));
Route::set('item.trade.lot', 'trade/lot/<id>', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'lot',
));
Route::set('item.trade.delete', 'trade/lot/<id>/delete', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'delete',
));
Route::set('item.trade.bid', 'trade/lot/<id>/bid', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'bid',
));
Route::set('item.trade.bid.process', 'trade/lot/<id>/process', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'process_bid',
));
Route::set('item.trade.bids', 'trade/bids')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'bids',
));
Route::set('item.trade.bids.accept', 'trade/bid/<id>/accept', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'accept',
));
Route::set('item.trade.bids.reject', 'trade/bid/<id>/reject', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'reject',
));
Route::set('item.trade.bids.retract', 'trade/bid/<id>/retract', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'retract',
));
Route::set('item.trade.search', 'trade/search(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'search',
	'page'       => 1
));
Route::set('item.trade.index', 'trade(/<page>)', array('page' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Trade',
	'action'     => 'index',
	'page'       => 1
));
Route::set('item.shops.index', 'shops')
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shops',
	'action'     => 'index',
));
Route::set('item.shops.view', 'shops/<id>', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shops',
	'action'     => 'view',
));
Route::set('item.shops.buy', 'shops/<id>/buy', array('id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'Item',
	'controller' => 'Shops',
	'action'     => 'buy',
));
