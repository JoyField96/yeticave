<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'function.php';
require_once 'models.php';

$goods = get_query_list_goods();
$cat_mass = get_query_categories();
$db = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password'=>'',
    'database'=>'yeticave'
];

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link,'utf8');


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

$page_content = include_template("404.php", [
    "cat_mass" => $cat_mass,
]);
$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "header" => $header,
    "content" => $page_content,
    "main_footer" =>$footer,



]);
//$page_content = include_template("lot-page.php", [
//
//    'goods'=>$goods,
//    'cat_mass'=>$cat_mass,
//    "lot" => $lot,
//    "user_name" => $user_name,
//    "error" => '',
//    'min_bets' => $min_bets
//
//]);



$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if ($id) {
    $lot = get_query_good ($id);
} else {
    print($layout_content);
    die();
};
if(!$lot) {
    print($layout_content);
    die();
}

$history = get_bets_history($link, $id);
if(!$history){
    $history = null;
    $current_price =$lot["start_price"];
} else{$current_price = max($lot["start_price"], $history[0]["price_bet"]);}


$min_bet = $current_price + $lot["step"];


$page_content = include_template("lot-page.php", [
    'cat_mass'=>$cat_mass,
    "header" => $header,
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "lot" => $lot,
    "current_price" => $current_price,
    "min_bet" => $min_bet,
    "id" => $id,
    "history" => $history
]);


$min_bets = take_min_bets($link,$id);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bet = filter_input(INPUT_POST, "cost", FILTER_VALIDATE_INT);

    if ($bet < $min_bet) {
        $error = "Ставка не может быть меньше $min_bet";
    }
    if (empty($bet)) {
        $error = "Ставка должна быть целым числом, болше ноля";
    }
    if ($error) {
        $page_content = include_template("lot-page.php", [
            'goods'=>$goods,
            'cat_mass'=>$cat_mass,
            "lot" => $lot,
            "error" => $error,
            "current_price" => $current_price,
            'min_bet' => $min_bet,
            "is_auth" => $is_auth,
            "user_name" => $user_name,
            "id" => $id,
            "history" => $history
        ]);

    } else {
        $res = add_bet_database($link, $bet, $_SESSION["id"], $id);
        header("Location: /lot.php?id=" .$id);
    }

}


$layout_content = include_template("layout.php", [
    "header" => $header,
    "title" => $lot['title'],
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "main_footer" =>$footer,
    'content' => $page_content
]);

print($layout_content);