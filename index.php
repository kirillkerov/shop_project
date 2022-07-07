<?php include_once $_SERVER['DOCUMENT_ROOT'].'/templates/header.php';?>


<pre><?php // обработка фильтров

$price = getMinMax();

$category = '';
$sale = '';
$new = '';
$sort_by = 'id';
$order_by = 'DESC';
$minPrice = $price['min'];
$maxPrice = $price['max'];

if (!empty($_GET)) {
	if (!empty($_GET['category'])) $category = $_GET['category'];
	if (!empty($_GET['sale'])) $sale = '1';
	if (!empty($_GET['new'])) $new = '1';
	if (!empty($_GET['sort_by'])) $sort_by = $_GET['sort_by'];
	if (!empty($_GET['order_by'])) $order_by = $_GET['order_by'];
	if (!empty($_GET['minPrice'])) $minPrice = $_GET['minPrice'];
	if (!empty($_GET['maxPrice'])) $maxPrice = $_GET['maxPrice'];
	$products = getProducts($sort_by, $order_by, $category, $sale, $new, 1, $minPrice, $maxPrice);
} else {
	$products = getProducts('id', 'DESC');
}
$productsCount = $products['prodcount'];
$pagesCount = $products['pagecount'];

unset($products['prodcount'], $products['pagecount'], $products['min_price'], $products['max_price']);

?></pre>

<script src="js/ajax.js"></script>

<main class="shop-page">
	<header class="intro">
		<div class="intro__wrapper">
			<h1 class=" intro__title">COATS</h1>
			<p class="intro__info">Collection 2018</p>
		</div>
	</header>
	<section class="shop container">
		<section class="shop__filter filter">
			<form id="categories_filters" method="get">
			<div class="filter__wrapper">
				<b class="filter__title">Категории</b>
				<ul class="filter__list">
					<li>
						<a class="filter__list-item <?php if (empty($_GET['category'])) echo 'active' ?>" href="/">Все</a>
					</li>
					<li>
						<a class="filter__list-item <?php if (!empty($_GET['category']) && $_GET['category'] == '1') echo 'active' ?>" href="?category=1">Женщины</a>
					</li>
					<li>
						<a class="filter__list-item <?php if (!empty($_GET['category']) && $_GET['category'] == '2') echo 'active' ?>" href="?category=2">Мужчины</a>
					</li>
					<li>
						<a class="filter__list-item <?php if (!empty($_GET['category']) && $_GET['category'] == '3') echo 'active' ?>" href="?category=3">Дети</a>
					</li>
					<li>
						<a class="filter__list-item <?php if (!empty($_GET['category']) && $_GET['category'] == '4') echo 'active' ?>" href="?category=4">Аксессуары</a>
					</li>
				</ul>
			</div>
				<div class="filter__wrapper">
					<b class="filter__title">Фильтры</b>
					<div class="filter__range range">
						<span class="range__info">Цена</span>
						<div class="range__line" aria-label="Range Line">
							<input type="text" id="min_price" name="minPrice" value="<?=$minPrice?>" hidden>
							<input type="text" id="max_price" name="maxPrice" value="<?=$maxPrice?>" hidden>
						</div>
						<div class="range__res">

							<input type="text" id="min_price1" value="<?=$price['min']?>" hidden>
							<input type="text" id="max_price1" value="<?=$price['max']?>" hidden>

							<span class="range__res-item min-price"><?=$minPrice?> руб.</span>
							<span class="range__res-item max-price"><?=$maxPrice?> руб.</span>
						</div>
					</div>
				</div>

				<fieldset class="custom-form__group myclass">
					<input type="checkbox" name="new" id="new" class="custom-form__checkbox" <?php if (!empty($_GET['new'])) echo 'checked'; ?>>
					<label for="new" class="custom-form__checkbox-label custom-form__info" style="display: block;">Новинка</label>
					<input type="checkbox" name="sale" id="sale" class="custom-form__checkbox" <?php if (!empty($_GET['sale'])) echo 'checked'; ?>>
					<label for="sale" class="custom-form__checkbox-label custom-form__info" style="display: block;">Распродажа</label>

					<?php if (!empty($_GET['category'])): ?>
						<input type="text" name="category" value="<?= $_GET['category'] ?>" hidden>
					<?php endif ?>

				</fieldset>
				<button class="button" type="submit" style="width: 100%">Применить</button>
			</form>
		</section>

		<div class="shop__wrapper">
			<section class="shop__sorting">
				<div class="shop__sorting-item custom-form__select-wrapper">
					<select class="custom-form__select" id="sort" name="sort_by">
						<option hidden="">Сортировка</option>
						<option value="price">По цене</option>
						<option value="name">По названию</option>
					</select>
				</div>
				<div class="shop__sorting-item custom-form__select-wrapper">
					<select class="custom-form__select" name="order_by" id="order">
						<option hidden="">Порядок</option>
						<option value="ASC">По возрастанию</option>
						<option value="DESC">По убыванию</option>
					</select>
				</div>
				<p class="shop__sorting-res">Найдено <span class="res-sort"><?= $productsCount ?></span> моделей</p>
			</section>
			<section class="shop__list">
				<?php foreach ($products as $k => $product): ?>
					<article id="<?= $product['id'] ?>" class="shop__item product" tabindex="0">
						<div class="product__image">
							<img src="<?= $product['img']; ?>" alt="product-name">
						</div>
						<p class="product__name"><?= $product['name']; ?></p>
						<span class="product__price"><?= $product['price']; ?></span> р.
					</article>
				<?php endforeach; ?>
			</section>
			<ul class="shop__paginator paginator">
				<?php for ($i = 1; $i <= $pagesCount; $i++): ?>
					<li>
						<input type="button" class="paginator__item" value="<?= $i ?>">
					</li>
				<?php endfor; ?>
			</ul>
		</div>
	</section>
	<section class="shop-page__order" hidden>
		<div class="shop-page__wrapper">
			<h2 class="h h--1">Оформление заказа</h2>
			<form id="order_form" class="custom-form js-order">
				<fieldset class="custom-form__group">
					<legend class="custom-form__title">Укажите свои личные данные</legend>
					<p class="custom-form__info">
						<span class="req">*</span> поля обязательные для заполнения
					</p>
					<div class="custom-form__column">
						<label class="custom-form__input-wrapper" for="surname">
							<input id="surname" class="custom-form__input" type="text" name="surname" required="">
							<p class="custom-form__input-label">Фамилия <span class="req">*</span></p>
						</label>
						<label class="custom-form__input-wrapper" for="name">
							<input id="name" class="custom-form__input" type="text" name="name" required="">
							<p class="custom-form__input-label">Имя <span class="req">*</span></p>
						</label>
						<label class="custom-form__input-wrapper" for="thirdName">
							<input id="thirdName" class="custom-form__input" type="text" name="thirdName">
							<p class="custom-form__input-label">Отчество</p>
						</label>
						<label class="custom-form__input-wrapper" for="phone">
							<input id="phone" class="custom-form__input" type="tel" name="phone" required="">
							<p class="custom-form__input-label">Телефон <span class="req">*</span></p>
						</label>
						<label class="custom-form__input-wrapper" for="email">
							<input id="email" class="custom-form__input" type="email" name="email" required="">
							<p class="custom-form__input-label">Почта <span class="req">*</span></p>
						</label>
					</div>
				</fieldset>
				<fieldset class="custom-form__group js-radio">
					<legend class="custom-form__title custom-form__title--radio">Способ доставки</legend>
					<input id="dev-no" class="custom-form__radio" type="radio" name="delivery" value="0" checked="">
					<label for="dev-no" class="custom-form__radio-label">Самовывоз</label>
					<input id="dev-yes" class="custom-form__radio" type="radio" name="delivery" value="1">
					<label for="dev-yes" class="custom-form__radio-label">Курьерная доставка</label>
				</fieldset>
				<div class="shop-page__delivery shop-page__delivery--no">
					<table class="custom-table">
						<caption class="custom-table__title">Пункт самовывоза</caption>
						<tr>
							<td class="custom-table__head">Адрес:</td>
							<td>Москва г, Тверская ул,<br> 4 Метро «Охотный ряд»</td>
						</tr>
						<tr>
							<td class="custom-table__head">Время работы:</td>
							<td>пн-вс 09:00-22:00</td>
						</tr>
						<tr>
							<td class="custom-table__head">Оплата:</td>
							<td>Наличными или банковской картой</td>
						</tr>
						<tr>
							<td class="custom-table__head">Срок доставки: </td>
							<td class="date">13 декабря—15 декабря</td>
						</tr>
					</table>
				</div>
				<div class="shop-page__delivery shop-page__delivery--yes" hidden="">
					<fieldset class="custom-form__group">
						<legend class="custom-form__title">Адрес</legend>
						<p class="custom-form__info">
							<span class="req">*</span> поля обязательные для заполнения
						</p>
						<div class="custom-form__row">
							<label class="custom-form__input-wrapper" for="city">
								<input id="city" class="custom-form__input" type="text" name="city">
								<p class="custom-form__input-label">Город <span class="req">*</span></p>
							</label>
							<label class="custom-form__input-wrapper" for="street">
								<input id="street" class="custom-form__input" type="text" name="street">
								<p class="custom-form__input-label">Улица <span class="req">*</span></p>
							</label>
							<label class="custom-form__input-wrapper" for="home">
								<input id="home" class="custom-form__input custom-form__input--small" type="text" name="home">
								<p class="custom-form__input-label">Дом <span class="req">*</span></p>
							</label>
							<label class="custom-form__input-wrapper" for="aprt">
								<input id="aprt" class="custom-form__input custom-form__input--small" type="text" name="aprt">
								<p class="custom-form__input-label">Квартира <span class="req">*</span></p>
							</label>
						</div>
					</fieldset>
				</div>
				<div class="custom-form__group shop-page__price" style="margin-bottom: 60px;">
					<div class="custom-form__title">Стоимость заказа: <span class="custom-form__summ"></span> р.</div>
					<p>Цена товара: <span class="custom-form__price"></span> р.</p>
					<p>Стоимость доставки: <span class="custom-form__dprice">0.00</span> р.</p>
				</div>
				<fieldset class="custom-form__group shop-page__pay">
					<legend class="custom-form__title custom-form__title--radio">Способ оплаты</legend>
					<input id="cash" class="custom-form__radio" type="radio" name="pay" value="cash">
					<label for="cash" class="custom-form__radio-label">Наличные</label>
					<input id="card" class="custom-form__radio" type="radio" name="pay" value="card" checked="">
					<label for="card" class="custom-form__radio-label">Банковской картой</label>
				</fieldset>
				<fieldset class="custom-form__group shop-page__comment">
					<legend class="custom-form__title custom-form__title--comment">Комментарии к заказу</legend>
					<textarea class="custom-form__textarea" name="comment"></textarea>
				</fieldset>
				<input id="sendProduct_id" type="text" name="product_id" value="" hidden="">
				<button class="button" id="send_order" type="submit">Отправить заказ</button>
			</form>
		</div>
	</section>
	<section class="shop-page__popup-end" hidden>
		<div class="shop-page__wrapper shop-page__wrapper--popup-end">
			<h2 class="h h--1 h--icon shop-page__end-title">Спасибо за заказ!</h2>
			<p class="shop-page__end-message" id="order_result">Ваш заказ успешно оформлен, с вами свяжутся в ближайшее время</p>
			<a href="/" id="proceed" class="button">Продолжить покупки</a>
		</div>
	</section>
</main>

<script src="/js/ajax.js"></script>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';?>