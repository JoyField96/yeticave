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
    $sql = 'SELECT lots.title, lots.start_price, lots.img, lots.date_finish, lots.lot_description, lots.step, lots.user_id, categories.name_category FROM lots
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

function make_bets ($link,$cost,$lot_id,$user_id){
    $sql = "INSERT INTO `bets`( `price_bet`, `user_id`, `lot_id`) VALUES (?,?,?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $cost,$lot_id,$user_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $bets = get_arrow($res);
        return $bets;
    }
    $error = mysqli_error($link);
    return $error;
}

function take_min_bets ($link, $id){

    $sql = "SELECT `price_bet` FROM `bets` WHERE `lot_id` = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $min_bets = get_arrow($res);
        return $min_bets;
    }
    $error = mysqli_error($link);
    return $error;

}
function get_bets_history ($link, $id) {
    if (!$link) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT users.user_name, bets.price_bet, DATE_FORMAT(date_bet, '%d.%m.%y %H:%i') AS date_bet
        FROM bets
        JOIN lots ON bets.lot_id=lots.id
        JOIN users ON bets.user_id=users.id
        WHERE lots.id= '$id'
        ORDER BY bets.date_bet DESC LIMIT 10;";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $list_bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $list_bets;
        }
        $error = mysqli_error($link);
        return $error;
    }
}

/**
 * Записывает в БД сделанную ставку
 * @param $link mysqli Ресурс соединения
 * @param int $sum Сумма ставки
 * @param int $user_id ID пользователя
 * @param int $lot_id ID лота
 * @return bool $res Возвращает true в случае успешной записи
 */
function add_bet_database($link, $sum, $user_id, $lot_id) {
    $sql = "INSERT INTO bets (date_bet, price_bet, user_id, lot_id) VALUE (NOW(), ?, $user_id, $lot_id);";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $sum);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        return $res;
    }
    $error = mysqli_error($link);
    return $error;
}

function get_bets($link, $id) {
    if (!$link) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT DATE_FORMAT(bets.date_bet, '%d.%m.%y %H:%i') AS date_bet, bets.price_bet, lots.title, lots.lot_description, lots.img, lots.date_finish, lots.id, categories.name_category, lots.winner_id, users.contacts
        FROM bets
        JOIN lots ON bets.lot_id=lots.id
        JOIN users ON bets.user_id=users.id
        JOIN categories ON lots.category_id=categories.id
        WHERE bets.user_id='$id'
        ORDER BY bets.date_bet DESC;";

        $result = mysqli_query($link, $sql);
        if ($result) {
            $list_bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $list_bets;
        }
        $error = mysqli_error($link);
        return $error;
    }
}
?>