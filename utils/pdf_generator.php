<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db_connect.php';

function generateInvoicePdf($orderId) {
    global $conn;

    // Fetch Order Info
    $stmt = $conn->prepare("SELECT orders.*, users.full_name, users.phone, users.email FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    // Fetch Items
    $stmt = $conn->prepare("SELECT order_items.*, products.name FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $items = $stmt->get_result();

    // Create PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Smart Scan Billing');
    $pdf->SetTitle('Invoice #' . $orderId);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(15, 15, 15);
    $pdf->AddPage();

    $html = '
    <h1 style="text-align:center;">Smart Scan Billing</h1>
    <p>123 Grocery Lane, Smart City, 560000</p>
    <hr>
    <table cellpadding="5">
        <tr>
            <td><strong>Invoice #: </strong>' . $orderId . '</td>
            <td style="text-align:right;"><strong>Date: </strong>' . $order['order_date'] . '</td>
        </tr>
    </table>
    <br><br>
    <strong>Bill To:</strong><br>
    ' . $order['full_name'] . '<br>
    ' . $order['email'] . '<br>
    ' . $order['phone'] . '<br><br>
    <table border="1" cellpadding="5">
        <thead>
            <tr style="background-color:#f2f2f2;">
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>';
    
    while ($item = $items->fetch_assoc()) {
        $html .= '
            <tr>
                <td>' . $item['name'] . '</td>
                <td>$' . number_format($item['price'], 2) . '</td>
                <td>' . $item['quantity'] . '</td>
                <td>$' . number_format($item['price'] * $item['quantity'], 2) . '</td>
            </tr>';
    }

    $html .= '
            <tr>
                <th colspan="3" style="text-align:right;"><strong>Grand Total</strong></th>
                <th><strong>$' . number_format($order['total_amount'], 2) . '</strong></th>
            </tr>
        </tbody>
    </table>
    <br><br>
    <p style="text-align:center;">Thank you for shopping with us!</p>';

    $pdf->writeHTML($html, true, false, true, false, '');
    
    $outputPath = __DIR__ . '/../uploads/invoices/invoice_' . $orderId . '.pdf';
    if (!is_dir(__DIR__ . '/../uploads/invoices')) {
        mkdir(__DIR__ . '/../uploads/invoices', 0777, true);
    }
    
    $pdf->Output($outputPath, 'F');
    return $outputPath;
}
?>
