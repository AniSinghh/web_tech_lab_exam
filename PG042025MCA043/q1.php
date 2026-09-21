<?php
/**
 * Question 1: Number Sign & Parity Checker
 * Student Roll/ID: PG042025MCA043
 */

$numInput = "";
$signResult = "";
$parityResult = "";
$signBadgeClass = "";
$parityBadgeClass = "";
$hasSubmitted = false;
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hasSubmitted = true;
    $rawInput = trim($_POST["num"] ?? "");
    $numInput = $rawInput;

    if ($rawInput === "" || !is_numeric($rawInput)) {
        $errorMessage = "Please enter a valid numeric value.";
    } else {
        $num = (float)$rawInput;

        // 1. Determine whether the number is Positive, Negative, or Zero
        if ($num > 0) {
            $signResult = "Positive (+)";
            $signBadgeClass = "badge-positive";
        } elseif ($num < 0) {
            $signResult = "Negative (-)";
            $signBadgeClass = "badge-negative";
        } else {
            $signResult = "Zero (0)";
            $signBadgeClass = "badge-zero";
        }

        // 2. Determine whether the number is Even or Odd (handle zero appropriately)
        if (floor($num) == $num) {
            $intVal = (int)$num;
            if ($intVal == 0) {
                $parityResult = "Even (Zero is mathematically an even number)";
                $parityBadgeClass = "badge-even";
            } elseif (abs($intVal) % 2 === 0) {
                $parityResult = "Even";
                $parityBadgeClass = "badge-even";
            } else {
                $parityResult = "Odd";
                $parityBadgeClass = "badge-odd";
            }
        } else {
            $parityResult = "Not Applicable (Even/Odd parity applies to integers; entered number has decimal fraction)";
            $parityBadgeClass = "badge-na";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question 1 - Number Checker | PG042025MCA043</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 24px;
        }
        .container {
            background: #ffffff;
            width: 100%;
            max-width: 580px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: #ffffff;
            padding: 24px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }
        input[type="number"] {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            font-size: 1rem;
            color: #1e293b;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input[type="number"]:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .btn-group {
            display: flex;
            gap: 12px;
        }
        button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
        }
        .btn-submit {
            background-color: #2563eb;
            color: #ffffff;
        }
        .btn-submit:hover {
            background-color: #1d4ed8;
        }
        .btn-reset {
            background-color: #e2e8f0;
            color: #475569;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .btn-reset:hover {
            background-color: #cbd5e1;
        }
        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        .result-box {
            margin-top: 28px;
            border-top: 2px dashed #e2e8f0;
            padding-top: 24px;
        }
        .result-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .result-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .result-table th, .result-table td {
            padding: 14px 16px;
            text-align: left;
            border: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }
        .result-table th {
            background-color: #f8fafc;
            color: #475569;
            width: 40%;
            font-weight: 600;
        }
        .result-table td {
            background-color: #ffffff;
            color: #1e293b;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .badge-positive {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-negative {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .badge-zero {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .badge-even {
            background-color: #ede9fe;
            color: #6d28d9;
        }
        .badge-odd {
            background-color: #fef3c7;
            color: #b45309;
        }
        .badge-na {
            background-color: #f1f5f9;
            color: #64748b;
        }
        .summary-banner {
            margin-top: 16px;
            padding: 14px 18px;
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
            color: #1e40af;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 14px;
            text-align: center;
            font-size: 0.82rem;
            color: #64748b;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Number Classification System</h1>
        <p>Lab Exam - Question 1 | Roll No: PG042025MCA043</p>
    </div>

    <div class="content">
        <?php if ($hasSubmitted && !empty($errorMessage)): ?>
            <div class="alert-error">
                <strong>Error:</strong> <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="num">Enter Any Number:</label>
                <input 
                    type="number" 
                    id="num" 
                    name="num" 
                    step="any" 
                    value="<?= htmlspecialchars($numInput) ?>" 
                    placeholder="e.g. -14, 0, 7, 28" 
                    required 
                    autofocus
                >
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-submit">Check Number</button>
                <a href="q1.php" class="btn-reset">Clear</a>
            </div>
        </form>

        <?php if ($hasSubmitted && empty($errorMessage)): ?>
            <div class="result-box">
                <div class="result-title">
                    <span>Evaluation Results</span>
                </div>

                <table class="result-table">
                    <tr>
                        <th>Entered Value</th>
                        <td><strong><?= htmlspecialchars($numInput) ?></strong></td>
                    </tr>
                    <tr>
                        <th>Sign Classification</th>
                        <td>
                            <span class="badge <?= $signBadgeClass ?>">
                                <?= htmlspecialchars($signResult) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Parity Classification</th>
                        <td>
                            <span class="badge <?= $parityBadgeClass ?>">
                                <?= htmlspecialchars($parityResult) ?>
                            </span>
                        </td>
                    </tr>
                </table>

                <div class="summary-banner">
                    <strong>Summary:</strong> The entered number <strong><?= htmlspecialchars($numInput) ?></strong> is 
                    <strong><?= htmlspecialchars($signResult) ?></strong> and 
                    <strong><?= htmlspecialchars($parityResult) ?></strong>.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        Web Technology Lab Exam &bull; PHP & HTML5 &bull; Candidate: PG042025MCA043
    </div>
</div>

</body>
</html>
