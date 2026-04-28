<?php
// ePowerCenterDirect - Shared Authentication Module
$authCookieName = 'epcd_admin_auth';
$isLoggedIn = false;
$loginError = '';

// Handle logout
if (isset($_POST['logout'])) {
    setcookie($authCookieName, '', time() - 3600, '/');
    // Redirect to same page without POST data
    $redirectUrl = $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirectUrl);
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
        $redirectUrl = $_SERVER['REQUEST_URI'];
        header('Location: ' . $redirectUrl);
        exit;
    } else {
        $loginError = 'Invalid username or password.';
    }
}
?>
