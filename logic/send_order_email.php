<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require (__DIR__ . '/../phpmailer/PHPMailer.php');
require (__DIR__ . '/../phpmailer/SMTP.php');
require (__DIR__ . '/../phpmailer/Exception.php');

function sendOrderEmail($order, $order_items, $order_id): bool {
    $mail = new PHPMailer(true);

    // SMTP Debugging
    // $mail->SMTPDebug = 2;
    // $mail->Debugoutput = function($str, $level) {
    //     echo "<pre>[$level] $str</pre>";
    // };


    try {
        // SMTP settings - Admin Email
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com';
        $mail->Password   = 'your-app-password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('your-email@gmail.com', 'LokiMart');
        $mail->addAddress($order['customer_email'], $order['customer_name']);

        // Optional internal notifications
        // $mail->addBCC('admin@example.com', 'Administrator');
        // $mail->addCC('support@example.com');

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Order Confirmation - LokiMart (Order #$order_id)";

        $grand_total = 0;
        $item_rows = '';

        foreach ($order_items as $item) {
            $total = $item['price'] * $item['quantity'];
            $grand_total += $total;

            $item_rows .= "
                <tr>
                    <td>" . htmlspecialchars($item['product_name']) . "</td>
                    <td>" . (int)$item['quantity'] . "</td>
                    <td>ZK" . number_format($item['price'], 2) . "</td>
                    <td>ZK" . number_format($total, 2) . "</td>
                </tr>
            ";
        }

        $body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                <h2>Order Confirmation</h2>
                <p>Hi <strong>{$order['customer_name']}</strong>,</p>
                <p>Thank you for your order. Here are your details:</p>

                <p><strong>Order ID:</strong> #$order_id</p>
                <p><strong>Ordered By:</strong> {$order['created_by']}</p>

                <h3>Order Summary</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <thead>
                        <tr style='background: #f0f0f0;'>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        $item_rows
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan='3' style='text-align:right;'><strong>Grand Total:</strong></td>
                            <td><strong>ZK" . number_format($grand_total, 2) . "</strong></td>
                        </tr>
                    </tfoot>
                </table>

                <h3>Shipping Address</h3>
                <p>" . nl2br(htmlspecialchars($order['customer_address'])) . "</p>

                <p>Regards,<br>LokiMart Team</p>
            </div>
        ";

        $mail->Body = $body;
        $mail->send();
        // echo "<pre>Email sent successfully to {$order['customer_email']}</pre>";
        return true;

    } catch (Exception $e) {
        $_SESSION['email_error'] = $mail->ErrorInfo;
        return false;
    }
}
