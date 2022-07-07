<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/include.php';?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; ?>

<main class="page-authorization">
	<h1 class="h h--1">Авторизация</h1>
	<?php if (!$verify): ?>
		<form class="custom-form" method="post" action="">
			<input type="email" value="<?= searchLogin(); ?>" class="custom-form__input" required="" name="admin-email">
			<input type="password" class="custom-form__input" required="" name="password">
			<button class="button" type="submit">Войти в личный кабинет</button>
		</form>
	<?php endif; ?>
	<div class="result"><?= $loginResult; ?></div>
</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';?>