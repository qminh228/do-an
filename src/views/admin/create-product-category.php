<?php

require __DIR__ . '/header.php';
require __DIR__ . '/../db.php';
require __DIR__ . '/../../csrf.php';

if (isset($_POST['submit']) && CSRF::validateToken($_POST['token'])) {
    $category = trim(filter_input(INPUT_POST, 'category'));

    if (!empty($category)) {
        // Check if category already exists
        $statement = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE title = ?");
        $statement->execute([$category]);

        if (!$statement->fetchColumn() > 0) {
            $statement = $pdo->prepare("INSERT INTO categories(title) VALUES (?)");
            $statement->execute([$category]);
            $success = "Category created successfully!";
        } else {
            $error = "This category already exists.";
        }
    } else {
        $error = "Category name cannot be empty.";
    }
}

?>
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-header"><i class="fas fa-folder-plus"></i> Create Category</div>
            <div class="card-body">
                <div class="col-md-6">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <form action="/admin/categories/create" method="post">
                        <?php CSRF::csrfInputField() ?>
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="category" class="form-control" required>
                        </div>
                        <div class="mb-3 text-end">
                            <button name="submit" type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
