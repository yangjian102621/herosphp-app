/**
 * 管理员模块js
 * @author yangjian
 */
"use strict";
define(function(require, exports) {

    var common = require("common");
    require("chosen");

    common.initForm("#cAdd");
    //初始化 switch 控件
    common.initSwitch();

    //初始化角色选择
	$('#chosen-select').chosen({
		max_selected_options: 5
	});

});
