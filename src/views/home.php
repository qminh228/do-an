<?php 
require __DIR__ . '/header.php'; 
require __DIR__ . '/db.php';

$items;
$statement = $pdo->prepare("SELECT * FROM products ORDER BY rand() LIMIT 9");
$statement->execute();
if($statement->rowCount() > 0) {
    $items = $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Appliances - Featured Products</title>
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

        .products.section,
        .call-to-action.section {
            padding: 60px 0;
        }

        .bg-gray {
            background-color: #f7f7f7;
        }

        .title {
            margin-bottom: 40px;
        }

        .title h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 15px;
        }

        .title p {
            font-size: 1rem;
            color: #4b5563;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Product Grid */
        .product-item {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 30px;
            height: 500px; /* Fixed height for all product items */
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

        /* Call to Action */
        .call-to-action .subscription-form {
            display: flex;
            justify-content: center;
            max-width: 500px;
            margin: 0 auto;
        }

        .call-to-action .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px 0 0 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .call-to-action .form-control:focus {
            outline: none;
            border-color: #1a1a1a;
            box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
        }

        .call-to-action .btn-main {
            background: #1a1a1a;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 0 8px 8px 0;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .call-to-action .btn-main:hover {
            background: #333;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .products.section,
            .call-to-action.section {
                padding: 30px 0;
            }

            .title h2 {
                font-size: 1.75rem;
            }

            .title p {
                font-size: 0.9rem;
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

            .call-to-action .subscription-form {
                flex-direction: column;
                gap: 10px;
            }

            .call-to-action .form-control {
                border-radius: 8px;
            }

            .call-to-action .btn-main {
                border-radius: 8px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
<section class="products section bg-gray">
    <div class="container">
        <div class="row">
            <div class="title text-center">
                <h2>What would you like for?</h2>
            </div>
        </div>
        <div class="row">
            <?php if(isset($items)): ?>
                <?php foreach($items as $item): ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="product-item">
                            <div class="product-thumb">
                                <img class="img-responsive" src="<?= htmlspecialchars(unserialize($item['images'])[0]) ?>" alt="<?= htmlspecialchars($item['title']) ?>" />
                            </div>
                            <div class="product-content">
                                <h4><a href="/item?id=<?= htmlspecialchars($item['id']) ?>"><?= htmlspecialchars($item['title']) ?></a></h4>
                                <p class="price">VND <?= number_format($item['price'], 2) ?></p>
                                <a href="/item?id=<?= htmlspecialchars($item['id']) ?>" class="btn-view">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif ?>
        </div>
    </div>
</section>

<section class="call-to-action bg-gray section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="title">
                    <h2>ĐĂNG KÝ NHẬN ƯU ĐÃI & MẸO SỬ DỤNG ĐỒ GIA DỤNG</h2>
                    <p>Đừng bỏ lỡ các chương trình khuyến mãi hấp dẫn, mẹo sử dụng đồ gia dụng thông minh và cập nhật sản phẩm mới nhất cho tổ ấm của bạn.</p>
                </div>
                <div class="subscription-form">
                    <input type="email" class="form-control" placeholder="Nhập địa chỉ email của bạn">
                    <button class="btn btn-main" type="button">Đăng ký ngay</button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>