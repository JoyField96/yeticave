<?php
session_start();

if($is_auth = isset($_SESSION["name"])){

    $user_name = $_SESSION["name"];

} else {
    $user_name = '';
}
//$user_name = isset($_SESSION["name"]);

function test_connetcion(){
    $db = [
        'host' => '127.0.0.1',
        'user' => 'root',
        'password'=>'',
        'database'=>'yeticave'
    ];

    $link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
    mysqli_set_charset($link,'utf8');

    if(!$link){
        $error = mysqli_connect_error();
       return $content = include_template('error.php', ['error'=>$error]);
    }else{
       return $link;
    }
}
$db = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password'=>'',
    'database'=>'yeticave'
];
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link,'utf8');
require_once ('vendor/autoload.php');
?>