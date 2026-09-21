<?php
/**
 * Question 2: Employee Salary Calculator
 * Student Roll/ID: PG042025MCA044
 */

$empName = "";
$basicSalary = "";
$hraPercent = "";
$daPercent = "";
$hraAmount = 0.0;
$daAmount = 0.0;
$grossSalary = 0.0;
$hasSubmitted = false;
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hasSubmitted = true;
    $empName = trim($_POST["emp_name"] ?? "");
    $rawBasic = trim($_POST["basic_salary"] ?? "");
    $rawHra = trim($_POST["hra_percent"] ?? "");
    $rawDa = trim($_POST["da_percent"] ?? "");

    // Validation
    if ($empName === "") {
        $errorMessage = "Please enter the Employee Name.";
    } elseif ($rawBasic === "" || !is_numeric($rawBasic) || (float)$rawBasic < 0) {
        $errorMessage = "Please enter a valid non-negative Basic Salary amount.";
    } elseif ($rawHra === "" || !is_numeric($rawHra) || (float)$rawHra < 0) {
        $errorMessage = "Please enter a valid non-negative HRA percentage.";
    } elseif ($rawDa === "" || !is_numeric($rawDa) || (float)$rawDa < 0) {
        $errorMessage = "Please enter a valid non-negative DA percentage.";
    } else {
        $basicSalary = (float)$rawBasic;
        $hraPercent = (float)$rawHra;
        $daPercent = (float)$rawDa;

        // Calculations
        // HRA = Basic Salary * (HRA% / 100)
        $hraAmount = $basicSalary * ($hraPercent / 100.0);

        // DA = Basic Salary * (DA% / 100)
        $daAmount = $basicSalary * ($daPercent / 100.0);

        // Gross Salary = Basic Salary + HRA + DA
        $grossSalary = $basicSalary + $hraAmount + $daAmount;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question 2 - Employee Salary Breakdown | PG042025MCA043</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 24px;
        }
        .container {
            background: #ffffff;
            width: 100%;
            max-width: 780px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #065f46 0%, #059669 100%);
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
        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 24px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group.full-width {
            grid-column: span 2;
        }
        label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #1e293b;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input[type="text"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2);
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
            background-color: #059669;
            color: #ffffff;
        }
        .btn-submit:hover {
            background-color: #047857;
        }
        .btn-reset {
            background-color: #e2e8f0;
            color: #475569;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
        }
        .btn-reset:hover {
            background-color: #cbd5e1;
        }
        .result-section {
            margin-top: 32px;
            border-top: 2px dashed #cbd5e1;
            padding-top: 28px;
        }
        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        /* Clean CSS Table Styling: borders, padding, zebra striping */
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            background: #ffffff;
            border: 1px solid #cbd5e1;
        }
        .salary-table th, 
        .salary-table td {
            border: 1px solid #cbd5e1;
            padding: 12px 16px;
            text-align: left;
        }
        .salary-table th {
            background-color: #065f46;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.82rem;
            letter-spacing: 0.5px;
        }
        /* Zebra striping */
        .salary-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .salary-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        .salary-table tbody tr:hover {
            background-color: #f1f5f9;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        /* Highlighted gross salary row */
        .salary-table tr.gross-salary-row,
        .salary-table tr.gross-salary-row td {
            background: #ecfdf5 !important;
            color: #065f46;
            font-weight: 700;
            font-size: 1.05rem;
            border-top: 2px solid #059669;
            border-bottom: 2px solid #059669;
        }
        .formula-card {
            background-color: #f0fdf4;
            border-left: 4px solid #059669;
            padding: 14px 18px;
            border-radius: 4px;
            font-size: 0.88rem;
            color: #166534;
            line-height: 1.6;
        }
        .formula-card code {
            background: #dcfce7;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: Consolas, monospace;
            font-weight: 600;
        }
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 14px;
            text-align: center;
            font-size: 0.82rem;
            color: #64748b;
        }
        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Employee Salary Calculator</h1>
        <p>Lab Exam - Question 2 | Roll No: PG042025MCA043</p>
    </div>

    <div class="content">
        <?php if ($hasSubmitted && !empty($errorMessage)): ?>
            <div class="alert-error">
                <strong>Error:</strong> <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="emp_name">Employee Name:</label>
                    <input 
                        type="text" 
                        id="emp_name" 
                        name="emp_name" 
                        value="<?= htmlspecialchars($empName) ?>" 
                        placeholder="e.g. Ramesh Kumar" 
                        required 
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="basic_salary">Basic Salary (₹):</label>
                    <input 
                        type="number" 
                        id="basic_salary" 
                        name="basic_salary" 
                        step="0.01" 
                        min="0" 
                        value="<?= htmlspecialchars($basicSalary !== '' ? (string)$basicSalary : '') ?>" 
                        placeholder="e.g. 50000" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="hra_percent">HRA (%):</label>
                    <input 
                        type="number" 
                        id="hra_percent" 
                        name="hra_percent" 
                        step="0.01" 
                        min="0" 
                        value="<?= htmlspecialchars($hraPercent !== '' ? (string)$hraPercent : '') ?>" 
                        placeholder="e.g. 20" 
                        required
                    >
                </div>

                <div class="form-group full-width">
                    <label for="da_percent">DA (%):</label>
                    <input 
                        type="number" 
                        id="da_percent" 
                        name="da_percent" 
                        step="0.01" 
                        min="0" 
                        value="<?= htmlspecialchars($daPercent !== '' ? (string)$daPercent : '') ?>" 
                        placeholder="e.g. 10" 
                        required
                    >
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-submit">Calculate Gross Salary</button>
                <a href="q2.php" class="btn-reset">Reset</a>
            </div>
        </form>

        <?php if ($hasSubmitted && empty($errorMessage)): ?>
            <div class="result-section">
                <div class="section-title">
                    <span>Salary Breakdown Statement</span>
                </div>

                <!-- Itemized Breakdown Table with Zebra Striping & Highlighted Gross Salary Row -->
                <div class="table-responsive">
                    <table class="salary-table">
                        <thead>
                            <tr>
                                <th>Component / Parameter</th>
                                <th class="text-center">Rate / Percentage</th>
                                <th class="text-right">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Employee Name</strong></td>
                                <td class="text-center">-</td>
                                <td class="text-right"><strong><?= htmlspecialchars($empName) ?></strong></td>
                            </tr>
                            <tr>
                                <td>Basic Salary</td>
                                <td class="text-center">Base Pay</td>
                                <td class="text-right"><?= number_format($basicSalary, 2) ?></td>
                            </tr>
                            <tr>
                                <td>House Rent Allowance (HRA)</td>
                                <td class="text-center"><?= number_format($hraPercent, 2) ?>%</td>
                                <td class="text-right">+ <?= number_format($hraAmount, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Dearness Allowance (DA)</td>
                                <td class="text-center"><?= number_format($daPercent, 2) ?>%</td>
                                <td class="text-right">+ <?= number_format($daAmount, 2) ?></td>
                            </tr>
                            <tr class="gross-salary-row">
                                <td><strong>Gross Salary</strong></td>
                                <td class="text-center"><strong>(Basic + HRA + DA)</strong></td>
                                <td class="text-right"><strong>₹ <?= number_format($grossSalary, 2) ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Comprehensive Summary Table matching exact requested columns -->
                <div class="section-title" style="font-size: 1.05rem; margin-top: 20px;">
                    <span>Salary Summary Table</span>
                </div>
                <div class="table-responsive">
                    <table class="salary-table">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th class="text-right">Basic Salary</th>
                                <th class="text-center">HRA (%)</th>
                                <th class="text-right">Calculated HRA</th>
                                <th class="text-center">DA (%)</th>
                                <th class="text-right">Calculated DA</th>
                                <th class="text-right">Gross Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="gross-salary-row">
                                <td><?= htmlspecialchars($empName) ?></td>
                                <td class="text-right">₹ <?= number_format($basicSalary, 2) ?></td>
                                <td class="text-center"><?= number_format($hraPercent, 2) ?>%</td>
                                <td class="text-right">₹ <?= number_format($hraAmount, 2) ?></td>
                                <td class="text-center"><?= number_format($daPercent, 2) ?>%</td>
                                <td class="text-right">₹ <?= number_format($daAmount, 2) ?></td>
                                <td class="text-right" style="color: #065f46; font-size: 1.05rem;">₹ <?= number_format($grossSalary, 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="formula-card">
                    <strong>Computation Reference:</strong><br>
                    &bull; HRA = Basic Salary &times; (HRA% &divide; 100) = <?= number_format($basicSalary, 2) ?> &times; (<?= number_format($hraPercent, 2) ?> &divide; 100) = <code>₹ <?= number_format($hraAmount, 2) ?></code><br>
                    &bull; DA = Basic Salary &times; (DA% &divide; 100) = <?= number_format($basicSalary, 2) ?> &times; (<?= number_format($daPercent, 2) ?> &divide; 100) = <code>₹ <?= number_format($daAmount, 2) ?></code><br>
                    &bull; Gross Salary = Basic + HRA + DA = <?= number_format($basicSalary, 2) ?> + <?= number_format($hraAmount, 2) ?> + <?= number_format($daAmount, 2) ?> = <code>₹ <?= number_format($grossSalary, 2) ?></code>
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
