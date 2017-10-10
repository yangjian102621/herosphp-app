/**
 * 管理员角色权限
 * @author yangjian
 */
"use strict";
define(function(require, exports) {

    var common = require("common");

    common.initForm("#cAdd");
    //初始化 switch 控件
    common.initSwitch();

    //添加权限
    $("#item-add").on("click", function () {
        common.post({
            title : "添加权限",
            tempId : "role-template",
            formId : "cAdd",
            data : {
                url : "/admin/permission/doAdd",
                item : {

                }
            }
        });

    });

    //编辑权限
    $(".item-edit").on("click", function () {
        var id = $(this).data("id");
        $.get("/admin/permission/edit/"+id, function (res) {
            if (res.code != "000") {
                common.errorMessage(res.message);
            } else {
	            common.post({
		            title : "编辑权限",
		            tempId : "role-template",
		            formId : "cAdd",
		            data : {
			            url : "/admin/permission/doUpdate",
                        item : res.item
		            }
	            });

				//初始化权限分组的选中状态
	            $('select[name="group"] option').each(function (idx, ele) {
		            if (ele.value == res.item.group) {
		            	ele.selected = true;
		            	return;
		            }
	            })
            }
        }, "json");
    });
});
