<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

function sendEmailTest(array $recipients, string $subject, string $body) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'thedanh0410@gmail.com'; 
        $mail->Password   = 'nuycldvcotvyyhhq';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->CharSet    = 'UTF-8';

        // Sender info
        $mail->setFrom('thedanh0410@gmail.com', 'Your Website');

        // Recipients
        foreach ($recipients as $email) {
            $mail->addAddress($email);
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body; // <-- Gửi HTML thuần

        $mail->send();
        echo "✅ Email đã được gửi thành công!";
    } catch (Exception $e) {
        echo "❌ Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
    }
}

