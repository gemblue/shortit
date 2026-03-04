<?php
// Database helper (MySQL via mysqli)
function get_db(){
    static $db = null;
    if ($db) return $db;
    $cfg = require __DIR__ . '/../config.php';
    // connect via mysqli
    $host = $cfg['db_host'];
    $port = $cfg['db_port'];
    $user = $cfg['db_user'];
    $pass = $cfg['db_pass'];
    $name = $cfg['db_name'];
    $mysqli = new mysqli($host, $user, $pass, $name, $port);
    if ($mysqli->connect_error){
        error_log("[shortit] MySQL connect error: " . $mysqli->connect_error);
        throw new RuntimeException("MySQL connection failed: " . $mysqli->connect_error);
    }
    $mysqli->set_charset('utf8mb4');
    $db = $mysqli;
    return $db;
}

function find_link_by_slug($slug){
    $db = get_db();
    $slug_esc = $db->real_escape_string($slug);
    $res = $db->query("SELECT * FROM links WHERE slug = '$slug_esc' LIMIT 1");
    return $res ? $res->fetch_assoc() : null;
}

function create_link($slug, $url, $title, $ads = 0, $ad_banner = '', $ad_delay = 0){
    $db = get_db();
    $slug_e = $db->real_escape_string($slug);
    $url_e = $db->real_escape_string($url);
    $title_e = $db->real_escape_string($title);
    $ads_i = $ads ? 1 : 0;
    $banner_e = $db->real_escape_string($ad_banner);
    $delay_i = intval($ad_delay);
    $db->query("INSERT INTO links (slug,url,title,created_at,ads,ad_banner,ad_delay) VALUES ('$slug_e','$url_e','$title_e',NOW(),$ads_i,'$banner_e',$delay_i)");
    return $db->insert_id;
}

function update_link($id, $slug, $url, $title, $ads = 0, $ad_banner = '', $ad_delay = 0){
    $db = get_db();
    $id = intval($id);
    $slug_e = $db->real_escape_string($slug);
    $url_e = $db->real_escape_string($url);
    $title_e = $db->real_escape_string($title);
    $ads_i = $ads ? 1 : 0;
    $banner_e = $db->real_escape_string($ad_banner);
    $delay_i = intval($ad_delay);
    $db->query("UPDATE links SET slug='$slug_e', url='$url_e', title='$title_e', ads=$ads_i, ad_banner='$banner_e', ad_delay=$delay_i WHERE id=$id");
}

function inc_click($link_id, $referer, $ua, $ip){
    $db = get_db();
    $lid = intval($link_id);
    $ref = $db->real_escape_string($referer);
    $ua_e = $db->real_escape_string($ua);
    $ip_e = $db->real_escape_string($ip);
    $db->query("INSERT INTO clicks (link_id,referer,ua,ip,created_at) VALUES ($lid,'$ref','$ua_e','$ip_e',NOW())");
    $db->query("UPDATE links SET clicks = clicks + 1 WHERE id = $lid");
}

function list_links(){
    $db = get_db();
    $res = $db->query('SELECT * FROM links ORDER BY created_at DESC');
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function get_clicks_for_link($link_id, $limit = 100){
    $db = get_db();
    $id = intval($link_id);
    $lim = intval($limit);
    $res = $db->query("SELECT * FROM clicks WHERE link_id = $id ORDER BY created_at DESC LIMIT $lim");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}
