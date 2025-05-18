<?php
require __DIR__ . '/header.php';
require __DIR__ . '/../csrf.php';
require __DIR__ . '/db.php';

$products = [];
$searchEmpty = false;
$page = isset($_GET['p']) ? filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT) : 1;
$results_per_page = 9;
$page_first_result = 0;
$number_of_pages = 1;

// Lấy tất cả danh mục
$statement = $pdo->prepare("SELECT * FROM categories ORDER BY title");
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

// Tìm kiếm sản phẩm theo query và danh mục
if (isset($_POST['q']) && CSRF::validateToken($_POST['token'])) {
    $query = filter_input(INPUT_POST, 'q');
    $category = filter_input(INPUT_GET, 'c');

    if ($category) {
        $statement = $pdo->prepare("SELECT * FROM products WHERE category = ? AND CONCAT(title, price, description, category) LIKE ?");
        $statement->execute([$category, "%$query%"]);
    } else {
        $statement = $pdo->prepare("SELECT * FROM products WHERE CONCAT(title, price, description, category) LIKE ?");
        $statement->execute(["%$query%"]);
    }

    if ($statement->rowCount() > 0) {
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $searchEmpty = true;
    }
}
// Lọc theo danh mục
elseif (isset($_GET['c'])) {
    $category = filter_input(INPUT_GET, 'c');
    $page_first_result = ($page - 1) * $results_per_page;

    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category = ?");
    $countStmt->execute([$category]);
    $number_of_result = $countStmt->fetchColumn();
    $number_of_pages = ceil($number_of_result / $results_per_page);

    $statement = $pdo->prepare("SELECT * FROM products WHERE category = ? LIMIT $page_first_result, $results_per_page");
    $statement->execute([$category]);

    if ($statement->rowCount() > 0) {
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
// Mặc định hiển thị tất cả sản phẩm
else {
    $page_first_result = ($page - 1) * $results_per_page;

    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products");
    $countStmt->execute();
    $number_of_result = $countStmt->fetchColumn();
    $number_of_pages = ceil($number_of_result / $results_per_page);

    $statement = $pdo->prepare("SELECT * FROM products LIMIT $page_first_result, $results_per_page");
    $statement->execute();

    if ($statement->rowCount() > 0) {
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Appliances</title>
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
            line-height: 1.6;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .products.section {
            padding: 60px 0;
        }

        /* Sidebar */
        .widget.product-category {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .widget-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #1a1a1a;
            box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
        }

        .btn-main {
            background: #1a1a1a;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            width: 100%;
        }

        .btn-main:hover {
            background: #333;
        }

        .widget ul {
            list-style: none;
        }

        .widget ul li {
            margin-bottom: 12px;
        }

        .widget ul li a {
            text-decoration: none;
            color: #4b5563;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .widget ul li a:hover {
            color: #1a1a1a;
        }

        /* Product Grid */
        .product-item {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
        }

        .product-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .product-thumb img {
            width: 100%;
            height: 300px; /* Fixed height for images */
            object-fit: cover;
            display: block;
        }

        .product-content {
            padding: 20px;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-content h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            flex-grow: 0;
        }

        .product-content h4 a {
            text-decoration: none;
            color: #1a1a1a;
            transition: color 0.2s;
        }

        .product-content h4 a:hover {
            color: #4b5563;
        }

        .product-content .price {
            color: #b91c1c;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            flex-grow: 0;
        }

        .product-content .btn-view {
            display: inline-block;
            background: #1a1a1a;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background 0.2s;
            flex-grow: 0;
        }

        .product-content .btn-view:hover {
            background: #333;
        }

        /* No Items Found */
        .no-items {
            padding: 50px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .no-items i {
            font-size: 3.5rem;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .no-items h2 {
            font-size: 1.75rem;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        .no-items .btn-main {
            display: inline-block;
            width: auto;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 40px;
        }

        .pagination a {
            text-decoration: none;
            padding: 10px 18px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            color: #1a1a1a;
            font-size: 0.95rem;
            transition: background 0.2s, color 0.2s;
        }

        .pagination a:hover {
            background: #1a1a1a;
            color: #fff;
            border-color: #1a1a1a;
        }

        .pagination a.active {
            background: #1a1a1a;
            color: #fff;
            border-color: #1a1a1a;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .products.section {
                padding: 30px 0;
            }

            .widget.product-category {
                margin-bottom: 30px;
            }

            .product-item {
                height: 400px;
            }

            .product-thumb img {
                height: 250px;
            }

            .product-content h4 {
                font-size: 1.1rem;
            }

            .product-content .price {
                font-size: 1rem;
            }

            .product-content {
                padding: 15px;
            }

            .no-items {
                padding: 30px;
            }

            .no-items i {
                font-size: 2.5rem;
            }

            .no-items h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<section class="products section">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="widget product-category">
                    <form action="/products<?= isset($_GET['c']) ? '?c=' . htmlspecialchars($_GET['c']) : '' ?>" method="post">
                        <?php CSRF::csrfInputField() ?>
                        <div class="form-group">
                            <input name="q" type="search" class="form-control" placeholder="Search home appliances...">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-main">Search</button>
                        </div>
                    </form>
                    <br>
                    <h4 class="widget-title">Categories</h4>
                    <ul>
                        <li><a href="/products">All Appliances</a></li>
                        <?php foreach($categories as $category): ?>
                            <li><a href="/products?c=<?= htmlspecialchars($category['title']) ?>"><?= htmlspecialchars($category['title']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Product List -->
            <div class="col-md-9">
                <div class="row">
                    <?php if (!$searchEmpty): ?>
                        <?php foreach($products as $product): ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="product-item">
                                    <div class="product-thumb">
                                        <img class="img-responsive" src="<?= htmlspecialchars(unserialize($product['images'])[0]) ?>" alt="product-img">
                                    </div>
                                    <div class="product-content">
                                        <h4><a href="/item?id=<?= htmlspecialchars($product['id']) ?>"><?= htmlspecialchars($product['title']) ?></a></h4>
                                        <p class="price">VND <?= number_format($product['price'], 2) ?></p>
                                        <a href="/item?id=<?= htmlspecialchars($product['id']) ?>" class="btn-view">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-md-12 no-items">
                            <i class="tf-ion-ios-cart-outline"></i>
                            <h2>No appliances found</h2>
                            <a href="/products" class="btn btn-main">Return to shop</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if (!isset($_POST['q']) && $number_of_pages > 1): ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <nav class="pagination">
                                <?php
                                    $baseUrl = '/products' . (isset($_GET['c']) ? '?c=' . urlencode($_GET['c']) . '&' : '?');
                                    $start = max(1, $page - 1);
                                    $end = min($number_of_pages, $page + 1);

                                    for ($i = $start; $i <= $end; $i++) {
                                        echo "<a href='{$baseUrl}p=$i' class='" . ($i == $page ? "active" : "") . "'>$i</a>";
                                    }
                                ?>
                            </nav>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
</body>
</html>