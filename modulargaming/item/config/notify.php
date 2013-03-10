<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'items' => array(
		'gift' => array(
			'title' => 'Gift',
			'message' => ':username sent you :item_name',
			'icon' => 'item_gift'
		),
		'transfer' => '',
		'user_shop' => array(
			'buy' => array(
				'icon' => 'user_shop',
				'message' => ':item_name was bought by :username',
				'title' => 'Shop'
			),
		),
		'trades' => array(
			'bid' => array(
				'icon' => 'trade.bid',
				'message' => ':username made an offer on :lot',
				'title' => 'Someone made a bid on your trade'
			),
			'accept' => array(
				'icon' => 'trade.accept',
				'message' => ':username has accepted your bid, the items should be in your inventory',
				'title' => 'Winning bid'
			),
			'reject' => array(
				'icon' => 'trade.reject',
				'message' => 'your offer for :lot was declined',
				'title' => 'Losing bid'
			),
			'retract' => array(
				'icon' => 'trade.retract',
				'message' => ':username has retracted his bid on :lot',
				'title' => 'Bid retraction'
			),
			'delete' => array(
				'icon' => 'trade.removed',
				'message' => 'Lot :lot got cancelled, you recieve all your items (and points) back',
				'title' => 'Trade cancelled'
			),

		),

	),
);
