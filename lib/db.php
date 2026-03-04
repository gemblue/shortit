<?php
// Simple SQLite helper
function get_db(){
    static $db = null;
    if ($db) return $db;
    $cfg = require __DIR__ . '/../config.php';
    $file = $cfg['db_file'];
    $dir = dirname($file);
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $db = new PDO('sqlite:' . $file);
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
