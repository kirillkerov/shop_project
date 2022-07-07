<?php

$main_menu = [
	[
		'name' => 'Главная',
		'path' => '/',
	],
	[
		'name' => 'Новинки',
		'path' => '/?new=on',
	],
	[
		'name' => 'Sale',
		'path' => '/?sale=on',
	],
	[
		'name' => 'Доставка',
		'path' => '/route/delivery/',
	],
];

$admin_menu = [
	[
		'name' => 'Главная',
		'path' => '/',
	],
	[
		'name' => 'Товары',
		'path' => '/route/products/',
	],
	[
		'name' => 'Заказы',
		'path' => '/route/orders/',
	],
	[
		'name' => 'Выйти',
		'path' => '/admin?exit',
	],
];