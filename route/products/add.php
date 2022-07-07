<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/include.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/route/products/send.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; ?>

<!-- проверка права доступа -->
<div class="access_false"><?php access(); ?></div>

<main class="page-add">
  <h1 class="h h--1"><?php if (!empty($existProduct)) {echo 'Изменение';} else {echo 'Добавление';}?> товара</h1>
  
  <!-- вывод ошибок отправки товара -->
  <ul>
    <?php foreach($sendError as $v): ?>
    <li style="color: red;"><?= $v ?></li>
    <?php endforeach; ?>
  </ul>

  <form action="" class="custom-form" method="post" enctype="multipart/form-data">
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Данные о товаре</legend>
      <label for="product-name" class="custom-form__input-wrapper page-add__first-wrapper">
        <input type="text" class="custom-form__input" placeholder="Название товара" name="product-name" id="product-name" value="<?php if(!empty($existProduct)) echo $existProduct['name']; ?>">
      </label>

      <label for="product-price" class="custom-form__input-wrapper">
        <input type="number" step="0.01" min="0" class="custom-form__input" placeholder="Цена товара" name="product-price" id="product-price" value="<?php if(!empty($existProduct)) echo $existProduct['price']; ?>">
      </label>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Фотография товара</legend>
      
      <!-- вывод фото, если выбрано "изменить товар" -->
      <?php if (!empty($existProduct)): ?>
        <p>Существующее фото</p>
        <img id="asd" style="width: 300px;" src="<?= $existProduct['img']; ?>">
        <p>Выбрать новое фото</p>
      <?php endif; ?>

      <ul class="add-list">
        <li class="add-list__item add-list__item--add">
          <input type="file" name="product-photo" id="product-photo" accept="image/jpeg, image/png" hidden>
          <label for="product-photo">Добавить фотографию</label>
        </li>
      </ul>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Раздел</legend>
      <div class="page-add__select">
        <select name="category[]" class="custom-form__select" multiple="multiple">
          <option hidden="">Название раздела</option>
          <?php foreach (getAllCategories() as $category): ?>
             <option <?php if (!empty($existProduct)) if (in_array($category['id'], $existProduct['category'])) echo 'selected' ?> value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <input <?php if (!empty($existProduct)) if ($existProduct['new']) echo 'checked'?> type="checkbox" name="new" id="new" class="custom-form__checkbox">
      <label for="new" class="custom-form__checkbox-label">Новинка</label>
      <input <?php if (!empty($existProduct)) if ($existProduct['sale']) echo 'checked'?> type="checkbox" name="sale" id="sale" class="custom-form__checkbox">
      <label for="sale" class="custom-form__checkbox-label">Распродажа</label>
    </fieldset>
    <button class="button" type="submit"><?php if (!empty($existProduct)) {echo 'Обновить';} else {echo 'Добавить';}?> товар</button>
  </form>
  <section class="shop-page__popup-end page-add__popup-end" hidden="">
    <div class="shop-page__wrapper shop-page__wrapper--popup-end">
      <h2 class="h h--1 h--icon shop-page__end-title">Товар успешно добавлен</h2>
    </div>
  </section>
</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';?>
