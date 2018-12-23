/**
 * admin 模块 seajs 配置文档
 * Created by yangjian on 2017/7/27.
 */
;(function () {

    var getCookie = function (name) {
        var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
        if (arr != null) {
            return unescape(arr[2]);
        }
        return null;
    };
    var version = "1.0.0";
    /* Local variable */
    var debug = /*getCookie("debugFlag") ? true : false*/true;

    var config = {
        //base path
        base: '/static/',
        vars: {
            "app": "admin/js/app",
        },
        //alias(别名)
        alias: {
            "template" : "common/js/artTemplate.js",
            "jupload" : "common/js/jupload/JUpload.js",
            "areaSelect" : "common/js/jarea/JAreaSelect.js",
            "areaData" : "common/js/jarea/JAreaData.js",
            "datepicker" : "common/js/datepicker/amazeui.datetimepicker.min.js",
            "chosen" : "common/js/chosen/amazeui.chosen.min.js",
            "jpreview" : "common/js/jpreview.js",

            "common" : "{app}/common.js",
            "index" : "{app}/index.js",
            "admin" : "{app}/admin.js",
            "role" : "{app}/role.js",
            "menu" : "{app}/menu.js",
            "permission" : "{app}/permission.js",

        },
        preload: [

        ],

        //文件映射
        map: [
            ['.css', '.css?v=' + version],
            ['.js', '.js?v=' + version]
        ],

        // 文件编码
        charset: function (url) {
            if (url.indexOf(".gbk.") > -1) {
                return "GBK";
            }
            return "UTF-8";
        }
    };
    var alias = config.alias;
    var v = typeof(rev) == "undefined" ? '' : '?v=' + rev;
    var suffix = (debug ? ".js" : ".min.js");
    for (var key in alias) {
        if (alias[key].indexOf(".min") == -1 && alias[key].indexOf("WdatePicker") == -1 && alias[key].indexOf(".js") != -1) {
            alias[key] = alias[key].replace(/\.js/, suffix) + v;
        }
    }
    seajs.config(config);
})();

