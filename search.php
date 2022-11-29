<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'models.php';
require_once 'function.php';



$goods = get_query_list_goods();
$cat_mass = get_query_categories();



$page_content = include_template("search-page.php", [
    'cat_mass'=>$cat_mass
]);


if($_SERVER['REQUEST_METHOD'] == 'GET'){

    $search = htmlspecialchars($_GET["search"]);


}

$header = include_template('header.php',
    [
        "is_auth" => $is_auth,
        "user_name" => $user_name
    ]);

$footer = include_template('footer.php',
    [
        'cat_mass'=>$cat_mass,
        'goods'=>$goods
    ]);
$layout_content = include_template("search-page.php", [
    "header" => $header,
    "content" => $page_content,
    "title" => "поиск",
    "main_footer" =>$footer,
    'cat_mass'=>$cat_mass

]);

print($layout_content);