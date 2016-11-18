<?php
//废弃！！！！废弃！！！废弃！！！
//废弃！！！！废弃！！！废弃！！！
//废弃！！！！废弃！！！废弃！！！
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2016/11/16
 * Time: 上午10:03
 */
require_once "config.php";
require_once "./captcha/lib/class.geetestlib.php";
session_start();
$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
//$user_id = $_SESSION['user_id'];
if ($_SESSION['gtserver'] == 1) {
    $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode']);
    if ($result) {
        echo 'Yes!';
    } else {
        echo 'No';
    }
} else {
    if ($GtSdk->fail_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'])) {
        echo "yes";
    } else {
        echo "no";
    }
}