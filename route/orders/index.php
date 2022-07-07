<?php include_once $_SERVER['DOCUMENT_ROOT'].'/include/include.php';?>
<?php include_once $_SERVER['DOCUMENT_ROOT'].'/templates/header.php';?>
<!-- проверка права доступа -->
<div class="access_false"><?php access(); ?></div>
<main class="page-order">
  <h1 class="h h--1">Список заказов</h1>
  <ul class="page-order__list">
    
    <?php
    // получить список заказов
    $orders = getOrders();
    ?>

    <?php foreach ($orders as $order): ?>
        <?php

        // доставка
        if ($order['delivery'] == 1) {
            $order['price'] = number_format($order['price'] + 280, 2);
            $order['delivery'] = 'Доставка';
        } else {
            $order['price'] = number_format($order['price'], 2);
            $order['delivery'] = 'Самовывоз';
        }
        // оплата
        switch ($order['pay']) {
            case 'cash':
                $order['pay'] = 'Наличными';
                break;
            case 'card':
                $order['pay'] = 'Картой';
                break;
        }

      // статус
        switch ($order['status']) {
            case '0':
                $order['status'] = 'Не выполнено';
                $statusClass = 'no';
                break;
            case '1':
                $order['status'] = 'Выполнено';
                $statusClass = 'yes';
                break;
        }

      ?>
    <li class="order-item page-order__item">
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--id">
          <span class="order-item__title">Номер заказа</span>
          <span class="order-item__info order-item__info--id"><?= $order['id'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Сумма заказа</span>
          <?= $order['price'] ?> руб.
        </div>
        <button class="order-item__toggle"></button>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--margin">
          <span class="order-item__title">Заказчик</span>
          <span class="order-item__info"><?= $order['name'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Номер телефона</span>
          <span class="order-item__info"><?= $order['phone'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ доставки</span>
          <span class="order-item__info"><?= $order['delivery'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ оплаты</span>
          <span class="order-item__info"><?= $order['pay'] ?></span>
        </div>
        <div class="order-item__group order-item__group--status">
          <span class="order-item__title">Статус заказа</span>
          <span class="order-item__info order-item__info--<?= $statusClass ?>"><?= $order['status'] ?></span>
          <button class="order-item__btn" id="<?= $order['id'] ?>" name="status" value="<?= $statusClass ?>">Изменить</button>
        </div>
      </div>
      <?php if ($order['delivery'] == 'Доставка'): ?>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Адрес доставки</span>
          <span class="order-item__info"><?= $order['adress'] ?></span>
        </div>
      </div>
      <?php endif ?>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Комментарий к заказу</span>
          <span class="order-item__info"><?= $order['comment'] ?></span>
        </div>
      </div>
    </li>
  <?php endforeach ?>
  </ul>
</main>

<script src="/js/ajax.js"></script>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';?>