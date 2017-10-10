/**
 * 公共js文件
 * @author yangjian
 */
"use strict";
define(function(require, exports) {

    var common = require("common");
	require("chosen");
	require("jpreview");
    common.initForm("#cAdd", {
	    validate: function(validity) {
		    // if (validity.field.id == 'pic') {
		    //     alert("fuck");
			 //    // var date = validity.field.value;
			 //    // var date1 = $('#startdate').val();
			 //    // if (date1 > date) {
				//  //    validity.valid = false;
			 //    // }
		    // }

	    },
    });

	//初始化 chosen 控件
	$('select[data-type="chosen"]').chosen({
		no_results_text: '木有找到匹配的项！',
		max_selected_options: 3
	});

	exports.upload = function (data) {

		var loader;

		$("#upload-btn").JUpload({
			url : "/upload/putFile",
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

				uploadJson : '/upload/putFile',
				filePostName : 'file',
				fileManagerJson : '/upload/list',
				imageSearchJson : '/upload/search', //图片搜索url
				imageGrapJson : '/upload/grap', //抓取选中的搜索图片地址
				allowFileManager : true,
				allowImageUpload : true,
				allowMediaUpload : true,
				afterCreate : function() {
					var self = this;
					K.ctrl(document, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
					K.ctrl(self.edit.doc, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
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
