<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2016/11/16
 * Time: 上午8:15
 */
require_once "config.php";
require_once "data.php";
require_once "class.geetest.php";
require_once "localcaptcha.php";
session_start();
$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
if (isset($_POST['vote_for'])) {
    if ($_SESSION['gtserver'] == 1) {
        $captcha_passed = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'],
            $_POST['geetest_seccode']);               //验证码 SDK 要求的返回值
    } else {
        $captcha_passed = $GtSdk->fail_validate($_POST['geetest_challenge'], $_POST['geetest_validate'],
            $_POST['geetest_seccode']);
    }
    $isSuccess = false;
    $vote_for = 0;
    $sql="";
$captcha_passed=true;
    if ($captcha_passed) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];    //获取 User Agent 信息
        $remote_ip = $_SERVER['REMOTE_ADDR'];         //获取客户端 IP 地址
        $time = date("Y-m-d H:i:s");                  //获取当前时间戳
        $vote_for = $_POST['vote_for'];               //投票对象
        $screen_width = $_POST['screen_width'];       //客户端屏幕宽度
        $screen_height = $_POST['screen_height'];     //客户端屏幕高度
        $window_width = $_POST['window_width'];       //客户端窗口宽度
        $window_height = $_POST['window_height'];     //客户端窗口高度
        $click_x_axis = $_POST['click_x_axis'];       //投票按钮点击的横轴坐标，按百分比计，double 类型，下同
        $click_y_axis = $_POST['click_y_axis'];       //投票按钮点击的纵轴坐标
        $captcha_verified = $_SESSION['gtserver'];
        $db_mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_database);
        $sql =
            "insert into $db_table_list (vote_for, user_agent, remote_ip, time, screen_width,".
            " screen_height, windows_width, windows_height, click_x_axis, click_y_axis, verified)".
            " values($vote_for, '$user_agent', '$remote_ip', '$time', $screen_width, $screen_height,".
            "$window_width, $window_height, $click_x_axis, $click_y_axis, $captcha_verified)";
        $isSuccess = $db_mysqli->query($sql);
        //echo $isSuccess;
    }
    if ($isSuccess) {
        echo json_encode(array("state" => true, "vote" => $vote_for));
    } else {
        echo json_encode(array("state" => false));
    }

    $_SESSION['gtserver'] = 0;
} else if (isset($_POST['get_data'])) {
    if($_POST['get_data'] == 1) {
        $ret = json_encode($movies);
        echo $ret;
    }
}
?>
