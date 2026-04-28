<?php
// ePowerCenterDirect - Admin Panel (Harmless Demo)
$siteName = "ePowerCenterDirect";
$currentYear = date('Y');
$authCookieName = 'epcd_admin_auth';
$isLoggedIn = false;
$error = '';
$broadcastMessage = '';

// Fake harmless user data
$users = [
    ['id' => 101, 'name' => 'Alice Johnson', 'role' => 'Sales Rep', 'status' => 'Active'],
    ['id' => 102, 'name' => 'Bob Smith', 'role' => 'Manager', 'status' => 'Active'],
    ['id' => 103, 'name' => 'Carol White', 'role' => 'Support', 'status' => 'Away'],
    ['id' => 104, 'name' => 'David Lee', 'role' => 'Sales Rep', 'status' => 'Active'],
    ['id' => 105, 'name' => 'Eve Brown', 'role' => 'Admin', 'status' => 'Active'],
];

// Handle logout
if (isset($_POST['logout'])) {
    setcookie($authCookieName, '', time() - 3600, '/');
    header('Location: admin.php');
    exit;
}

// Check auth cookie
if (isset($_COOKIE[$authCookieName]) && $_COOKIE[$authCookieName] === 'dcm_authenticated') {
    $isLoggedIn = true;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user = isset($_POST['username']) ? $_POST['username'] : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';
    if ($user === 'dcm' && $pass === 'dcm') {
        setcookie($authCookieName, 'dcm_authenticated', time() + 3600, '/');
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}

// Handle harmless broadcast form
if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['broadcast'])) {
    $broadcastMessage = isset($_POST['message']) ? $_POST['message'] : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1024">
    <title>Admin Panel - <?php echo htmlspecialchars($siteName); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-box { max-width: 350px; margin: 60px auto; }
        .login-box .content-box { text-align: center; }
        .login-box input[type="text"], .login-box input[type="password"] {
            width: 100%; padding: 8px; margin: 8px 0; border: 1px solid #bbb; border-radius: 4px; font-size: 13px;
        }
        .login-box input[type="submit"] {
            padding: 10px 24px; font-weight: bold; color: #fff;
            background: linear-gradient(to bottom, #4a90d9, #1e5a8e);
            border: 1px solid #1a4a7a; border-radius: 6px; cursor: pointer; font-size: 13px;
        }
        .login-box input[type="submit"]:hover { background: linear-gradient(to bottom, #5aa0e9, #2e6a9e); }
        .error { color: #cc0000; font-weight: bold; margin-bottom: 10px; }
        .admin-grid { display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap; }
        .admin-card { flex: 1; min-width: 280px; }
        .admin-card .content-box { min-height: 180px; }
        .info-row { padding: 6px 0; border-bottom: 1px dotted #ccc; display: flex; justify-content: space-between; }
        .info-row:last-child { border-bottom: none; }
        .label { color: #666; }
        .value { font-weight: bold; color: #1a4a7a; }
        .broadcast-box { margin-top: 15px; }
        .broadcast-box textarea { width: 100%; height: 80px; padding: 8px; border: 1px solid #bbb; border-radius: 4px; font-family: Arial, sans-serif; font-size: 12px; }
        .broadcast-box input[type="submit"] { margin-top: 8px; padding: 8px 18px; font-weight: bold; color: #fff; background: linear-gradient(to bottom, #ff8800, #cc6600); border: 1px solid #aa5500; border-radius: 6px; cursor: pointer; font-size: 12px; }
        .broadcast-box input[type="submit"]:hover { background: linear-gradient(to bottom, #ff9933, #dd7722); }
        .message-display { background: #ffffcc; border: 1px solid #dddd66; padding: 12px; margin-top: 12px; border-radius: 4px; font-size: 12px; }
        .logout-form { text-align: right; margin-bottom: 10px; }
        .logout-form input[type="submit"] { padding: 6px 14px; font-size: 11px; color: #333; background: linear-gradient(to bottom, #f0f0f0, #d0d0d0); border: 1px solid #bbb; border-radius: 4px; cursor: pointer; }
        .user-table { width: 100%; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; font-size: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 10px; }
        .user-table thead { background: linear-gradient(to bottom, #f0f0f0 0%, #d8d8d8 100%); }
        .user-table th { padding: 8px 10px; text-align: left; font-weight: bold; color: #333; border-bottom: 1px solid #bbb; text-shadow: 0 1px 0 #fff; }
        .user-table td { padding: 8px 10px; border-bottom: 1px solid #e0e0e0; background: #fff; }
        .user-table tbody tr:hover td { background: #f0f7ff; }
        .status-badge { display: inline-block; padding: 1px 6px; border-radius: 8px; font-size: 10px; font-weight: bold; }
        .status-active { background: #ccffcc; color: #006600; border: 1px solid #88cc88; }
        .status-away { background: #ffffcc; color: #886600; border: 1px solid #dddd66; }
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
                <li><a href="#">Contacts</a></li>
                <li><a href="#">Leads</a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="admin.php" class="active">Admin</a></li>
            </ul>
        </div>

        <div id="main">
            <div class="content-box" style="width:100%">
                <h2>Administrator Panel</h2>

                <?php if (!$isLoggedIn): ?>
                    <div class="login-box">
                        <div class="content-box">
                            <h3>Secure Login Required</h3>
                            <?php if ($error): ?>
                                <div class="error"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>
                            <form method="post" action="admin.php">
                                <input type="text" name="username" placeholder="Username" required>
                                <input type="password" name="password" placeholder="Password" required>
                                <input type="submit" name="login" value="Login">
                            </form>
                            <p style="margin-top:15px;font-size:11px;color:#999;">Authorized personnel only.<br>Default: dcm / dcm</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="logout-form">
                        <form method="post" action="admin.php">
                            <input type="submit" name="logout" value="Logout">
                        </form>
                    </div>

                    <div class="admin-grid">
                        <div class="admin-card">
                            <div class="content-box">
                                <h3>System Overview</h3>
                                <div class="info-row">
                                    <span class="label">PHP Version:</span>
                                    <span class="value"><?php echo htmlspecialchars(PHP_VERSION); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Server Time:</span>
                                    <span class="value"><?php echo htmlspecialchars(date('Y-m-d H:i:s')); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Operating System:</span>
                                    <span class="value"><?php echo htmlspecialchars(PHP_OS); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">System Status:</span>
                                    <span class="value" style="color:#008800">Online</span>
                                </div>
                            </div>
                        </div>

                        <div class="admin-card">
                            <div class="content-box">
                                <h3>Broadcast Console</h3>
                                <p style="font-size:11px;color:#666;margin-bottom:10px;">Type a message below to preview it. This does not send or store anything.</p>
                                <form method="post" action="admin.php" class="broadcast-box">
                                    <textarea name="message" placeholder="Enter a harmless message..."><?php echo htmlspecialchars($broadcastMessage); ?></textarea>
                                    <input type="submit" name="broadcast" value="Preview Message">
                                </form>
                                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['broadcast'])): ?>
                                    <div class="message-display">
                                        <strong>Preview:</strong><br>
                                        <?php echo nl2br(htmlspecialchars($broadcastMessage)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="content-box" style="margin-top: 20px;">
                        <h3>User Management</h3>
                        <p style="font-size:11px;color:#666;margin-bottom:10px;">View-only list of CRM users. No modifications permitted.</p>
                        <table class="user-table" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($u['id']); ?></td>
                                        <td><?php echo htmlspecialchars($u['name']); ?></td>
                                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower(htmlspecialchars($u['status'])); ?>">
                                                <?php echo htmlspecialchars($u['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="footer">
            <p>&copy; <?php echo htmlspecialchars($currentYear); ?> ePowerCenterDirect Inc. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
            <p style="font-size: 10px; color: #999; margin-top: 5px;">Best viewed in Internet Explorer 7 or Firefox 3.0 at 1024x768</p>
        </div>
    </div>
</body>
</html>
