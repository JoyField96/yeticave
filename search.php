<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'models.php';
require_once 'function.php';




$cat_mass = get_query_categories();


$search = htmlspecialchars($_GET["search"]);
if ($search) {
    $items_count = get_count_lots($link, $search);
    $cur_page = $_GET["page"] ?? 1;
    $page_items = 8;
    $pages_count = ceil($items_count / $page_items);
    $offset = ($page_items * $cur_page) - $page_items;
    $pages = range(1, $pages_count);

    $goods = get_found_lots($link, $search, $page_items, $offset);


}




$header = include_template('header.php',
    [
        "is_auth" => $is_auth,
        "user_name" => $user_name
    ]);
$footer = include_template('footer.php',
    [
        'cat_mass'=>$cat_mass,

    ]);
$page_content = include_template("search-page.php", [
    'cat_mass'=>$cat_mass,
    "search" => $search,
    "goods" => $goods,
    "header" => $header,
    "pages_count" => $pages_count,
    "pages" => $pages,
    "cur_page" => $cur_page,
    "main_footer" =>$footer

]);

$layout_content = include_template("layout.php", [
    "header" => '',
    "main_footer" => '',
    "content" => $page_content,
    'cat_mass'=>$cat_mass,
    "title" => "поиск",
    "search" => $search,

]);

print($layout_content);