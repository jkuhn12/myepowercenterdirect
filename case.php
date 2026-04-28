<?php
// ePowerCenterDirect - Case Detail Page
require_once 'auth.php';
$siteName = "ePowerCenterDirect";
$currentYear = date('Y');

// Fake customer cases data (must match cases.php)
$customerCases = [
    1001 => ['customer' => 'Acme Corporation', 'subject' => 'Billing discrepancy on Q3 invoice', 'status' => 'Open', 'priority' => 'High', 'date' => '2008-03-15', 'assigned' => 'Alice Johnson', 'description' => 'Customer reports that the Q3 invoice total does not match their internal records. Discrepancy appears to be related to promotional credits not being applied correctly. Awaiting finance team review.'],
    1002 => ['customer' => 'Globex Industries', 'subject' => 'Feature request: custom reporting module', 'status' => 'Pending', 'priority' => 'Medium', 'date' => '2008-03-18', 'assigned' => 'Bob Smith', 'description' => 'Globex has requested a custom reporting module that allows them to export quarterly sales data in a specific CSV format. This has been logged as a feature request for the next product cycle.'],
    1003 => ['customer' => 'Initech LLC', 'subject' => 'Login issues after password reset', 'status' => 'Closed', 'priority' => 'High', 'date' => '2008-02-22', 'assigned' => 'Carol White', 'description' => 'Users unable to log in after using the self-service password reset tool. Root cause identified: cached session cookies on client browsers. Resolution: advised users to clear cookies and use the new login link.'],
    1004 => ['customer' => 'Umbrella Corp', 'subject' => 'Data export timeout on large datasets', 'status' => 'Open', 'priority' => 'Medium', 'date' => '2008-04-02', 'assigned' => 'David Lee', 'description' => 'Exporting datasets larger than 50,000 records results in a server timeout. Engineering team is investigating batch processing options to handle larger exports without hitting the 30-second execution limit.'],
    1005 => ['customer' => 'Stark Enterprises', 'subject' => 'Integration with legacy ERP system', 'status' => 'Pending', 'priority' => 'Low', 'date' => '2008-04-05', 'assigned' => 'Eve Brown', 'description' => 'Stark Enterprises needs bidirectional sync with their 1998-era ERP system. The ERP supports only flat-file exchange via FTP. A custom adapter will need to be developed.'],
    1006 => ['customer' => 'Wayne Industries', 'subject' => 'Duplicate contact records merge request', 'status' => 'Open', 'priority' => 'Low', 'date' => '2008-04-08', 'assigned' => 'Alice Johnson', 'description' => 'Approximately 400 duplicate contact records were created during a bulk import. Customer has provided a mapping spreadsheet. Data cleanup scheduled for next maintenance window.'],
    1007 => ['customer' => 'Cyberdyne Systems', 'subject' => 'Scheduled backup failure notification', 'status' => 'Closed', 'priority' => 'High', 'date' => '2008-01-30', 'assigned' => 'Bob Smith', 'description' => 'Automated nightly backup job failed for three consecutive days. Issue traced to insufficient disk space on the backup volume. Old backups archived and job resumed successfully.'],
    1008 => ['customer' => 'Massive Dynamic', 'subject' => 'User permissions not propagating to sub-accounts', 'status' => 'Open', 'priority' => 'Medium', 'date' => '2008-04-10', 'assigned' => 'Carol White', 'description' => 'When a manager account permissions are updated, the changes are not reflected in their subordinate accounts. The permission inheritance logic appears to be skipping nested groups. Bug ticket #4421 opened.'],
];

$caseId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$case = isset($customerCases[$caseId]) ? $customerCases[$caseId] : null;

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
    <title><?php echo $case ? 'Case #' . htmlspecialchars($caseId) : 'Case Not Found'; ?> - <?php echo htmlspecialchars($siteName); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .detail-box { margin-top: 15px; }
        .detail-row { padding: 10px 0; border-bottom: 1px dotted #ccc; display: flex; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { width: 140px; color: #666; font-weight: bold; }
        .detail-value { flex: 1; color: #333; }
        .status-badge { display: inline-block; padding: 1px 6px; border-radius: 8px; font-size: 10px; font-weight: bold; }
        .status-open { background: #ccffcc; color: #006600; border: 1px solid #88cc88; }
        .status-closed { background: #e0e0e0; color: #555; border: 1px solid #bbb; }
        .status-pending { background: #ffffcc; color: #886600; border: 1px solid #dddd66; }
        .description-box { background: #fafafa; border: 1px solid #ddd; border-radius: 4px; padding: 15px; margin-top: 15px; line-height: 1.6; }
        .back-link { display: inline-block; margin-top: 15px; color: #1a4a7a; text-decoration: underline; font-weight: bold; font-size: 12px; }
        .back-link:hover { color: #ff8800; }
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
                <li><a href="#">Reports</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </div>

        <div id="main">
            <div class="content-box" style="width:100%">
                <h2>Case Details</h2>

                <?php if (!$isLoggedIn): ?>
                    <div style="max-width: 350px; margin: 60px auto;">
                        <div class="content-box" style="text-align:center">
                            <h3>Authentication Required</h3>
                            <p style="font-size:12px;color:#666;margin:10px 0;">You must be logged in to view case details.</p>
                            <?php if ($loginError): ?>
                                <div style="color:#cc0000;font-weight:bold;margin-bottom:10px;"><?php echo htmlspecialchars($loginError); ?></div>
                            <?php endif; ?>
                            <form method="post" action="case.php?id=<?php echo urlencode($caseId); ?>">
                                <input type="text" name="username" placeholder="Username" style="width:100%;padding:8px;margin:6px 0;border:1px solid #bbb;border-radius:4px;font-size:13px;" required>
                                <input type="password" name="password" placeholder="Password" style="width:100%;padding:8px;margin:6px 0;border:1px solid #bbb;border-radius:4px;font-size:13px;" required>
                                <input type="submit" name="login" value="Login" style="padding:10px 24px;font-weight:bold;color:#fff;background:linear-gradient(to bottom,#4a90d9,#1e5a8e);border:1px solid #1a4a7a;border-radius:6px;cursor:pointer;font-size:13px;">
                            </form>
                        </div>
                    </div>
                <?php elseif (!$case): ?>
                    <p style="color:#cc0000;font-weight:bold;">Case not found.</p>
                    <a href="cases.php" class="back-link">&larr; Back to Cases</a>
                <?php else: ?>
                    <div style="text-align:right;margin-bottom:10px;">
                        <form method="post" action="case.php?id=<?php echo urlencode($caseId); ?>" style="display:inline">
                            <input type="submit" name="logout" value="Logout" style="padding:6px 14px;font-size:11px;color:#333;background:linear-gradient(to bottom,#f0f0f0,#d0d0d0);border:1px solid #bbb;border-radius:4px;cursor:pointer;">
                        </form>
                    </div>

                    <div class="detail-box">
                        <div class="detail-row">
                            <div class="detail-label">Case Number:</div>
                            <div class="detail-value">#<?php echo htmlspecialchars($caseId); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Customer:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($case['customer']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Subject:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($case['subject']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status:</div>
                            <div class="detail-value"><?php echo statusBadge($case['status']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Priority:</div>
                            <div class="detail-value" style="<?php echo $case['priority'] === 'High' ? 'color:#cc0000;font-weight:bold;' : ($case['priority'] === 'Medium' ? 'color:#ff8800;font-weight:bold;' : 'color:#008800;'); ?>"><?php echo htmlspecialchars($case['priority']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date Opened:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($case['date']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Assigned To:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($case['assigned']); ?></div>
                        </div>
                    </div>

                    <h3 style="margin-top:20px;padding-bottom:6px;border-bottom:1px solid #e0e0e0;color:#1a4a7a;font-size:15px;">Description</h3>
                    <div class="description-box">
                        <?php echo nl2br(htmlspecialchars($case['description'])); ?>
                    </div>

                    <a href="cases.php" class="back-link">&larr; Back to Cases</a>
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
