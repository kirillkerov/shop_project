<?php

if (!isset($verify)) {
	$verify = false;
}
if (!isset($loginResult)) {
	$loginResult = '';
}

// действия по кнопке "выйти"
if (isset($_GET['exit'])) {
	unset ($_SESSION['user']);
}

if (!empty($_POST['admin-email'])) {

	// защита от sql инъекций
	$login = mysqli_real_escape_string(connect(), $_POST['admin-email']);

	//получение из БД данных пользователя
	$userData = userData($login);

	// проверка пароля
	if (count($userData) > 0) {
		if(password_verify($_POST['password'], $userData['password'])) {
			setcookie("login", $userData['email'], time() + 3600 * 24 * 30, "/");
			$_SESSION['user'] = $userData;
			$verify = true;
			$loginResult = '<p style="color: green;">' . $_SESSION['user']['name'] . ', Вы авторизованы как ' . $_SESSION['user']['role']. '</p>';
		} else {
			$verify = false;
			$loginResult = '<p style="color: red;">' . 'Неверный пароль' . '</p>';
		}
	} else {
		$verify = false;
		$loginResult = '<p style="color: red;">' . 'Неверный логин' . '</p>';
	}
}

// продлить жизнь куки
if (isset($_SESSION['user'], $_COOKIE['login'])) {
	setcookie("login", $_SESSION['user']['email'], time() + 3600 * 24 * 30, "/");
}
