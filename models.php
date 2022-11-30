<?php
$db = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password'=>'',
    'database'=>'yeticave'
];
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link,'utf8');

function get_query_list_goods(){
    $sql = 'SELECT lots.id, `date_creation`, `title`, `lot_description`, `img`, `start_price`, `date_finish`, `step`, `user_id`, `winner_id`, `category_id`, categories.name_category FROM `lots` JOIN categories on category_id = categories.id 
         ORDER BY `date_creation` DESC';
    $test = test_connetcion();
    $res= mysqli_query($test, $sql);
    return  $result = mysqli_fetch_all($res,MYSQLI_ASSOC);
    }

function get_query_good($id){
    $sql = 'SELECT lots.title, lots.start_price, lots.img, lots.date_finish, lots.lot_description, categories.name_category FROM lots
    JOIN categories ON lots.category_id=categories.id
    WHERE lots.id='.$id;
    $test = test_connetcion();
    $res= mysqli_query($test, $sql);
    return $result = mysqli_fetch_assoc($res);
}

function get_query_create_lot ($user_id) {
    return "INSERT INTO lots (title, category_id, lot_description, start_price, step, date_finish, img, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, $user_id);";
}
function get_lot(){
    $sql = "SELECT lots.title, lots.start_price, lots.img, lots.date_finish, lots.lot_description, categories.name_category FROM lots
    JOIN categories ON lots.category_id=categories.id";

    $test = test_connetcion();
    $res= mysqli_query($test, $sql);
    $result = mysqli_fetch_assoc($res);
    if(!$result) {
        http_response_code(404);
        die();
    } else {
        return  $result;
    }

}

function get_query_categories(){
    $sql = 'SELECT `id`, `character_code`, `name_category` FROM `categories`';
    $test = test_connetcion();
    $res = mysqli_query($test, $sql);
    return  $result = mysqli_fetch_all($res,MYSQLI_ASSOC);
}

function create_user ($email, $name,$hash_password,$contacts) {
    $mysqli = new mysqli("127.0.0.1", "root", "", "yeticave");
    if( $mysqli->query("INSERT INTO `users`(  `email`, `user_name`, `user_password`, `contacts`) VALUES ('$email','$name','$hash_password','$contacts')")){
        return $res = true;
    } else {
        return $res = false;
    }

}

function get_login($con, $email) {
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT id, email, user_name, user_password FROM users WHERE email = '$email'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $users_data= get_arrow($result);
            return $users_data;
        }
        $error = mysqli_error($con);
        return $error;
    }
}
function get_found_lots($link, $words, $limit, $offset) {
    $sql = "SELECT lots.id, lots.title, lots.start_price, lots.img, lots.date_finish, categories.name_category FROM lots
    JOIN categories ON lots.category_id=categories.id
    WHERE MATCH(title, lot_description) AGAINST(?) ORDER BY date_creation DESC LIMIT $limit OFFSET $offset;";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $words);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $goods = get_arrow($res);
        return $goods;
    }
    $error = mysqli_error($link);
    return $error;
}

function get_count_lots($link, $words) {
    $sql = "SELECT COUNT(*) as cnt FROM lots
    WHERE MATCH(title, lot_description) AGAINST(?);";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $words);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $count = mysqli_fetch_assoc($res)["cnt"];
        return $count;
    }
    $error = mysqli_error($link);
    return $error;
    }

?>