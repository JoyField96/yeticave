<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'models.php';
require_once 'function.php';



$goods = get_query_list_goods();
$cat_mass = get_query_categories();



$page_content = include_template("sing-up.php", [
    'cat_mass'=>$cat_mass
]);



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ["email", "password", "name", "message"];
    $errors = [];

    $rules = [
        "email" => function($value) {
            return validate_email($value);
        },
        "password" => function($value) {
        return validate_password($value);
    },
    "name" => function($value) {
        return validate_name($value);
    },
    "message" => function($value) {
        return validate_message($value);
    }
    ];

    $user = filter_input_array(INPUT_POST,
        [
            "email"=>FILTER_SANITIZE_EMAIL,
            "password"=>FILTER_DEFAULT,
            "name"=>FILTER_DEFAULT,
            "message"=>FILTER_DEFAULT,
        ], true);

    foreach ($user as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $errors[$field] = $rule($value);
        }
        if (in_array($field, $required) && empty($value)) {
            $errors[$field] = "Поле $field нужно заполнить";
        }
    }

    $errors = array_filter($errors);


    if (count($errors)) {
        $page_content = include_template("sing-up.php", [
            'errors' => $errors,
            'cat_mass'=>$cat_mass,

        ]);
    } else {
        $hash_password = password_hash($user['password'], PASSWORD_DEFAULT);
        $res = create_user($user['email'],$user['name'],$hash_password,$user['message']);

        if ($res) {
            header("Location: login.php");
        } else {
            $error = mysqli_error($link);
        }
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
    "header" => $header,
    "content" => $page_content,
    "title" => "Добавление лота",
    "main_footer" =>$footer,

    'cat_mass'=>$cat_mass

]);

print($layout_content);