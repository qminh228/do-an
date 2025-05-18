<?php 
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Gia Dụng Việt - Siêu thị đồ gia dụng</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Siêu thị đồ gia dụng hiện đại">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="author" content="Gia Dụng Việt">
    
    <link rel="shortcut icon" type="image/x-icon" href="views/images/favicon.png" />
    <link rel="stylesheet" href="views/plugins/themefisher-font/style.css">
    <link rel="stylesheet" href="views/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="views/plugins/animate/animate.css">
    <link rel="stylesheet" href="views/plugins/slick/slick.css">
    <link rel="stylesheet" href="views/plugins/slick/slick-theme.css">
    <link rel="stylesheet" href="views/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #f7f7f7;
            color: #1a1a1a;
        }

        /* Top Header */
        .top-header {
            background: rgba(26, 26, 26, 0.9); /* Semi-transparent black */
            color: #fff;
            padding: 10px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .contact-number {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .contact-number i {
            margin-right: 8px;
            color: #fff;
        }

        .contact-number span {
            color: #fff;
        }

        .logo h2 {
            margin: 0;
            font-weight: 700;
            color: #fff;
            font-size: 1.5rem;
        }

        .logo p {
            margin: 0;
            font-size: 0.9rem;
            color: #fff;
        }

        .top-menu {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .cart-nav .dropdown-toggle {
            color: #fff;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            padding: 5px 15px;
            transition: color 0.2s ease;
        }

        .cart-nav .dropdown-toggle i {
            margin-right: 5px;
            font-size: 1.2rem;
            color: #fff;
        }

        .cart-nav .dropdown-toggle:hover {
            color: #d1d5db;
        }

        .cart-dropdown {
            min-width: 300px;
            background: #fff;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 15px;
            animation: slideDown 0.3s ease;
            max-height: 400px;
            overflow-y: auto;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cart-dropdown .media {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .cart-dropdown .media:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .cart-dropdown .media-object {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .cart-dropdown .media-body h4 {
            font-size: 1rem;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .cart-dropdown .cart-price {
            font-size: 0.9rem;
            color: #4b5563;
        }

        .cart-dropdown .cart-summary {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .cart-dropdown .cart-buttons {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }

        .cart-dropdown .btn-small {
            background: #1a1a1a;
            color: #fff;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .cart-dropdown .btn-small:hover {
            background: #333;
        }

        /* Menu */
        .menu {
            background: rgba(26, 26, 26, 0.9); /* Semi-transparent black */
            padding: 10px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navigation .navbar-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .navigation .navbar-nav > li > a {
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            padding: 10px 15px;
            transition: all 0.2s ease;
        }

        .navigation .navbar-nav > li > a:hover {
            color: #d1d5db;
            transform: scale(1.05);
        }

        .dropdown-slide .dropdown-toggle {
            color: #fff;
            position: relative;
            padding: 10px 15px;
        }

        .dropdown-slide .dropdown-toggle:hover {
            color: #d1d5db;
        }

        .dropdown-menu {
            background: #fff;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 10px 0;
            animation: slideDown 0.3s ease;
            min-width: 160px;
        }

        .dropdown-menu li a {
            color: #1a1a1a;
            padding: 8px 20px;
            font-size: 0.95rem;
            transition: background 0.2s ease;
        }

        .dropdown-menu li a:hover {
            background: #f7f7f7;
            color: #1a1a1a;
        }

        .navbar-toggle {
            border: 1px solid #fff;
            border-radius: 4px;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }

        .navbar-toggle:hover {
            background: #333;
        }

        .navbar-toggle .icon-bar {
            background: #fff;
            transition: all 0.3s ease;
        }

        .navbar-toggle.collapsed .icon-bar {
            background: #d1d5db;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .top-header .row {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .logo {
                margin: 10px 0;
            }

            .menu .navbar-nav {
                flex-direction: column;
                text-align: center;
            }

            .navigation .navbar-nav > li > a {
                padding: 10px;
            }

            .dropdown-menu {
                margin-left: 0;
            }
        }
    </style>
</head>

<body id="body">

<!-- Header -->
<section class="top-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 col-xs-12">
                <div class="contact-number">
                    <i class="tf-ion-ios-telephone"></i>
                    <span>+84 123 456 789</span>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="logo">
                    <a href="/">
                        <h2 style="margin:0; font-weight:bold; color:#fff;">GIA DỤNG VIỆT</h2>
                        <p style="font-size:14px; color:#fff;">Chất lượng cho mọi nhà</p>
                    </a>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <ul class="top-menu list-inline">
                    <li class="dropdown cart-nav dropdown-slide">
                        <a href="#!" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
                            <i class="tf-ion-android-cart"></i> Giỏ hàng
                        </a>
                        <div class="dropdown-menu cart-dropdown">
                            <?php if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0): ?>
                                <div class="media">
                                    <div class="media-body">
                                        <h4 class="text-center">Giỏ hàng trống</h4>
                                    </div>
                                </div>
                                <div class="cart-summary">
                                    <span>Tổng</span>
                                    <span class="total-price">₫ 0</span>
                                </div>
                                <ul class="text-center cart-buttons">
                                    <li><a href="/cart" class="btn btn-small">Xem giỏ</a></li>
                                </ul>
                            <?php else: ?>
                                <?php foreach ($_SESSION['cart'] as $item): ?>
                                    <div class="media">
                                        <a class="pull-left" href="#!">
                                            <img class="media-object" src="<?= htmlspecialchars($item['image']) ?>" alt="product image" width="50" />
                                        </a>
                                        <div class="media-body">
                                            <h4 class="media-heading"><?= htmlspecialchars($item['title']) ?></h4>
                                            <div class="cart-price">
                                                <span><?= $item['Số lượng'] ?> x</span>
                                                <span><?= number_format($item['price'], 0, ',', '.') ?>₫</span>
                                            </div>
                                            <h5><strong><?= number_format($item['Số lượng'] * $item['price'], 0, ',', '.') ?>₫</strong></h5>
                                        </div>
                                        <a href="/cart-remove-item?id=<?= htmlspecialchars($item['id']) ?>"><i class="tf-ion-close"></i></a>
                                    </div>
                                <?php endforeach; ?>
                                <div class="cart-summary">
                                    <span>Tổng</span>
                                    <span class="total-price">
                                        ₫<?php 
                                            $total = 0;
                                            foreach ($_SESSION['cart'] as $item) {
                                                $total += $item['price'] * $item['Số lượng'];
                                            }
                                            echo number_format($total, 0, ',', '.');
                                        ?>
                                    </span>
                                </div>
                                <ul class="text-center cart-buttons">
                                    <li><a href="/cart" class="btn btn-small">Xem giỏ</a></li>
                                </ul>
                            <?php endif ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Menu -->
<section class="menu">
    <nav class="navbar navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar" class="navbar-collapse collapse text-center">
                <ul class="nav navbar-nav">
                    <li><a href="/">Trang chủ</a></li>
                    <li><a href="/products">Sản phẩm</a></li>
                    <li><a href="/about">Giới thiệu</a></li>
                    <li><a href="/contact">Liên hệ</a></li>
                    <?php if (isset($_SESSION['name'])): ?>
                        <li class="dropdown dropdown-slide">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= htmlspecialchars($_SESSION['name']) ?> <span class="tf-ion-ios-arrow-down"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/profile">Thông tin</a></li>
                                <li><a href="/logout">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="dropdown dropdown-slide">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tài khoản <span class="tf-ion-ios-arrow-down"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/login">Đăng nhập</a></li>
                                <li><a href="/register">Đăng ký</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</section>

<!-- Thêm phần nội dung sản phẩm ở đây nếu muốn -->

<script src="views/plugins/jquery/jquery.min.js"></script>
<script src="views/plugins/bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>