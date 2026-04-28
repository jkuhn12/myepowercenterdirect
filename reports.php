<?php
// ePowerCenterDirect - Reports Page
require_once 'auth.php';
require_once 'data.php';
$siteName = "ePowerCenterDirect";
$currentYear = date('Y');

// Handle CSV download
if ($isLoggedIn && isset($_GET['download']) && $_GET['download'] === 'cases') {
    $filename = 'cases_report_' . date('Ymd') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $out = fopen('php://output', 'w');
    fputcsv($out, ['Case ID', 'Customer', 'Subject', 'Status', 'Priority', 'Date Opened', 'Assigned To']);
    foreach ($customerCases as $case) {
        fputcsv($out, [
            $case['id'],
            $case['customer'],
            $case['subject'],
            $case['status'],
            $case['priority'],
            $case['date'],
            $case['assigned']
        ]);
    }
    fclose($out);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1024">
    <title>Reports - <?php echo htmlspecialchars($siteName); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .report-card { margin-bottom: 20px; }
        .report-card .content-box { min-height: 120px; }
        .report-meta { font-size: 12px; color: #666; margin: 8px 0; }
        .download-btn {
            display: inline-block;
            padding: 12px 28px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(to bottom, #4a90d9 0%, #1e5a8e 100%);
            border: 1px solid #1a4a7a;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .download-btn:hover {
            background: linear-gradient(to bottom, #5aa0e9 0%, #2e6a9e 100%);
        }
        .download-btn:active {
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }
        .preview-table { width: 100%; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; font-size: 11px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 10px; }
        .preview-table thead { background: linear-gradient(to bottom, #f0f0f0 0%, #d8d8d8 100%); }
        .preview-table th { padding: 6px 8px; text-align: left; font-weight: bold; color: #333; border-bottom: 1px solid #bbb; text-shadow: 0 1px 0 #fff; font-size: 10px; }
        .preview-table td { padding: 6px 8px; border-bottom: 1px solid #e0e0e0; background: #fff; }
        .preview-table tbody tr:hover td { background: #f0f7ff; }
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <div class="logo">
                <h1>ePowerCenter<span style="color:#ff8800">Direct</span></h1>
                <span class="beta-badge">BETA</span>
            </div>
            <div class="tagline">Enterprise Customer Relationship Management Simplified</div>
        </div>

        <div id="nav">
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="cases.php">Cases</a></li>
                <li><a href="reports.php" class="active">Reports</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </div>

        <div id="main">
            <div class="content-box" style="width:100%">
                <h2>Reports</h2>

                <?php if (!$isLoggedIn): ?>
                    <div style="max-width: 350px; margin: 60px auto;">
                        <div class="content-box" style="text-align:center">
                            <h3>Authentication Required</h3>
                            <p style="font-size:12px;color:#666;margin:10px 0;">You must be logged in to generate reports.</p>
                            <?php if ($loginError): ?>
                                <div style="color:#cc0000;font-weight:bold;margin-bottom:10px;"><?php echo htmlspecialchars($loginError); ?></div>
                            <?php endif; ?>
                            <form method="post" action="reports.php">
                                <input type="text" name="username" placeholder="Username" style="width:100%;padding:8px;margin:6px 0;border:1px solid #bbb;border-radius:4px;font-size:13px;" required>
                                <input type="password" name="password" placeholder="Password" style="width:100%;padding:8px;margin:6px 0;border:1px solid #bbb;border-radius:4px;font-size:13px;" required>
                                <input type="submit" name="login" value="Login" style="padding:10px 24px;font-weight:bold;color:#fff;background:linear-gradient(to bottom,#4a90d9,#1e5a8e);border:1px solid #1a4a7a;border-radius:6px;cursor:pointer;font-size:13px;">
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="text-align:right;margin-bottom:10px;">
                        <form method="post" action="reports.php" style="display:inline">
                            <input type="submit" name="logout" value="Logout" style="padding:6px 14px;font-size:11px;color:#333;background:linear-gradient(to bottom,#f0f0f0,#d0d0d0);border:1px solid #bbb;border-radius:4px;cursor:pointer;">
                        </form>
                    </div>

                    <div class="report-card">
                        <div class="content-box">
                            <h3>Customer Cases Report</h3>
                            <p class="report-meta">
                                Generates a CSV export of all customer cases including case ID, customer name, subject, status, priority, date opened, and assigned representative.
                            </p>
                            <p class="report-meta">
                                <strong>Total Records:</strong> <?php echo count($customerCases); ?> &nbsp;|&nbsp;
                                <strong>Generated:</strong> <?php echo date('Y-m-d H:i:s'); ?>
                            </p>
                            <a href="reports.php?download=cases" class="download-btn">&#9660; Download Cases CSV</a>

                            <h4 style="margin-top:20px;padding-bottom:6px;border-bottom:1px solid #e0e0e0;color:#1a4a7a;font-size:13px;">Preview</h4>
                            <table class="preview-table" cellpadding="0" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Case ID</th>
                                        <th>Customer</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Date</th>
                                        <th>Assigned</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customerCases as $case): ?>
                                        <tr>
                                            <td>#<?php echo htmlspecialchars($case['id']); ?></td>
                                            <td><?php echo htmlspecialchars($case['customer']); ?></td>
                                            <td><?php echo htmlspecialchars($case['subject']); ?></td>
                                            <td><?php echo htmlspecialchars($case['status']); ?></td>
                                            <td><?php echo htmlspecialchars($case['priority']); ?></td>
                                            <td><?php echo htmlspecialchars($case['date']); ?></td>
                                            <td><?php echo htmlspecialchars($case['assigned']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="footer">
            <p>&copy; <?php echo htmlspecialchars($currentYear); ?> ePowerCenterDirect Inc. All rights reserved. | <a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
            <p style="font-size: 10px; color: #999; margin-top: 5px;">Best viewed in Internet Explorer 7 or Firefox 3.0 at 1024x768</p>
        </div>
    </div>
</body>
</html>
