<?php
// Simple SQLite helper
function get_db(){
    static $db = null;
    if ($db) return $db;
    $cfg = require __DIR__ . '/../config.php';
    $file = $cfg['db_file'];
    $dir = dirname($file);
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true) && !is_dir($dir)){
            error_log("[shortit] Failed to create directory $dir");
            throw new RuntimeException("Unable to create database directory: $dir");
        }
    }

    // check that PDO sqlite driver is available in this PHP build
    if (!in_array('sqlite', PDO::getAvailableDrivers())){
        $msg = "PDO sqlite driver not found; please install/enable php-sqlite3 for the PHP SAPI running your webserver. " .
               "CLI vs FPM may differ (check phpinfo).";
        error_log("[shortit] " . $msg);
        throw new RuntimeException($msg);
    }

    try {
        $db = new PDO('sqlite:' . $file);
    } catch (PDOException $e){
        // if it's a permission or file problem, fail early with message
        error_log("[shortit] PDO open error: " . $e->getMessage());
        throw $e;
    }
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    init_schema($db);
    return $db;
}

function init_schema($db){
    $db->exec("CREATE TABLE IF NOT EXISTS links (
        id INTEGER PRIMARY KEY,
        slug TEXT UNIQUE,
        url TEXT,
        title TEXT,
        clicks INTEGER DEFAULT 0,
        created_at TEXT
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS clicks (
        id INTEGER PRIMARY KEY,
        link_id INTEGER,
        referer TEXT,
        ua TEXT,
        ip TEXT,
        created_at TEXT
    )");
}

function find_link_by_slug($slug){
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM links WHERE slug = :slug LIMIT 1');
    $stmt->execute([':slug'=>$slug]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function create_link($slug, $url, $title){
    $db = get_db();
    $now = date('c');
    $stmt = $db->prepare('INSERT INTO links (slug,url,title,created_at) VALUES (:slug,:url,:title,:now)');
    $stmt->execute([':slug'=>$slug,':url'=>$url,':title'=>$title,':now'=>$now]);
    return $db->lastInsertId();
}

function update_link($id, $slug, $url, $title){
    $db = get_db();
    $stmt = $db->prepare('UPDATE links SET slug = :slug, url = :url, title = :title WHERE id = :id');
    $stmt->execute([':slug'=>$slug,':url'=>$url,':title'=>$title,':id'=>$id]);
}

function inc_click($link_id, $referer, $ua, $ip){
    $db = get_db();
    $stmt = $db->prepare('INSERT INTO clicks (link_id,referer,ua,ip,created_at) VALUES (:lid,:ref,:ua,:ip,:now)');
    $stmt->execute([':lid'=>$link_id,':ref'=>$referer,':ua'=>$ua,':ip'=>$ip,':now'=>date('c')]);
    $db->prepare('UPDATE links SET clicks = clicks + 1 WHERE id = :id')->execute([':id'=>$link_id]);
}

function list_links(){
    $db = get_db();
    $stmt = $db->query('SELECT * FROM links ORDER BY created_at DESC');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_clicks_for_link($link_id, $limit = 100){
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM clicks WHERE link_id = :id ORDER BY created_at DESC LIMIT :lim');
    $stmt->bindValue(':id', $link_id, PDO::PARAM_INT);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
