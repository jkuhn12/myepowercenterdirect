<?php
// ePowerCenterDirect - Cases List Page
require_once 'auth.php';
require_once 'data.php';
$siteName = "ePowerCenterDirect";
$currentYear = date('Y');

function priorityColor($p) {
    if ($p === 'High') return 'color:#cc0000;font-weight:bold;';
    if ($p === 'Medium') return 'color:#ff8800;font-weight:bold;';
    return 'color:#008800;';
}

function statusBadge($s) {
    if ($s === 'Open') return '<span class="status-badge status-open">Open</span>';
    if ($s === 'Closed') return '<span class="status-badge status-closed">Closed</span>';
    return '<span class="status-badge status-pending">Pending</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1024">
    <title>Customer Cases - <?php echo htmlspecialchars($siteName); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .cases-table { width: 100%; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; font-size: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .cases-table thead { background: linear-gradient(to bottom, #f0f0f0 0%, #d8d8d8 100%); }
        .cases-table th { padding: 10px 12px; text-align: left; font-weight: bold; color: #333; border-bottom: 1px solid #bbb; text-shadow: 0 1px 0 #fff; }
        .cases-table td { padding: 10px 12px; border-bottom: 1px solid #e0e0e0; background: #fff; }
        .cases-table tbody tr:hover td { background: #f0f7ff; }
        .status-badge { display: inline-block; padding: 1px 6px; border-radius: 8px; font-size: 10px; font-weight: bold; }
        .status-open { background: #ccffcc; color: #006600; border: 1px solid #88cc88; }
        .status-closed { background: #e0e0e0; color: #555; border: 1px solid #bbb; }
        .status-pending { background: #ffffcc; color: #886600; border: 1px solid #dddd66; }
        .case-link { color: #1a4a7a; text-decoration: underline; font-weight: bold; }
        .case-link:hover { color: #ff8800; }
        .filter-bar { margin-bottom: 15px; padding: 10px; background: #f0f0f0; border: 1px solid #ccc; border-radius: 4px; }
        .filter-bar span { font-size: 12px; color: #666; margin-right: 10px; }
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
                <li><a href="cases.php" class="active">Cases</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </div>

        <div id="main">
            <div class="content-box" style="width:100%">
                <h2>Customer Cases</h2>

                <?php if (!$isLoggedIn): ?>
                    <div style="max-width: 350px; margin: 60px auto;">
                        <div class="content-box" style="text-align:center">
                            <h3>Authentication Required</h3>
                            <p style="font-size:12px;color:#666;margin:10px 0;">You must be logged in to view customer cases.</p>
                            <?php if ($loginError): ?>
                                <div style="color:#cc0000;font-weight:bold;margin-bottom:10px;"><?php echo htmlspecialchars($loginError); ?></div>
                            <?php endif; ?>
                            <form method="post" action="cases.php">
                                <input type="text" name="username" placeholder="Username" style="width:100%;padding:8px;margin:6px 0;border:1px solid #bbb;border-radius:4px;font-size:13px;" required>
                                <input type="password" name="password" placeholder="Password" style="width:100%;padding:8px;margin:6px 0;border:1px solid #bbb;border-radius:4px;font-size:13px;" required>
                                <input type="submit" name="login" value="Login" style="padding:10px 24px;font-weight:bold;color:#fff;background:linear-gradient(to bottom,#4a90d9,#1e5a8e);border:1px solid #1a4a7a;border-radius:6px;cursor:pointer;font-size:13px;">
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="text-align:right;margin-bottom:10px;">
                        <form method="post" action="cases.php" style="display:inline">
                            <input type="submit" name="logout" value="Logout" style="padding:6px 14px;font-size:11px;color:#333;background:linear-gradient(to bottom,#f0f0f0,#d0d0d0);border:1px solid #bbb;border-radius:4px;cursor:pointer;">
                        </form>
                    </div>

                    <div class="filter-bar">
                        <span><strong>Total Cases:</strong> <?php echo count($customerCases); ?></span>
                        <span><strong>Open:</strong> <?php echo count(array_filter($customerCases, fn($c) => $c['status'] === 'Open')); ?></span>
                        <span><strong>Pending:</strong> <?php echo count(array_filter($customerCases, fn($c) => $c['status'] === 'Pending')); ?></span>
                        <span><strong>Closed:</strong> <?php echo count(array_filter($customerCases, fn($c) => $c['status'] === 'Closed')); ?></span>
                    </div>

                    <table class="cases-table" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Case #</th>
                                <th>Customer</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Date Opened</th>
                                <th>Assigned To</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customerCases as $case): ?>
                                <tr>
                                    <td><a href="case.php?id=<?php echo urlencode($case['id']); ?>" class="case-link">#<?php echo htmlspecialchars($case['id']); ?></a></td>
                                    <td><?php echo htmlspecialchars($case['customer']); ?></td>
                                    <td><?php echo htmlspecialchars($case['subject']); ?></td>
                                    <td><?php echo statusBadge($case['status']); ?></td>
                                    <td style="<?php echo priorityColor($case['priority']); ?>"><?php echo htmlspecialchars($case['priority']); ?></td>
                                    <td><?php echo htmlspecialchars($case['date']); ?></td>
                                    <td><?php echo htmlspecialchars($case['assigned']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
