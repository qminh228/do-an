<?php 
require __DIR__ . '/header.php';
require __DIR__ . '/admin/sendEmail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    $subject = "Liên hệ từ khách hàng: $name";
    $body = "
        <h3>Thông tin liên hệ</h3>
        <p><strong>Họ tên:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Nội dung:</strong><br>{$message}</p>
    ";

    sendEmailTest(['thedanh0410@gmail.com'], $subject, $body);

    echo "<script>alert('✅ Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất!');</script>";
}
?>

<section class="contact-section py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Liên hệ với chúng tôi</h2>
      <p class="text-muted">Nếu bạn có câu hỏi hoặc cần hỗ trợ, đừng ngần ngại gửi tin nhắn cho chúng tôi.</p>
    </div>

    <div class="row g-5">
      <div class="col-md-6">
        <h5>Thông tin liên hệ</h5>
        <ul class="list-unstyled">
          <li><i class="bi bi-geo-alt-fill me-2 text-primary"></i> 123 Đường ABC, Quận 1, TP.HCM</li>
          <li><i class="bi bi-envelope-fill me-2 text-primary"></i> hotro@giadungviet.vn</li>
          <li><i class="bi bi-phone-fill me-2 text-primary"></i> 0989 999 999</li>
        </ul>
        <div class="mt-4">
          <h6>Kết nối với chúng tôi</h6>
          <a href="#" class="me-3"><i class="bi bi-facebook fs-4 text-primary"></i></a>
          <a href="#" class="me-3"><i class="bi bi-instagram fs-4 text-danger"></i></a>
          <a href="#"><i class="bi bi-youtube fs-4 text-danger"></i></a>
        </div>
      </div>

      <div class="col-md-6">
        <h5>Gửi tin nhắn</h5>
        <form action="" method="post">
          <div class="mb-3">
            <label for="name" class="form-label">Họ tên</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nguyễn Văn A" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email của bạn</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="abc@example.com" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Nội dung</label>
            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Nội dung tin nhắn..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Gửi liên hệ</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>
