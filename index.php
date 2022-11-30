
<?php
require_once 'helpers.php';
require_once 'init.php';
require_once 'function.php';
require_once 'models.php';




$goods = get_query_list_goods();
$cat_mass = get_query_categories();



$header = include_template('header.php',
    [
        "is_auth" => $is_auth,
        "user_name" => $user_name
    ]);

$page_content = include_template('main.php',
    [
    'cat_mass'=>$cat_mass,
        'goods'=>$goods
    ]);


$footer = include_template('footer.php',
    [
        'cat_mass'=>$cat_mass,

    ]);

$layout_content = include_template('layout.php', [
    "header" =>$header,
    'content'=>$page_content,
    'goods'=>$goods,
    'cat_mass'=>$cat_mass,
    "main_footer" =>$footer,
    'title'=> 'YetiCave - главная страница'

]);
print($layout_content);
?>