<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2016/11/16
 * Time: 上午9:57
 */
require_once "config.php";
require_once "./captcha/lib/class.geetestlib.php";
$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
session_start();
$GtStatus = $GtSdk->pre_process();
$_SESSION['gtserver'] = $GtStatus;
echo $GtSdk->get_response_str();