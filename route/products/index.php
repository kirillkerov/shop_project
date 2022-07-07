<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/include.php';?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';?>

<!-- проверка права доступа -->
<div class="access_false"><?php access(); ?></div>

<!-- получение массива товаров из бд -->
<?php $productsList = getProductsList();?>

<main class="page-products">
  <h1 class="h h--1">Товары</h1>
  <a class="page-products__button button" href="/route/products/add.php">Добавить товар</a>
  <p style="color: green;" id="result">
    
    <!-- Вывод результата при добавлении/изменении товара -->
    <?php
    if (!empty($_SESSION['addProductId'])) {
        $product = getProductById($_SESSION['addProductId']);
        unset($_SESSION['addProductId']);
        echo 'Товар "' . $product['name'] . '" успешно добавлен.';
    } elseif (!empty($_SESSION['updateProductId'])) {
        $product = getProductById($_SESSION['updateProductId']);
        unset($_SESSION['updateProductId']);
        echo 'Товар "' . $product['name'] . '" успешно изменён.';
    }
    ?>

  </p>
  <div class="page-products__header">
    <span class="page-products__header-field">Название товара</span>
    <span class="page-products__header-field">ID</span>
    <span class="page-products__header-field">Цена</span>
    <span class="page-products__header-field">Категория</span>
    <span class="page-products__header-field">Новинка</span>
    <span class="page-products__header-field">Распродажа</span>
  </div>

  <?php foreach ($productsList as $k => $v) :?>
  <ul class="page-products__list">
    <li class="product-item page-products__item">
      <b class="product-item__name"><?= $v['name'];?></b>
      <span class="product-item__field"><?= $v['id'];?></span>
      <span class="product-item__field"><?= $v['price'];?> руб.</span>
      <span class="product-item__field"><?= $v['category'];?></span>
      <span class="product-item__field"><?= $v['new'];?></span>
      <span class="product-item__field"><?= $v['sale'];?></span>
      <!-- Кнопка редактирования товара -->
      <a href="/route/products/add.php?product_id=<?= $v['id'];?>" class="product-item__edit" aria-label="Редактировать"></a>
      <!-- Кнопка удаления товара -->
      <button class="product-item__delete" id="<?=$v['id']?>"></button>
    </li>
  </ul>
  <?php endforeach; ?>

  <p class="id_del"></p>

</main>

<script src="/js/ajax.js"></script>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';?>