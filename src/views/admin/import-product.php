<?php
require __DIR__ . '/header.php';
require __DIR__ . '/../db.php';
require __DIR__ . '/../../csrf.php';
require __DIR__ . '/util.php';

function saveImagesFromURLs($urls) {
    $uploadDir = __DIR__ . "/../../uploads/products/";
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $paths = []; // Mảng lưu đường dẫn ảnh

    foreach ($urls as $url) {
        // Lấy phần mở rộng file từ URL
        $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
        $fileExt = strtolower($pathInfo['extension'] ?? 'jpg'); // Mặc định là jpg nếu không có extension

        // Tạo tên file ngẫu nhiên
        $imageName = md5(microtime(true)) . '.' . $fileExt;
        $imagePath = $uploadDir . $imageName;

        // Tải ảnh về
        $imageData = @file_get_contents($url);
        if ($imageData === false) {
            continue; // Nếu lỗi, bỏ qua ảnh này
        }

        // Lưu ảnh tạm thời
        file_put_contents($imagePath, $imageData);

        // Kiểm tra Magic Byte
        if (!verifyMagicByte($imagePath)) {
            unlink($imagePath); // Xóa file nếu không hợp lệ
            continue;
        }

        // Nén ảnh
        compressImage($imagePath, $imagePath, 50);

        // Lưu đường dẫn ảnh vào mảng
        $paths[] = "uploads/products/" . $imageName;
    }

    // Serialize giống `uploadImages()`
    return serialize($paths);
}

// Xử lý khi người dùng submit file CSV
if(isset($_POST['submit']) && CSRF::validateToken($_POST['token'])) {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        die("<script>alert('Lỗi khi upload file CSV.'); window.history.back();</script>");
    }

    $file = $_FILES['file']['tmp_name'];
    $handle = fopen($file, "r");

    if ($handle === false) {
        die("<script>alert('Không thể mở file CSV.'); window.history.back();</script>");
    }

    // Đọc dòng tiêu đề
    $header = fgetcsv($handle);
    $requiredColumns = ['title', 'description', 'price', 'category', 'image_url'];

    // Kiểm tra file CSV có đủ cột không
    foreach ($requiredColumns as $column) {
        if (!in_array($column, $header)) {
            die("<script>alert('File CSV thiếu cột $column.'); window.history.back();</script>");
        }
    }

    $pdo->beginTransaction();

    while (($row = fgetcsv($handle)) !== false) {
        $data = array_combine($header, $row);

        $title = trim($data['title']);
        $description = trim($data['description']);
        $price = floatval($data['price']);
        $category = trim($data['category']);
        $imageUrl = trim($data['image_url']);

        // Kiểm tra nếu danh mục chưa tồn tại thì thêm mới
        $statement = $pdo->prepare("SELECT id FROM categories WHERE title = ?");
        $statement->execute([$category]);
        $categoryId = $statement->fetchColumn();

        if (!$categoryId) {
            $statement = $pdo->prepare("INSERT INTO categories(title) VALUES (?)");
            $statement->execute([$category]);
            $categoryId = $pdo->lastInsertId();
        }

        // Lưu ảnh từ URL
        // $imagePath = !empty($imageUrl) ? saveImagesFromURLs($imageUrl) : '';
        $imageURLs = explode(',', $data['image_url']); // Giả sử ảnh tách bằng dấu `,`
        $imagesSerialized = saveImagesFromURLs($imageURLs);

        // Thêm sản phẩm vào database
        $statement = $pdo->prepare("INSERT INTO products(title, price, description, category, images) VALUES (?, ?, ?, ?, ?)");
        $statement->execute([$title, $price, $description, $category, $imagesSerialized]);
    }

    fclose($handle);
    $pdo->commit();

    echo "<script>alert('Import thành công!'); window.location.href='/admin/products';</script>";
}
?>

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-lg p-4 rounded-3 w-50">
        <div class="card-header bg-primary text-white text-center fw-bold">
            <i class="fas fa-file-csv"></i> Import Products from CSV
        </div>
        <div class="card-body">
            <p class="text-muted text-center">
                Upload a CSV file to import products into your store. The file must contain the following columns:
                <strong>Title, Description, Price, Category, Image URL</strong>.
            </p>

            <form action="" method="post" enctype="multipart/form-data">
                <?php CSRF::csrfInputField() ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Upload CSV File</label>
                    <input type="file" name="file" class="form-control border-primary shadow-sm" accept=".csv" required>
                    <small class="text-muted">Accepted formats: .csv</small>
                </div>

                <div class="text-center">
                    <button name="submit" type="submit" class="btn btn-lg btn-primary shadow">
                        <i class="fas fa-upload"></i> Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require __DIR__ . '/../footer.php'; ?>
