<?php
function format_sum ($num){
    $num = ceil($num);
    $num = number_format($num, 0, '', ' ');

    return "$num ₽";
}

function goods_timer ($date)
    {
        date_default_timezone_set('Europe/Moscow');
        $final_date = date_create($date);
        $cur_date = date_create("now");
        $diff = date_diff($final_date, $cur_date);
        $format_diff = date_interval_format($diff, "%d %H %I");
        $arr = explode(" ", $format_diff);

        $hours = $arr[0] * 24 + $arr[1];
        $minutes = intval($arr[2]);
        $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
        $res[] = $hours;
        $res[] = $minutes;

        return $res;
    }

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return stmt Подготовленное выражение
 */
function db_get_prepare_stmt_version($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $key => $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);
        mysqli_stmt_bind_param(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает массив из объекта результата запроса
 * @param object $result_query mysqli Результат запроса к базе данных
 * @return array
 */
function get_arrow ($result_query) {
   $arrow = null;
    $row = mysqli_num_rows($result_query);
    if ($row === 1) {
        $arrow = mysqli_fetch_assoc($result_query);
    } else if ($row > 1) {
        $arrow = mysqli_fetch_all($result_query, MYSQLI_ASSOC);
    }

    return $arrow;
}

/**
 * Валидирует поле категории, если такой категории нет в списке
 * возвращает сообщение об этом
 * @param int $id категория, которую ввел пользователь в форму
 * @param array $allowed_list Список существующих категорий
 * @return string Текст сообщения об ошибке
 */
function validate_category ($id, $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
}
/**
 * Проверяет что содержимое поля является числом больше нуля
 * @param string $num число которое ввел пользователь в форму
 * @return string Текст сообщения об ошибке
 */
function validate_number ($num) {
    if (!empty($num)) {
        $num *= 1;
        if (is_int($num) && $num > 0) {
            return NULL;
        }
        return "Содержимое поля должно быть целым числом больше ноля";
    }
};

/**
 * Проверяет что дата окончания торгов не меньше одного дня
 * @param string $date дата которую ввел пользователь в форму
 * @return string Текст сообщения об ошибке
 */
function validate_date ($date) {
    if (is_date_valid($date)) {
        $now = date_create("now");
        $d = date_create($date);
        $diff = date_diff($d, $now);
        $interval = date_interval_format($diff, "%d");

        if ($interval < 1) {
            return "Дата должна быть больше текущей не менее чем на один день";
        };
    } else {
        return "Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»";
    }
};
/**
 * Валидации для страницы регистрации
 */
function validate_email($email){
    if(!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $email))
    {
        return "не корректный емейл";

    } else {
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $test = test_connetcion();
        $res = mysqli_query($test, $sql);
        $result = mysqli_fetch_assoc($res);
        if ($result != []) {
            return "Такой EMAIL занят";
        }
    }
};
function validate_password ($password){
    if(mb_strlen($password)<5 || mb_strlen($password)>12){
        return "длина меньше 5ти или больше 12";
    }
};

function validate_name ($name){
    if(mb_strlen($name)<5 || mb_strlen($name)>12){
        return "длина меньше 5ти или больше 12";
    }
};

    function validate_message ($message){
        if(mb_strlen($message)<5 || mb_strlen($message)>100) {
            return "длина меньше 5ти или больше 100";
        }
    };

function chek_mail ($email){
    if(empty($email) || !preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $email))
    {
        return "пустоe поле или неверный email";
    }
        $sql = "SELECT `email` FROM `users` WHERE `email` = '$email'";
        $test = test_connetcion();
        $res = mysqli_query($test, $sql);
        $result = mysqli_fetch_assoc($res);
        if ($result['email'] != $email) {
            return "Такой EMAIL не зарегестрирован";
        }
    return true;
};

function chek_password ($password,$email){
    if(empty($password) || mb_strlen($password)<5)
    {
        return "пустоe поле или короткий пароль";
    }

       // $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "SELECT `user_password` FROM `users` WHERE `email` = '$email'";
    $test = test_connetcion();
    $res = mysqli_query($test, $sql);
        $result = mysqli_fetch_assoc($res);
       // var_dump($result);
    if (!password_verify($password, $result['user_password'])) {
        return 'Пароль неправильный!';
    }
    return true;

};
//if ( $result['user_password'] != $hash_password) {
//    return "Такого пароля нет";
//}

function get_time_left ($date) {
    date_default_timezone_set('Europe/Moscow');
    $final_date = date_create($date);
    $cur_date = date_create("now");
    if ($cur_date >= $final_date) {
        $res = ["00", "00"];
        return $res;
    }
    $diff = date_diff($final_date, $cur_date);
    $format_diff = date_interval_format($diff, "%d %H %I");
    $arr = explode(" ", $format_diff);

    $hours = $arr[0] * 24 + $arr[1];
    $minutes = intval($arr[2]);
    $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
    $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

    $res[] = $hours;
    $res[] = $minutes;

    return $res;
}
?>