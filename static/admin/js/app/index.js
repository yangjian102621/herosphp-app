/**
 * 公共js文件
 * @author yangjian
 */
"use strict";
define(function(require, exports) {

    var common = require("common");
	require("chosen");
	require("jpreview");
	require("datepicker");

    common.initForm("#cAdd", {
	    //validate: function(validity) {
		 //   // if (validity.field.id == 'pic') {
		 //   //     alert("fuck");
			// //    // var date = validity.field.value;
			// //    // var date1 = $('#startdate').val();
			// //    // if (date1 > date) {
			//	//  //    validity.valid = false;
			// //    // }
		 //   // }
	    //
	    //},
    });

	//初始化 chosen 控件
	$('select[data-type="chosen"]').chosen({
		no_results_text: '木有找到匹配的项！',
		max_selected_options: 3
	});

	// 日期选择控件
	$('#date').datetimepicker({
		format: 'yyyy-mm-dd',
		//startDate : new Date(),
		minView : 2,
		todayBtn : true,
		autoclose : true
	});

	$("#time").datetimepicker({
		format: 'yyyy-mm-dd hh:ii',
		startDate : new Date(),
		minView : 0,
		autoclose : true
	});

	/**
	 * 图片上传
	 * @param data
	 */
	exports.upload = function (data) {

		var loader;

		$("#upload-btn").JUpload({
			url : "/admin/upload/qiniu",
			src : "file",
			maxFileNum : 5,
			extAllow : "jpg|png|gif|jpeg",
			datas : data,
			maxFileSize : 2,
			image_container:"image-box",
			messageHandler : function (message) {
				JDialog.msg({type:"error", content:message, timer:3000});
			},
			onStart : function () {
				loader = JDialog.msg({type:"loading", content:"正在上传文件,请稍后...", timer:0, lock:true});
			},
			onComplete : function () { //完成上传
				loader.hide();
			},
			onError : function () {
				loader.hide();
			},
			onSuccess : function(data) {
				$('#cover').val(data);
				JDialog.msg({type:"ok", content:"上传成功。", timer:2000});
				$(".img-wrapper img").jpreview();
			},
			onRemove : function(data) {
				$('#cover').val("");
			}, //删除一张图片回调
		});

		$(".img-wrapper img").jpreview();
	}

	/**
	 * 编辑器
	 */
	exports.editor = function () {

		KindEditor.ready(function(K) {
			K.create('textarea[name="content"]', {

				filePostName : 'imgFile',
				uploadJson : K.basePath+'php/qiniu/upload_json.php',
				fileManagerJson : K.basePath+'php/qiniu/file_manager_json.php',
				imageSearchJson : K.basePath+'php/qiniu/image_search_json.php', //图片搜索url
				imageGrapJson : K.basePath+'php/qiniu/image_grap_json.php', //抓取选中的搜索图片地址
				allowFileManager : true,
				allowImageUpload : true,
				allowMediaUpload : true,
				afterCreate : function() {
					//var self = this;
					//K.ctrl(document, 13, function() {
					//	self.sync();
					//	K('form[name=example]')[0].submit();
					//});
					//K.ctrl(self.edit.doc, 13, function() {
					//	self.sync();
					//	K('form[name=example]')[0].submit();
					//});
				},
				//错误处理 handler
				errorMsgHandler : function(message, type) {
					JDialog.msg({type:type, content:message, timer:2000, offset:60});
				}
			});

			K.create('textarea[name="content1"]', {

				filePostName : 'imgFile',
				uploadJson : K.basePath+'php/default/upload_json.php',
				fileManagerJson : K.basePath+'php/default/file_manager_json.php',
				imageSearchJson : K.basePath+'php/default/image_search_json.php', //图片搜索url
				imageGrapJson : K.basePath+'php/default/image_grap_json.php', //抓取选中的搜索图片地址
				allowFileManager : true,
				allowImageUpload : true,
				allowMediaUpload : true,
				themeType : "black",
				afterCreate : function() {
					//var self = this;
					//K.ctrl(document, 13, function() {
					//	self.sync();
					//	K('form[name=example]')[0].submit();
					//});
					//K.ctrl(self.edit.doc, 13, function() {
					//	self.sync();
					//	K('form[name=example]')[0].submit();
					//});
				},
				//错误处理 handler
				errorMsgHandler : function(message, type) {
					JDialog.msg({type:type, content:message, timer:2000, offset:60});
				}
			});

		});

	}

    var vm = new Vue({
        el: '#example',
        data: {
            message : "数据双向绑定"
        }
    })


});
