<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2016/11/18
 * Time: 上午1:06
 */

$char = array(0 => "０", 1 => '１', 2 => '２', 3 => '３', 4 => '４',
    5 => '５', 6 => '６', 7 => '７', 8 => '８', 9 => '９',
    '+' => '加', '-' => '减', '*' => '乘', '=' => '＝');

session_start();
$_SESSION['isPass'] = 0;

if (isset($_POST['get_captcha'])) {
    if ($_POST['get_captcha'] == 1) {
        $method = rand(1, 3);//1 -> +, 2 -> -, 3 -> *
        $_SESSION['setCapt'] = 1;
        if ($method == 1) {
            $a = rand(0, 9);
            $b = rand(0, 9);
            $c = rand(0, 9);
            $d = rand(0, 9);
            $x = $a * 10 + $b;
            $y = $c * 10 + $d;
            $_SESSION['answer'] = $x + $y;
            $ret = $char[$a] . $char[$b] . $char['+'] . $char[$c] . $char[$d] . $char['='];
            echo json_encode(array('captcha' => $ret));
        } else if ($method == 2) {
            $a = rand(0, 9);
            $b = rand(0, 9);
            $c = rand(0, 9);
            $d = rand(0, 9);
            $x = $a * 10 + $b;
            $y = $c * 10 + $d;
            $_SESSION['answer'] = $x - $y;
            $ret = $char[$a] . $char[$b] . $char['-'] . $char[$c] . $char[$d] . $char['='];
            echo json_encode(array('captcha' => $ret));
        } else if ($method == 3) {
            $a = rand(0, 1);
            $b = rand(0, 9);
            $c = rand(0, 1);
            $d = rand(0, 9);
            $x = $a * 10 + $b;
            $y = $c * 10 + $d;
            $_SESSION['answer'] = $x * $y;
            $ret = $char[$a] . $char[$b] . $char['*'] . $char[$c] . $char[$d] . $char['='];
            echo json_encode(array('captcha' => $ret));
        }
    }
}

if (isset($_POST['test_captcha'])) {
    if (isset($_SESSION['setCapt'])) {
        if ($_SESSION['setCapt'] == 1) {
            if ($_POST['test_captcha'] == $_SESSION['answer']) {
                $_SESSION['isPass'] = 1;
                $ret = json_encode(array("status" => true));
                echo $ret;
            } else {
                $ret = json_encode(array("status" => false));
                echo $ret;
            }
        } else {
            $ret = json_encode(array("status" => false));
            echo $ret;
        }
    } else {
        $ret = json_encode(array("status" => false));
        echo $ret;
    }
    unset($_SESSION['answer']);
}

//测试验证码是否正常运行，部署时应删除
/*
if (isset($_POST['status'])) {
    if ($_POST['status'] == 1) {
        $ret = json_encode(array('setCapt' => $_SESSION['setCapt'], 'answer' => $_SESSION['answer'],
            'isPass' => $_SESSION['isPass']));
        echo $ret;
    }
}
*/