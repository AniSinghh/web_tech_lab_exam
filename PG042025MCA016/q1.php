<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question 1: Numeric Array Analysis</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 30px 15px;
        }
        .container {
            max-width: 700px;
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
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #4a5568;
            margin: 20px 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .array-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }
        .array-badge {
            background: #edf2f7;
            border: 1px solid #cbd5e0;
            color: #2d3748;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 15px;
        }
        .stat-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            transition: all 0.2s ease;
        }
        .stat-card.highlight-green {
            border-left: 5px solid #38a169;
        }
        .stat-card.highlight-red {
            border-left: 5px solid #e53e3e;
        }
        .stat-card.highlight-blue {
            border-left: 5px solid #3182ce;
        }
        .stat-card.highlight-purple {
            border-left: 5px solid #805ad5;
        }
        .stat-label {
            font-size: 13px;
            color: #718096;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #1a202c;
        }
        .footer-note {
            margin-top: 25px;
            padding: 12px;
            background: #ebf8ff;
            border-radius: 8px;
            color: #2b6cb0;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Question 1: Indexed Array Operations</h1>

    <?php
    // Step 1: Initialize an indexed array with 10 numeric values
    $numbers = [45, 12, 89, 34, 99, 23, 76, 5, 61, 38];

    // Initialize tracking variables with the first element
    $largest = $numbers[0];
    $smallest = $numbers[0];
    $sum = 0;
    $count = count($numbers);

    // Step 2 & 3: Iterate through elements to display numbers and compute stats via manual loop
    ?>

    <div class="section-title">Array Elements (Traversed via Loop):</div>
    <div class="array-container">
        <?php
        foreach ($numbers as $index => $val) {
            echo "<div class='array-badge'>Index [$index] = $val</div>";

            // Manual logic to find largest
            if ($val > $largest) {
                $largest = $val;
            }

            // Manual logic to find smallest
            if ($val < $smallest) {
                $smallest = $val;
            }

            // Sum accumulation
            $sum += $val;
        }

        // Calculate average
        $average = $count > 0 ? ($sum / $count) : 0;
        ?>
    </div>

    <div class="section-title">Statistical Summary:</div>
    <div class="stats-grid">
        <div class="stat-card highlight-green">
            <div class="stat-label">Largest Number</div>
            <div class="stat-value"><?php echo $largest; ?></div>
        </div>

        <div class="stat-card highlight-red">
            <div class="stat-label">Smallest Number</div>
            <div class="stat-value"><?php echo $smallest; ?></div>
        </div>

        <div class="stat-card highlight-blue">
            <div class="stat-label">Total Sum</div>
            <div class="stat-value"><?php echo $sum; ?></div>
        </div>

        <div class="stat-card highlight-purple">
            <div class="stat-label">Average Value</div>
            <div class="stat-value"><?php echo number_format($average, 2); ?></div>
        </div>
    </div>

    <div class="footer-note">
        <strong>Verification:</strong> Calculated using manual loop logic over <strong><?php echo $count; ?></strong> elements.
        (Cross-check: <code>max() = <?php echo max($numbers); ?></code>, <code>min() = <?php echo min($numbers); ?></code>, <code>array_sum() = <?php echo array_sum($numbers); ?></code>)
    </div>
</div>

</body>
</html>
