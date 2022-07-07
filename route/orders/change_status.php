<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/include/include.php';

foreach ($_POST as $id => $status) {
	
	// меняем статус на противоположный
	if ($status == 'yes') {
		$newStatus = '0';
	} elseif ($status == 'no') {
		$newStatus = '1';
	}

	// установить новый статус заказу в БД
	mysqli_query(connect(), "UPDATE `orders` SET `status` = '" . $newStatus . "' WHERE (`id` = '" . $id . "');");
}
