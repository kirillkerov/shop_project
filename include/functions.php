<?php

//возвращает текущую директорию (без имени файла)
function thisPath(): string
{
	return strstr($_SERVER['PHP_SELF'], basename($_SERVER['PHP_SELF']), true);
}

//вывод меню
function showMenu(array $menu, string $position)
{
	$rn = "\r\n";
	echo '<ul class="main-menu main-menu--' . $position . '">' . $rn;
	foreach ($menu as $item) {
		if ($item['path'] == thisPath()) {
			echo '<li><a class="main-menu__item active" href="' . $item['path'] . '">' . $item['name'] . '</a></li>' . $rn;
		} else {
			echo '<li><a class="main-menu__item" href="' . $item['path'] . '">' . $item['name'] . '</a></li>' . $rn;
		}
	}
	echo '</ul>' . $rn;
}

//получение данных пользователя
function userData(string $login)
{

	// запрос в бд
	// сортировка используется для того, чтобы пользователь состоящий в группах Администратор и Оператор одновременно определялись как Администратор (в этом случае знать что он ещё и Оператор - бесполезно)
	$result = mysqli_query(connect(), "SELECT u.name, password, email, g.name AS role, g.description FROM `users` AS u JOIN `group_user` AS gu ON user_id = u.id jOIN `groups` AS g ON group_id = g.id WHERE email = '" . $login . "' ORDER BY role ASC LIMIT 1");

	// результата запроса
	return mysqli_fetch_assoc($result);
}

//поиск логина в $_POST или в $_COOKIE
function searchLogin() : string
{
	if (isset($_POST['admin-email'])) {
		return $_POST['admin-email'];
	} elseif (isset($_COOKIE['login'])) {
		return $_COOKIE['login'];
	} else {
		return '';
	}
}

// проверка доступа к разделам
function access()
{
	if (isset($_SESSION['user'])) {
		if ($_SESSION['user']['role'] == 'Администратор') {
			return '';
		} elseif ($_SESSION['user']['role'] == 'Оператор') {
			if (thisPath() == '/route/orders/') {
				return '';
			} elseif (thisPath() == '/route/products/') {
				exit('Раздел доступен только Администраторам');
			}
		} else {
			exit('Раздел доступен только Администрации');
		}
	} else {
		exit('Раздел доступен только Администрации');
	}
}

// получить список товаров для магазина
function getProducts($sortBy = 'id', $order = 'DESC', $category = '', $sale = '', $new = '', $page = 1, $minPrice = 0, $maxPrice = 999999): array
{
	// строка усорвия выборки WHERE
	$where = '';
	$join = '';
	$whereArr = [''];
	if (!empty($category) || !empty($sale) || !empty($new)) {
		if (!empty($category)) {
			$join = 'LEFT JOIN category_product AS cp ON p.id = cp.product_id';
			$whereArr[] = '`category_id` = ' . $category;
		}
		if (!empty($sale)) {
			$whereArr[] = '`sale` = ' . $sale;
		}
		if (!empty($new)) {
			$whereArr[] = '`new` = ' . $new;
		}
	}
	$where = "WHERE `price` between ".$minPrice." and ".$maxPrice . implode(' and ', $whereArr);
	$where = mysqli_real_escape_string(connect(), $where);
	$sortBy = mysqli_real_escape_string(connect(), $sortBy);
	$order = mysqli_real_escape_string(connect(), $order);

	// вычисляем с какого элемента выводить
	$pageSize=6; // элементов на странице
	$offset = ($page - 1) * $pageSize;

	// вычесляем количество элементов в базе
	$result = mysqli_query(connect(), "
		SELECT COUNT(*) as count FROM products AS p
			".$join."
		".$where."
		ORDER BY " . $sortBy . " " . $order . "
	;");
	while ($row = mysqli_fetch_assoc($result)) {
		$count = $row['count'];
	}

	// заброс на выборку товаров из бд
	$result = mysqli_query(connect(), "
		SELECT * FROM products AS p
			".$join . "
		" . $where . "
		ORDER BY " . $sortBy . " " . $order . " LIMIT " . $offset . ", " . $pageSize . "
	;");

	// результат запроса
	$products = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$products[] = $row;
	}

	// не знаю куда засунуть эти данные - не забыть удалить, где не нужны
	$products['prodcount'] = (int) $count;
	$products['pagecount'] = ceil((int) $count / $pageSize);
	return $products;
}

// получить минимальную и максимальную цены
function getMinMax()
{

	$result = mysqli_query(connect(), "
		SELECT MIN(price) as min_price, MAX(price) as max_price FROM products;");
	
	while ($row = mysqli_fetch_assoc($result)) {
		$price['min'] = $row['min_price'];
		$price['max'] = $row['max_price'];
	}

	return $price;

}

// получить список товаров для админки
function getProductsList($sortBy = 'id', $order = 'DESC'): array
{
	$result = mysqli_query(connect(), "SELECT g.id, g.name, price, img, CASE sale WHEN '0' THEN 'нет' WHEN '1' THEN 'да' END sale, CASE g.new WHEN '0' THEN 'нет' WHEN '1' THEN 'да' END new, group_concat(c.name separator ', ') AS category FROM products AS g LEFT JOIN category_product AS cg ON g.id = cg.product_id LEFT JOIN categories AS c ON cg.category_id = c.id group by id ORDER BY " . $sortBy . " " . $order . ";");

	// результат запроса
	$productsList = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$productsList[] = $row;
	}
	return $productsList;
}

// получить данные о товаре по ID
function getProductById($id): array
{
	$result = mysqli_query(connect(), "SELECT p.id, p.`name`, price, img, sale, `new`, group_concat(c.`id`) AS category FROM products AS p LEFT JOIN category_product AS cp ON p.id = product_id LEFT JOIN categories AS c ON c.id = category_id WHERE p.id = " . $id . ";");
	return mysqli_fetch_assoc($result);
}

// ЗАГРУЗКА ФОТО ТОВАРА
function loadProductImg()
{
	$result = [];
	if (!empty($_FILES)) {
		if (!empty($_FILES['product-photo']['name'])) {

			// создание уникального имени загружаемого файла
			$pathInfo = pathinfo($_FILES['product-photo']['name']);
			$fileExtension = $pathInfo['extension'];			
			$uploadFile = UPLOAD_DIR . uniqid() . ".$fileExtension";
			
			// проверка типа загружаемого файла
			$userFileType = mime_content_type($_FILES['product-photo']['tmp_name']);
			if (in_array($userFileType, TRUE_FILE_TYPE)) {

				// проверка и перемещение в каталог
				if (move_uploaded_file($_FILES['product-photo']['tmp_name'], $uploadFile)) {
					
					$result['file'] = $uploadFile;
					// возырвщвем полное имя картинки
					return $result;
				}

			} else {
				$result['error'] = 'Неверный тип загружаемого файла: "' . $userFileType . '". Поддерживаемые типы: ' . implode(TRUE_FILE_TYPE, ', ');
			}

		} else {
			$result['error'] = 'Файл не выбран';
		}
	}
	return $result;
}

// получить все категории из БД
function getAllCategories()
{

	$result = mysqli_query(connect(), "SELECT * FROM categories;");

	// результат запроса
	$categoryList = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$categoryList[] = $row;
	}
	return $categoryList;
}

// подготовка данных о товаре для загрузки в БД
function readyProduct($img = 0): array
{
	$product = [];
	if (!empty($_POST['product-name'])) {
		$product['name'] = mysqli_real_escape_string(connect(), $_POST['product-name']);
	}
	if (!empty($_POST['product-price'])) {
		$product['price'] = mysqli_real_escape_string(connect(), $_POST['product-price']);
	}
	if (!empty($img)) {
		$product['img'] = mysqli_real_escape_string(connect(), '/img/products/' . basename($img));
	}
	if (!empty($_POST['new'])) {
		$product['new'] = '1';
	} else {
		$product['new'] = '0';
	}
	if (!empty($_POST['sale'])) {
		$product['sale'] = '1';
	} else {
		$product['sale'] = '0';
	}

	return $product;
}

// загрузка товара в БД
function insertProduct($product)
{
	foreach ($product as $k => $v) {
		$insertColumns[] = '`'.$k.'`';
		$insertValues[] = "'".$v."'";
	}
	$insertColumns = implode(', ', $insertColumns);
	$insertValues = implode(', ', $insertValues);

	// добавление товара в таблицу 'products'
	$result = mysqli_query(connect(), 
		"INSERT INTO `products` (" . $insertColumns . ") VALUES (" . $insertValues . ")"
	);

	return mysqli_insert_id(connect());
}

// изменение существующего в БД товара
function updateProduct($productId, $readyProduct)
{
	
	// если новое фото - удалить старое
	if (!empty($readyProduct['img'])) {
		$delete_id = mysqli_real_escape_string(connect(), $productId);

		// получить путь к фото товара
		$result = mysqli_query(connect(), "SELECT img FROM `products` WHERE (`id` = '".$delete_id."')");
		$row = mysqli_fetch_assoc($result);
		
		// удалить фото продукта
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . $row['img'])) {
			unlink($_SERVER['DOCUMENT_ROOT'] . $row['img']);
		}
	}

	// подготовка данных для update
	foreach ($readyProduct as $k => $v) {
		$set[] = "`" . $k . "`" . " = '" . $v . "'";
	}
	$set = implode(', ', $set);

	$result = mysqli_query(connect(), 
		"UPDATE `products` SET " . $set . " WHERE `id` = " . $productId . ";"
	);
}

// добаить категории товару
function insertCategory($productId)
{

	foreach ($_POST['category'] as $v) {
		$insertValues[] = "(" . mysqli_real_escape_string(connect(), $v) . ", " . mysqli_real_escape_string(connect(), $productId). ")";
	}
	
	$insertValues = implode(', ', $insertValues);

	$result = mysqli_query(connect(), "INSERT INTO `category_product` (`category_id`, `product_id`) VALUES $insertValues");
}

// получить список заказов
function getOrders()
{

	$result = mysqli_query(connect(), "
		SELECT o.id, p.price, concat(o.surname, ' ', o.name, ' ', o.thirdName) AS name, o.phone, o.delivery, o.pay, o.status, concat(o.city, ', ул. ', o.street, ', д. ', o.home, ', кв. ', o.aprt) AS adress, o.comment FROM orders AS o
				LEFT JOIN products AS p ON p.id = product_id 
			order by status, id DESC;
	");

	// результат запроса
	$orders = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$orders[] = $row;
	}
	return $orders;

}
