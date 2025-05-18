<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function generateInvoice($date)
{
    $name = htmlspecialchars($_SESSION['name'] ?? 'Guest');
    $address = htmlspecialchars($_SESSION['address'] ?? 'Unknown');
    $email = htmlspecialchars($_SESSION['email'] ?? 'noemail@example.com');

    $invoice = "
    <div style=\"max-width: 600px; margin: auto; font-family: Arial, sans-serif; border: 1px solid #eee; padding: 20px; color: #555;\">
        <h2 style=\"text-align: center; color: #333;\">Ecomerce INVOICE</h2>
        <p style=\"text-align: right; font-size: 14px;\">Date: {$date}</p>

        <h4>Customer Information</h4>
        <p>
            {$name}<br>
            {$address}<br>
            {$email}
        </p>

        <hr style=\"border: 1px solid #eee;\">

        <h4>Order Summary</h4>
        <table style=\"width: 100%; border-collapse: collapse;\">
            <thead>
                <tr>
                    <th style=\"text-align: left; padding: 8px; border-bottom: 1px solid #ddd;\">Item</th>
                    <th style=\"text-align: right; padding: 8px; border-bottom: 1px solid #ddd;\">Price</th>
                </tr>
            </thead>
            <tbody>";

    $total = 0;

    foreach ($_SESSION['cart'] as $item) {
        $title = htmlspecialchars($item['title']);
        $Số lượng = (int) $item['Số lượng'];
        $price = (float) $item['price'];
        $subtotal = $price * $Số lượng;
        $total += $subtotal;

        $invoice .= "
            <tr>
                <td style=\"padding: 8px;\">{$title} (x{$Số lượng})</td>
                <td style=\"padding: 8px; text-align: right;\">VND" . number_format($subtotal, 2) . "</td>
            </tr>";
    }

    $invoice .= "
            <tr>
                <td style=\"padding: 8px; text-align: right; font-weight: bold;\">Total:</td>
                <td style=\"padding: 8px; text-align: right; font-weight: bold;\">VND" . number_format($total, 2) . "</td>
            </tr>
            </tbody>
        </table>

        <p style=\"margin-top: 30px; text-align: center; color: #888; font-size: 13px;\">
            Thank you for shopping with YEM-YEM!
        </p>
    </div>";

    return $invoice;
}