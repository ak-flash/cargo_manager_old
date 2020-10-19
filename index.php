<?php
session_start();

$views_dir = 'theme/';

// NEW - REPLACE ABOVE if (isset($_SESSION['user_id']) && isset($_COOKIE['authcode'])) {
if (isset($_SESSION['user_id'])) {
    $whitelist = array('main', 'orders', 'docs', 'clients', 'transp', 'adress', 'notify', 'reports', 'profile', 'ways', 'ways_cl');

    if ($_SESSION["group"] == 1 || $_SESSION["group"] == 2 || $_SESSION["group"] == 4) {
        array_push($whitelist, 'company', 'workers', 'pays', 'settings', 'cash', 'import', 'autopark');
    }

    if (isset($_GET['page']) && in_array($_GET['page'], $whitelist)) {

        $p = trim($_GET['page']);
        $p = strip_tags($p);
        $p = htmlspecialchars($p);

        $page = $views_dir . $p . '.tpl';

    } else $page = $views_dir . 'orders.tpl';

    if (!file_exists($page)) {
        // Page not found
        header('Location: 404.html');
        exit;
    } else {
        include($page);
    }


} else {
    header('Location: login.php');
    exit;
}