<?php

require __DIR__ . '/header.php'; 
require __DIR__ . '/db.php';
require __DIR__ . '/../csrf.php';
require __DIR__ . '/invoice.php';
require __DIR__ . '/admin/sendEmail.php';


if (isset($_POST['checkout']) && CSRF::validateToken($_POST['token'])) {
  if (!isset($_SESSION['name'])) {
      header('Location: /login');
      exit;
  } else {
      $details = serialize($_SESSION['cart']);
      $timestamp = gmdate('Y-m-d H:i:s');

      $statement = $pdo->prepare("INSERT INTO transactions (name, email, address, details, timestamp) VALUES (?, ?, ?, ?, ?)");
      $statement->execute([
          $_SESSION['name'],
          $_SESSION['email'],
          $_SESSION['address'],
          $details,
          $timestamp
      ]);

      $invoiceHtml = generateInvoice($timestamp);
      sendEmailTest([$_SESSION['email']], "Invoice", $invoiceHtml);

      header('Location: /confirmation');
      exit;
  }
}

?>
<?php if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0): ?>
<section class="empty-cart page-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="block text-center">
        	<i class="tf-ion-ios-cart-outline"></i>
          	<h2 class="text-center">Your cart is currently empty.</h2>
          	<a href="/products" class="btn btn-main mt-20">Return to shop</a>
      </div>
    </div>
  </div>
</section>
<?php else: ?>
<div class="page-wrapper">
  <div class="cart shopping">
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="block">
            <div class="product-list">
              <form method="post">
                <?php CSRF::csrfInputField() ?>
                <table class="table">
                  <thead>
                    <tr>
                      <th class="">Item Name</th>
                      <th class="">Item Price</th>
                      <th class="">Số lượng</th>
                      <th class="">Actions</th>
                      <th class="">Sub Total</th>
                    </tr>
                  </thead>
                  <tbody>

                      <?php foreach($_SESSION['cart'] as $item): ?>
                        <tr class="">
                          <td class="">
                            <div class="product-info">
                              <img width="80" src="<?= htmlspecialchars($item['image']) ?>" alt="" />
                              <a href="#!"><?= htmlspecialchars($item['title']) ?></a>
                            </div>
                          </td>
                          <td class="">VND<?= number_format($item['price'], 2) ?></td>
                          <td class="">   <?= htmlspecialchars($item['Số lượng']) ?></td>
                          <td class="">
                            <a href="/cart-remove-item?id=<?= $item['id'] ?>" class="product-remove">Remove</a>
                          </td>
                          <td class="">VND<?= number_format($item['price'] * htmlspecialchars($item['Số lượng']), 2) ?></td>
                        </tr>
                      <?php endforeach; ?>

                    <tr class="">
                      <td class="">
                        <div class="product-info">
                          <a href="#!">Total</a>
                        </div>
                      </td>
                      <td class=""></td>
                      <td class=""></td>
                      <td class=""></td>
                      <td class="">VND<?php
                          if(!isset($_SESSION['cart'])) {
                            echo '0.00';
                          } else {
                            $total = 0;
                            foreach($_SESSION['cart'] as $item) {
                              $total += $item['price'] * $item['Số lượng'];
                            }
                            echo number_format($total, 2);
                          }
                        ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <form action="/cart" method="post">
                  <?php CSRF::csrfInputField() ?>
                  <button name="checkout" type="submit" class="btn btn-main pull-right">Checkout</button>
                </form>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif ?>
<?php require __DIR__ . '/footer.php'; ?>