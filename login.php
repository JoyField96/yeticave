<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'models.php';
require_once 'function.php';

$db = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password'=>'',
    'database'=>'yeticave'
];
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link,'utf8');

$goods = get_query_list_goods();
$cat_mass = get_query_categories();


$page_content = include_template("log-in.php", [
    'cat_mass'=>$cat_mass
]);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = [];
    $user = filter_input_array(INPUT_POST,
        [
            "email"=>FILTER_SANITIZE_EMAIL,
            "password"=>FILTER_DEFAULT
        ], true);

    $chek_mail = chek_mail($user['email']);
    $chek_pass = chek_password($user['password'],$user['email']);

    if($chek_mail !== true  || $chek_pass !== true){
        $page_content = include_template("log-in.php", [
            'chek_mail' => $chek_mail,
            'chek_pass' => $chek_pass,
            'cat_mass'=>$cat_mass

        ]);
    }else {
        $users_data = get_login($link,$user['email']);
        $issession = session_start();
        $_SESSION['name'] = $users_data["user_name"];
        $_SESSION['id'] = $users_data["id"];

        header("Location: /index.php");

    }


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
$layout_content = include_template("layout.php", [
    "header" =>$header,
    "content" => $page_content,
    "title" => "регистрация",
    "main_footer" =>$footer,

    'cat_mass'=>$cat_mass

]);

print($layout_content);