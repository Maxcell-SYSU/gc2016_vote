var vid = [
    [
        "Pump_it", 212109, 344380
    ]
];


var isfixed = false;
var addlist = false;

var vh = $(window).height(), vw = $(window).width();
var sh = $(document).height(),sw = $(document).width();
console.log(vh + "," + vw + "," + sh + "," + sw);

var x_axis = -1, y_axis= -1;

var chosen_id = -1;

var captcha;

var vote = function (validate) {
    if(chosen_id === -1) {
        alert('请选择视频');
        return;
    }
    $.ajax({
        url: "vote.php",
        type: "post",
        dataType: "json",
        data: {
            vote_for: vid[chosen_id][0],
            geetest_challenge: validate.geetest_challenge,
            geetest_validate: validate.geetest_validate,
            geetest_seccode: validate.geetest_seccode
        },
        success: function (data) {
            if (data && (data.status === "success")) {
                alert('投票成功');
            } else {
                alert('投票失败');
            }
        },
        error: function () {
            alert('服务器出错');
        }
        
    });
};

var vote_pc = function (e) {
    x_axis = e.pageX;
    y_axis = e.pageY;
    console.log(x_axis + "," + y_axis);

    captcha.show();
};

var vote_mob = function (e) {
    e.preventDefault();
    x_axis = e.changedTouches[0].pageX;
    y_axis = e.changedTouches[0].pageY;
    console.log(x_axis + "," + y_axis);

    captcha.show();
};

var chose_g = function (gid) {
    chosen_id = gid;
    console.log(gid);

    $("#b_video").attr("src", "http://www.bilibili.com/html/html5player.html?aid=" + vid[gid][1] + "&cid=" + vid[gid][2]);
};

var fix = function () {
    if (document.getElementById("main-bg").offsetWidth > 768) {

        if(document.getElementById("main-bg").offsetWidth / document.getElementById("main-bg").offsetHeight < 16 / 9) {
            $(".main-bg-fixed").css({
                    "height": document.getElementById("main-bg").offsetHeight + "px",
                    "width": document.getElementById("main-bg").offsetHeight * 16 / 9 + "px"
            });
        }
        else {
            $(".main-bg-fixed").css({
                    "height": document.getElementById("main-bg").offsetWidth * 9 / 16 + "px",
                    "width": document.getElementById("main-bg").offsetWidth + "px"
            });
        }
        $("#viewport").attr("content", "width=" + document.getElementById("main-bg").offsetWidth);
        console.log("width=" + document.getElementById("main-bg").offsetWidth);

        $(".mar-top").css({
            "margin-top": document.getElementById("main-bg").offsetHeight * 0.2 + "px"
        });

        $(".vbg").css({
            "height": document.getElementById("main-bg").offsetHeight * 0.65 + "px"
        });

        if(!addlist) {
            $("#vlist").append('<div class="list-group" id="listg"></div>');
            for (var i = 0; i < vid.length; ++i) {
                $("#listg").append('<a id="' + i + '" class="list-group-item list-group-item-warning" onclick="chose_g(this.id)">' + vid[i][0] + '</a>');
            }
            addlist = true;
        }

        isfixed = true;
    } else {
            $("#viewport").attr("content", "width=device-width");
            console.log(document.getElementById("v-video").offsetWidth);

            $("#v-video").css({
                "height": document.getElementById("v-video").offsetWidth * 0.5625 + "px"
            });

            if(!addlist) {
                $("#vlist").append('<select class="form-control" id="listg"></select>');
                for (var i = 0; i < vid.length; ++i) {
                    $("#listg").append('<option value="'+i+'">' + vid[i][0] + '</option>');
                }

                document.getElementById("listg").addEventListener("change", function() {
                    chose_g($("#listg option:selected").val());
                });
                addlist = true;
            }
    }

};

$(document).ready(function () {
    $('body').responsive({
        extraSmall: function () {
            fix();
        },
        small: function () {
            fix();
        },
        medium: function () {
            fix();
        },
        large: function () {
            fix();
        }
    });

});



var handlerPopup = function (captchaObj) {
    // 成功的回调
    captchaObj.onSuccess(function () {
        var validate = captchaObj.getValidate();
        vote(validate);
    });
    // 将验证码加到id为captcha的元素里
    document.getElementById("popup-submit").addEventListener("mousedown", vote_pc, false);
    document.getElementById("popup-submit").addEventListener("touchstart", vote_mob, false);
    captcha = captchaObj;
    chose_g(0);
    captchaObj.appendTo("#popup-captcha");
    // 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
};
// 验证开始需要向网站主后台获取id，challenge，success（是否启用failback）
$.ajax({
    url: "initcaptcha.php?type=pc&t=" + (new Date()).getTime(), // 加随机数防止缓存
    type: "get",
    dataType: "json",
    success: function (data) {
        // 使用initGeetest接口
        // 参数1：配置参数
        // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
        initGeetest({
            gt: data.gt,
            challenge: data.challenge,
            product: "popup", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
            offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
            // 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
        }, handlerPopup);
    },
    error: function () {
        alert("加载验证码失败，请刷新");
    }
});