<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2016/11/18
 * Time: 上午8:52
 */
?>
<html>
<head>
    <meta http-equiv="Content-Type" charset="UTF-8">
    <title>Maxcell 广创 2016 投票</title>
    <script src="./assets/js/jquery-2.1.1.min.js"></script>
    <script>
        function getCapt() {
            $.ajax({
                type: 'post',
                url: 'localcaptcha.php',
                data: {
                    'get_captcha': 1
                },
                success: function (msg) {
                    var ret = JSON.parse(msg);
                    $("#captcha").html(ret['captcha']);
                    console.log(ret['captcha']);
                }
            });
        }

        function testCapt() {
            var cap = $("#captcha_text").val();
            console.log(cap);
            $.ajax({
                type: 'post',
                url: 'localcaptcha.php',
                data: {
                    'test_captcha': cap
                },
                success: function (msg) {
                    var ret = JSON.parse(msg);
                    if (ret['status']) {
                        $("#msg").html("pass");
                    } else {
                        $("#msg").html("fail");
                    }
                }
            });

            $.ajax({
                type: 'post',
                url: 'localcaptcha.php',
                data: {
                    'status': 1
                },
                success: function (msg) {
                    console.log(msg);
                }
            });
        }
    </script>
</head>
<body>
<div>
    <span id="captcha"></span>
    <input type="text" id="captcha_text">
    <button id="get_captcha" onclick="getCapt()">get</button>
    <button id="test_captcha" onclick="testCapt()">test</button>
    <span id="msg"></span>
</div>
<div>
    <iframe height="600" width="1000" src="http://www.bilibili.com/html/html5player.html?aid=212109&cid=344380"></iframe>
</div>
</body>