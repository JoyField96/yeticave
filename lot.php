<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'function.php';
require_once 'models.php';

$goods = get_query_list_goods();
$cat_mass = get_query_categories();

$lot_id= filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
if($lot_id){
    $lot = get_query_good($lot_id);
} else {
    http_response_code(404);
    die();
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


$layout_content = include_template("lot-page.php", [
    "header" => $header,
    "title" => "Страница лота",
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "main_footer" =>$footer,
    'goods'=>$goods,
    'cat_mass'=>$cat_mass,
    "lot" => $lot
]);

print($layout_content);