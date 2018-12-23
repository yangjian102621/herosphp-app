/**
 * 后台菜单
 * @author yangjian
 */
"use strict";
define(function(require, exports) {

    var common = require("common");

    common.initForm("#cAdd");
    //初始化 switch 控件
    common.initSwitch();

    //添加菜单
    $("#item-add").on("click", function () {
        common.post({
            title : "添加菜单",
            tempId : "role-template",
            formId : "cAdd",
            data : {
                url : "/admin/menu/insert",
                item : {}
            }
        });
    });

    //编辑菜单
    $(".item-edit").on("click", function () {
        var id = $(this).data("id");
        $.get("/admin/menu/edit/?id="+id, function (res) {
            if (res.code != "000") {
                common.errorMessage(res.message);
            } else {
	            common.post({
		            title : "编辑菜单",
		            tempId : "role-template",
		            formId : "cAdd",
		            data : {
			            url : "/admin/menu/update",
                        item : res.item
		            }
	            });

                //处理父级菜单可选状态
                $('select[name="pid"] option').each(function (idx, ele) {
                     if (ele.value == res.item.id) {
                         $(ele).attr("disabled", true);
                     }
                     if (ele.value == res.item.pid) {
                         ele.selected = true;
                     }
                })
            }
        }, "json");
    });

    //添加子菜单
    $(".add-sub-item").on("click", function () {

        var pid = $(this).data("id");
        common.post({
            title : "添加菜单",
            tempId : "role-template",
            formId : "cAdd",
            data : {
                url : "/admin/menu/insert",
                item : {}
            }
        });
        $('select[name="data[pid]"] option').each(function (idx, ele) {
            if (ele.value == pid) {
                ele.selected = true;
            }
        })
    });

});
