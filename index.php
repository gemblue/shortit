<?php
require __DIR__ . '/lib/db.php';
session_start();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Simple auth helpers
function logged_in(){ return !empty($_SESSION['admin']); }
function require_login(){ if(!logged_in()){ header('Location: /admin/login'); exit; } }

// Routes
if (preg_match('#^/s/([A-Za-z0-9_-]+)$#', $path, $m)){
    $slug = $m[1];
    $link = find_link_by_slug($slug);
    if (!$link){ http_response_code(404); echo 'Not found'; exit; }
    // record click
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    inc_click($link['id'],$referer,$ua,$ip);
    header('Location: ' . $link['url'], true, 302);
    exit;
}

// Admin routes
if (strpos($path, '/admin') === 0){
    // login
    if ($path === '/admin/login'){
        $error = '';
        if ($method === 'POST'){
            $cfg = require __DIR__ . '/config.php';
            $u = $_POST['user'] ?? '';
            $p = $_POST['pass'] ?? '';
            if ($u === $cfg['admin_user'] && $p === $cfg['admin_pass']){
                $_SESSION['admin'] = $u;
                header('Location: /admin'); exit;
            } else {
                $error = 'Invalid credentials';
            }
        }
        include __DIR__ . '/views/login.php';
        exit;
    }

    if ($path === '/admin/logout'){
        session_destroy(); header('Location: /admin/login'); exit;
    }

    require_login();

    if ($path === '/admin' || $path === '/admin/'){
        $links = list_links();
        include __DIR__ . '/views/dashboard.php';
        exit;
    }

    if ($path === '/admin/create'){
        $msg = $error = '';
        $title = $url = $slug = '';
        if ($method === 'POST'){
            $title = trim($_POST['title'] ?? '');
            $url = trim($_POST['url'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            if (empty($url)) $error = 'URL is required';
            else {
                if (empty($slug)){
                    $slug = substr(bin2hex(random_bytes(4)),0,6);
                }
                // ensure unique
                $other = find_link_by_slug($slug);
                if ($other){
                    $error = 'Slug already used, choose another';
                } else {
                    create_link($slug, $url, $title ?: $url);
                    $msg = 'Created: /s/' . $slug;
                    $title = $url = $slug = '';
                }
            }
        }
        include __DIR__ . '/views/create.php';
        exit;
    }
    if ($path === '/admin/edit'){
        $msg = $error = '';
        $id = intval($_GET['link'] ?? $_POST['id'] ?? 0);
        $link = null;
        if ($id) {
            $links = list_links();
            foreach($links as $l) if($l['id']==$id) $link = $l;
        }
        if (!$link){
            http_response_code(404); echo 'Link not found'; exit;
        }
        $title = $link['title'];
        $url = $link['url'];
        $slug = $link['slug'];
        if ($method === 'POST'){
            $title = trim($_POST['title'] ?? '');
            $url = trim($_POST['url'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            if (empty($url)) $error = 'URL is required';
            else {
                $other = find_link_by_slug($slug);
                if ($other && $other['id'] != $id){
                    $error = 'Slug already used, choose another';
                } else {
                    update_link($id, $slug, $url, $title ?: $url);
                    $msg = 'Updated';
                }
            }
        }
        include __DIR__ . '/views/edit.php';
        exit;
    }

    if ($path === '/admin/stats'){
        $links = list_links();
        $selected_link = null; $clicks = [];
        if (!empty($_GET['link'])){
            $id = intval($_GET['link']);
            foreach($links as $l) if($l['id']==$id) $selected_link = $l;
            if ($selected_link) $clicks = get_clicks_for_link($selected_link['id'], 200);
        }
        include __DIR__ . '/views/stats.php';
        exit;
    }

    http_response_code(404); echo 'Admin page not found'; exit;
}

// Root: simple landing
if ($path === '/' || $path === ''){
    echo "<h2 style='font-family:system-ui, -apple-system; padding:2rem'>Shortit — ready. Admin: <a href='/admin/login'>/admin/login</a></h2>";
    exit;
}

// fallback 404
http_response_code(404); echo 'Not found';
