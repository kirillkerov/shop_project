<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/include/include.php';

// проверка заполнения обязательных полей
$errors = [];
if (empty($_POST['name']) || empty($_POST['surname'])) {
	$errors[] = 'Заполните имя и фамилию';
}
if (empty($_POST['phone']) || empty($_POST['email'])) {
	$errors[] = 'Заполните телефон и e-mail';
}
if ($_POST['delivery'] == '1') {
	if (empty($_POST['city']) || empty($_POST['street']) || empty($_POST['home']) || empty($_POST['aprt']) ) {
		$errors[] = 'Заполните все поля для доставки в разделе "Адрес"';
	}
}

// если обязательные поля заполнены
if (count($errors) == 0) {
	
	// защита от sql инъекций
	foreach ($_POST as $k => $v) {
		$_POST[$k] = mysqli_real_escape_string(connect(), $v);
		$columnsArr[] = '`' . $k . '`';
		$valuesArr[] = "'" . $_POST[$k] . "'";
	}

	$columns = implode(', ', $columnsArr);
	$values = implode(', ', $valuesArr);

	// добавить данные заказа в БД
	mysqli_query(connect(), "INSERT INTO `orders` (" . $columns . ") VALUES (" . $values . ");");

	// echo json_encode($_POST);

} else { // если не все обязательные поля заполнены
	// echo json_encode($errors);
}