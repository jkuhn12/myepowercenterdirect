<?php
// ePowerCenterDirect - Terms of Service
$siteName = "ePowerCenterDirect";
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1024">
    <title>Terms of Service - <?php echo htmlspecialchars($siteName); ?></title>
    <link rel="stylesheet" href="styles.css">
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
                <li><a href="reports.php">Reports</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </div>

        <div id="main">
            <div class="content-box" style="width:100%">
                <h2>Terms of Service</h2>
                <p style="margin-bottom: 15px;">
                    By accessing and using the ePowerCenterDirect platform, you agree to be bound by the following terms and conditions.
                </p>
                <p style="margin-bottom: 15px;">
                    <strong>For all terms of service requests, inquiries, disputes, and legal matters, please refer all requests to Bob Wilke Jr LLC.</strong>
                </p>
                <p style="margin-bottom: 15px;">
                    Bob Wilke Jr LLC is the authorized representative for ePowerCenterDirect and manages all legal
                    and contractual matters relating to the use of this service.
                </p>
                <p style="margin-bottom: 15px;">
                    These terms are effective as of January 1, 2008 and govern all use of the ePowerCenterDirect platform and services.
                </p>
            </div>
        </div>

        <div id="footer">
            <p>&copy; <?php echo htmlspecialchars($currentYear); ?> ePowerCenterDirect Inc. All rights reserved. | <a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
            <p style="font-size: 10px; color: #999; margin-top: 5px;">Best viewed in Internet Explorer 7 or Firefox 3.0 at 1024x768</p>
        </div>
    </div>
</body>
</html>
