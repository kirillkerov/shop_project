<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/include/include.php';

if (!empty($_POST['delete_id'])) {
	$delete_id = mysqli_real_escape_string(connect(), $_POST['delete_id']);

	// получить путь к фото товара
	$result = mysqli_query(connect(), "SELECT img FROM `products` WHERE (`id` = '".$delete_id."')");
	$row = mysqli_fetch_assoc($result);
	
	// удалить фото продукта
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$row['img'])) {
		unlink($_SERVER['DOCUMENT_ROOT'].$row['img']);
	}

	// удаление товара из products
	$result = mysqli_query(connect(), "SELECT id, name FROM `products` WHERE `id` = '".$delete_id."'");
	mysqli_query(connect(), "DELETE FROM `products` WHERE (`id` = '".$delete_id."')");
	$row = mysqli_fetch_assoc($result);
	echo 'Товар "' . $row['name'] . '", id = ' . $row['id'] . " удалён!";
}
