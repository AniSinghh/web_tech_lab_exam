<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question 2: Product Invoice & Discount Calculation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 30px 15px;
        }
        .container {
            max-width: 750px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }
        h1 {
            color: #1a365d;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            font-size: 24px;
            margin-top: 0;
        }
        .discount-notice {
            background-color: #e6fffa;
            border-left: 4px solid #319795;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 14px;
            color: #234e52;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background-color: #2b6cb0;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        th:first-child {
            border-top-left-radius: 8px;
        }
        th:last-child {
            border-top-right-radius: 8px;
        }
        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        tbody tr:hover {
            background-color: #edf2f7;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .summary-label {
            text-align: right;
            font-weight: 600;
            padding-right: 20px;
        }
        .total-row td, .total-row th {
            background-color: #edf2f7;
            font-weight: 600;
            border-top: 2px solid #cbd5e0;
        }
        .discount-row td, .discount-row th {
            background-color: #f0fff4;
            color: #276749;
            font-weight: 600;
        }
        .payable-row td, .payable-row th {
            background-color: #ebf8ff;
            color: #2b6cb0;
            font-size: 17px;
            font-weight: 700;
            border-bottom: 2px solid #3182ce;
        }
        .footer-info {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #718096;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Question 2: Product Billing & Discount Calculator</h1>

    <?php
    // Step 1: Define an associative array with product names (keys) and prices in Rs. (values)
    // Sum is intentionally > Rs. 2,000 to trigger the discount logic
    $products = [
        "Wireless Mouse"       => 450,
        "Mechanical Keyboard"  => 1200,
        "USB Hub"              => 350,
        "Laptop Stand"         => 800
    ];

    // Step 2: Calculate total price
    $total = 0;
    foreach ($products as $name => $price) {
        $total += $price;
    }

    // Step 3: Check discount condition (> 2000 => 10% discount, else 0)
    $discountRate = 0;
    $discount = 0;
    if ($total > 2000) {
        $discountRate = 0.10;
        $discount = $total * $discountRate;
    }

    // Step 4: Calculate final payable amount
    $finalPayable = $total - $discount;
    ?>

    <div class="discount-notice">
        <?php if ($discount > 0): ?>
            🎉 <strong>10% Discount Applied!</strong> Order total exceeds <strong>Rs. 2,000.00</strong> threshold.
        <?php else: ?>
            ℹ️ No discount applied. Add more items to reach the Rs. 2,000.00 threshold for a 10% discount.
        <?php endif; ?>
    </div>

    <!-- Step 5: Render Products in Styled HTML Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 15%;" class="text-center">S.No</th>
                <th style="width: 55%;">Product Name</th>
                <th style="width: 30%;" class="text-right">Price (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sno = 1;
            foreach ($products as $name => $price):
            ?>
                <tr>
                    <td class="text-center"><?php echo $sno++; ?></td>
                    <td><strong><?php echo htmlspecialchars($name); ?></strong></td>
                    <td class="text-right">Rs. <?php echo number_format($price, 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <!-- Total Price Row -->
            <tr class="total-row">
                <td colspan="2" class="summary-label">Total Price:</td>
                <td class="text-right">Rs. <?php echo number_format($total, 2); ?></td>
            </tr>

            <!-- Discount Row -->
            <tr class="discount-row">
                <td colspan="2" class="summary-label">
                    Discount <?php echo ($discountRate > 0) ? "(10% Off)" : "(0%)"; ?>:
                </td>
                <td class="text-right">
                    - Rs. <?php echo number_format($discount, 2); ?>
                </td>
            </tr>

            <!-- Final Payable Amount Row -->
            <tr class="payable-row">
                <td colspan="2" class="summary-label">Final Payable Amount:</td>
                <td class="text-right">Rs. <?php echo number_format($finalPayable, 2); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-info">
        <span>Order Condition: <em>Total &gt; Rs. 2,000 = 10% Discount</em></span>
        <span>Items Count: <strong><?php echo count($products); ?></strong></span>
    </div>
</div>

</body>
</html>
