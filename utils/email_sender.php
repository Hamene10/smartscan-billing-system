<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendInvoiceEmail($recipientEmail, $recipientName, $pdfPath, $orderId) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';           // Replace with your SMTP server (e.g., smtp.gmail.com)
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gurlc982@gmail.com';     // Replace with your email address
        $mail->Password   = 'nzxv vmnv ecbr ddno';        // Replace with your App Password (not your login password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('no-reply@smartscanbilling.com', 'Smart Scan Billing');
        $mail->addAddress($recipientEmail, $recipientName);

        // Attachments
        if (file_exists($pdfPath)) {
            $mail->addAttachment($pdfPath, 'Invoice_' . $orderId . '.pdf');
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Invoice from Smart Scan Billing - #' . $orderId;
        $mail->Body    = "Hello " . $recipientName . ",<br><br>Thank you for your purchase. Please find your invoice attached for Order #" . $orderId . ".<br><br>Best regards,<br>Smart Scan Billing Team";
        $mail->AltBody = "Hello " . $recipientName . ",\n\nThank you for your purchase. Please find your invoice attached for Order #" . $orderId . ".\n\nBest regards,\nSmart Scan Billing Team";

        // Sending the email
        $mail->send();
        
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

function sendPasswordResetEmail($recipientEmail, $recipientName, $resetLink) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gurlc982@gmail.com';
        $mail->Password   = 'nzxv vmnv ecbr ddno';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('no-reply@smartscanbilling.com', 'Smart Scan Billing');
        $mail->addAddress($recipientEmail, $recipientName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request - Smart Scan Billing';
        $mail->Body    = "Hello " . $recipientName . ",<br><br>We received a request to reset your password for your Smart Scan Billing account. Click the link below to set a new password:<br><br><a href='" . $resetLink . "'>Reset Password</a><br><br>If you did not request this, please ignore this email.<br><br>Best regards,<br>Smart Scan Billing Team";
        $mail->AltBody = "Hello " . $recipientName . ",\n\nWe received a request to reset your password for your Smart Scan Billing account. Copy and paste the link below to set a new password:\n\n" . $resetLink . "\n\nIf you did not request this, please ignore this email.\n\nBest regards,\nSmart Scan Billing Team";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Reset email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
