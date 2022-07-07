<?php

// сборщик ощибок
$sendError = [];

// ЗАГРУЗКА ТОВАРА
if (!empty($_POST) && empty($_GET['product_id'])) {
	
	// если заполнены обязательные поля (название, цена, раздел)
	if (!empty($_POST['product-name']) && !empty($_POST['product-price'])) {

		$loadFileResult = loadProductImg();

		// если файл успешно отправлен
		if (isset($loadFileResult['file'])) {

			// получение данных для загрузки в бд
			$readyProduct = readyProduct($loadFileResult['file']);

			// добаить товар и полученить его id
			$productId = insertProduct($readyProduct);

			// добавить категории товара в БД
			if (!empty($_POST['category'])) {
				insertCategory($productId);
			}

			if (!empty($productId)) {
				// header('Location:/route/products?product-name='.$_POST['product-name'].'&action=add');
				header('Location:/route/products/');
				$_SESSION['addProductId'] = $productId;
			}
		} else {
			$sendError[] = $loadFileResult['error'];
		}
	} else {
		$sendError[] = 'Заполните обязательные поля: Название товара, Цена товара';
	}

// ЕСЛИ НАЖАТА КНОПКА "ИЗМЕНИТЬ ТОВАР"
} elseif (!empty($_GET['product_id'])) {

	// создать массив данных изменяемого товара
	$existProduct = getProductById($_GET['product_id']);
	$existProduct['category'] = explode(',', $existProduct['category']);

	// если выбрано новое фото
	if (!empty($_FILES['product-photo']['name'])) {
		
		$loadFileResult = loadProductImg();

		if (is_string($loadFileResult['file'])) {

			// получение данных для загрузки в бд
			$readyProduct = readyProduct($loadFileResult['file']);

			updateProduct($_GET['product_id'], $readyProduct);
			
			header('Location:/route/products/');
			$_SESSION['updateProductId'] = $_GET['product_id'];

		} else {
			$sendError[] = $loadFileResult['error'];
		}

	// если не выбрано новое фото
	} elseif (!empty($_POST['name']) || !empty($_POST['price']) || !empty($_POST['new']) || !empty($_POST['sale']) || !empty($_POST['category'])) {
		
		// получение данных для загрузки в бд
		$readyProduct = readyProduct();

		$result = updateProduct($_GET['product_id'], $readyProduct);

		if (!empty($_POST['category'])) {
			
			// удалить старые категории
			mysqli_query(connect(), "DELETE FROM `category_product` WHERE `product_id` = ".$_GET['product_id']."");

			// добавить новые категории
			insertCategory($_GET['product_id']);
		}

		header('Location:/route/products/');
		$_SESSION['updateProductId'] = $_GET['product_id'];
	}
}