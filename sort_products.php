<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/include/include.php';

$sort_by = 'id';
$order_by = 'DESC';
$category = '';
$sale = '';
$new = '';
$page = 1;

if (!empty($_GET['sort_by'])) {
	$sort_by = $_GET['sort_by'];
}
if (!empty($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
}
if (!empty($_GET['category'])) {
	$category = $_GET['category'];
}
if (!empty($_GET['page'])) {
	$page = $_GET['page'];
}
if (!empty($_GET['sale'])) {
	$sale = 1;
}
if (!empty($_GET['new'])) {
	$new = 1;
}
if (!empty($_GET['minPrice']) && !empty($_GET['maxPrice'])) {
	$minPrice = $_GET['minPrice'];
	$maxPrice = $_GET['maxPrice'];
	$products = getProducts($sort_by, $order_by, $category, $sale, $new, $page, $minPrice, $maxPrice);
} else {
	$products = getProducts($sort_by, $order_by, $category, $sale, $new, $page);
}

unset($products['prodcount'], $products['pagecount'], $products['min_price'], $products['max_price']);

?>



<?php foreach ($products as $k => $product): ?>
<article id="<?= $product['id'] ?>" class="shop__item product" tabindex="0">
	<div class="product__image">
		<img src="<?= $product['img']; ?>" alt="product-name">
	</div>
	<p class="product__name"><?= $product['name']; ?></p>
	<span class="product__price"><?= $product['price']; ?></span> р.
</article>
<?php endforeach; ?>
