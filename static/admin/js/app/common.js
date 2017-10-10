    /**
     * 公共js文件
     * @author yangjian
     */
    "use strict";
    define(function(require, exports) {
    
        var template = require("template");
        template.config("openTag", "{{");
        template.config("closeTag", "}}");

        //全局变量
        exports.global = {
            timeout : 1500,
	        errorMsg : "系统开了小差.",
            msg : {
                offset : 85,
            }
        };
    
        /**
         * 初始化数据表单
         * @param formId
         */
        exports.initForm = function (formId, options) {
            options = $.extend({

	            /**
	             * 表单验证
	             * @param validity
	             * @returns {*}
	             */
	            validate: function(validity) {
		            // Ajax 验证
		            if ($(validity.field).is('.js-ajax-validate')) {
			            // 异步操作必须返回 Deferred 对象
			            var value = validity.field.value;
			            if (value == '') {
				            return false;
			            }
			            var minlength = $(validity.field).attr("minlength");
			            if ( minlength > value.length ) {
				            $(validity.field).data("validationMessage", "最少输入 "+minlength+" 位");
				            return false;
			            }
			            //console.log(value);
			            return $.ajax({
				            url: $(validity.field).data("url")+value,
				            cache: false,
				            dataType: 'json'
			            }).then(function(data) {

				            if (data.code != "000") {
					            validity.valid = false;
				            } else {
					            validity.valid = true;
				            }
				            return validity;
			            }, function() {
				            validity.valid = false;
				            return validity;
			            });
		            }

	            },

	            onValid: function(validity) {
		            $(validity.field).closest('.am-form-group').find('.am-alert').hide();
	            },

	            onInValid: function(validity) {
		            var $field = $(validity.field);
		            var $group = $field.closest('.am-form-group');
		            var $alert = $group.find('.am-alert');
		            // 使用自定义的提示信息 或 插件内置的提示信息
		            var msg = $field.data('validationMessage') || this.getValidationMessage(validity);
		            if ($field.val() == '') {
			            msg = $field.attr('placeholder');
		            }

		            if (typeof options.messageHandler == "function") {
						options.messageHandler(msg);
		            } else {
			            if (!$alert.length) {
				            $alert = $('<div class="am-alert am-alert-danger"></div>').hide().
				            appendTo($group);
			            }

			            $alert.html(msg).show();
		            }
	            },

	            /**
	             * form submit handler
	             * @returns {boolean}
	             */
	            submit : function () {

		            if ( $(formId).validator('isFormValid') == false) {
			            return false;
		            }
		            var $btn = $('button[type="submit"]');
		            $btn.button('loading');
		            var formData = $(formId).serialize();
		            var url = $(formId).data("action");
		            var location = $(formId).data("location");
		            $.post(url, formData, function (res) {

			            if (res.code == "000") {
				            exports.okMessage(res.message);
				            setTimeout(function () {
					            if (location) {
						            switch (location) {
							            case "reset":
								            $(formId).reset();
								            break;
							            case "reload":
								            window.location.reload();
								            break;
							            default:
								            window.location.replace(location);
								            break;
						            }
					            }
				            }, exports.global.timeout);
			            } else {
				            exports.errorMessage(res.message);
				            $btn.button("reset");
			            }

		            }, "json");

		            return false;
	            }
            }, options);
            $(formId).validator(options);
        }

	    /**
         * 初始化 switch 控件
	     */
	    exports.initSwitch = function () {
	        $('input[data-type="switch"]').on("click", function () {

		        var url = $(this).data("url");
		        var id = $(this).data("id");
		        var enable = 0;
		        if(this.checked) {
			        enable = 1;
		        }
		        $.post(url, {
			        id : id,
			        enable : enable
		        }, function (res) {
			        if (res.code == "000") {
				        exports.okMessage("操作成功!");
			        } else {
				        exports.errorMessage("操作失败!");
			        }
		        }, "json");
	        });
        }
    
        /**
         * 初始化删除按钮操作
         */
        $(".item-delete").on("click", function (e) {
            var url = $(this).data("url");
            JDialog.confirm({
                title : "删除提示",
                content : "要删除这条记录吗？",
                icon : 'warn',
                button : {
                    '确定' : function (dialog) {
                        dialog.close();
                        $.get(url, function (res) {
                            if (res.code == "000") {
                                exports.okMessage(res.message);
    
                                setTimeout(function () {
                                    window.location.reload();
                                }, exports.global.timeout);
    
                            } else {
                                exports.errorMessage(res.message || exports.global.errorMsg);
                            }
                        })
                    },
                    '取消' : function (dialog) {
                        dialog.close();
                    }
                }
            });
        });
    
        //初始化 checkbox UI
        $('input[data-type="icheck"]').uCheck();
        //全选操作事件绑定
        $('#check-all').on("change", function () {
            if (this.checked) {
                $("#cList tbody").find('input[data-type="icheck"]').uCheck('check');
            } else {
                $("#cList tbody").find('input[data-type="icheck"]').uCheck('uncheck');
            }
        });
        
        //批量删除数据
        $("#del-all").on("click", function () {
    
            var ids = [];
            var url = $(this).data("url");
            $("#cList").find('input[data-type="icheck"]').each(function (idx, ele) {
                if (ele.checked && ele.value != "0") {
                    ids.push(ele.value);
                }
            })
            if (ids.length == 0) {
                return exports.infoMessage("请选择你要删除的记录!");
            }
    
            JDialog.confirm({
                title : "删除提示",
                content : "确定要删除选中记录？",
                icon : 'warn',
                button : {
                    '确定' : function (dialog) {
                        dialog.close();
                        $.get(url, {
                            id_str : ids.join(",")
                        }, function (res) {
                            if (res.code == "000") {
                                exports.okMessage("删除成功!");
    
                                setTimeout(function () {
                                    window.location.reload();
                                }, exports.global.timeout);
    
                            } else {
                                exports.errorMessage("删除失败!");
                            }
                        });
                    },
                    '取消' : function (dialog) {
                        dialog.close();
                    }
                }
            });
    
        });
    
        $(".empty-td").attr("colspan", $("#clist-table thead tr th").length).css({"text-align":"center"});
    
        //修改管理员密码
        $("#modify-pass").on("click", function () {
            exports.post({
                title : '修改密码',
                tempId : 'modify-password-template',
	            formId : 'modify-pass-form'
            });
        });

	    /**
         * 发送 post 请求的统一方法
	     * @param options
	     */
	    exports.post = function (options) {
	        options = options || {};
		    var html = template(options.tempId, options.data || {});
		    var formId = '#'+options.formId;
		    var dia = JDialog.open({
			    title : options.title,
			    effect : 'slideInDown',
			    content : html,
			    width : options.width || 600,
			    height : options.height || 0,
			    offset : options.offset || 50,
			    button : {
				    '保存' : function(dialog) {

					    if ( $(formId).validator('isFormValid') == false) {
						    return false;
					    }
					    dialog.lock();
					    var formData = $(formId).serialize();
					    var url = $(formId).data("action"), location = $(formId).data("location");
					    exports.sendPost({
					    	url : url,
						    data : formData,
						    location : location,
						    callback : function () {
							    dialog.close();
						    }
					    })

				    },
				    '取消' : function(dialog) {
					    dialog.close();
				    }
			    }
		    });
		    exports.initForm(formId, {
			    messageHandler : function (msg) {
				    JDialog.msg({type:"error", content:msg, container:"#"+dia.getId()});
			    }
		    });
        }

	    /**
	     * 发送 ajax post 请求
	     * @param options {url, data, location, btn, callback}
	     */
	    exports.sendPost = function (options) {
	    	if (options.btn) {
	    		$(options.btn).button("loading");
		    }
		    $.post(options.url, options.data || {}, function (res) {

				if (typeof options.callback == "function") {
					options.callback(res);
				}
			    if (res.code == "000") {
				    exports.okMessage(options.successMsg || "操作成功!");
				    if ( options.location == 'reload' ) {
					    setTimeout(function () {
						    window.location.reload();
					    }, exports.global.timeout);
				    } else if (options.location) {
					    setTimeout(function () {
						    window.location.href = options.location;
					    }, exports.global.timeout);
				    }
			    } else {
				    exports.errorMessage(options.failMsg || res.message);
			    }

			    if (options.btn) {
				    $(options.btn).button("reset");
			    }
		    });
        }
    
        /**
         * 操作成功的提示信息
         * @param message
         */
        exports.okMessage = function (message) {
            JDialog.msg({type:"ok", content:message, offset:exports.global.msg.offset, timer: exports.global.timeout});
        }
    
        /**
         * 操作失败的提示信息
         * @param message
         */
        exports.errorMessage = function (message) {
            JDialog.msg({type:"error", content:message, offset:exports.global.msg.offset, timer: exports.global.timeout});
        }
    
        /**
         * 普通提示信息
         * @param message
         */
        exports.infoMessage = function (message) {
            JDialog.msg({type:"warn", content:message, offset:exports.global.msg.offset, timer: exports.global.timeout});
        }
    
    });
