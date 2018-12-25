/**
 * @author yangjian
 * @since 18-12-25 下午2:47.
 */

$(document).ready(function() {

	// 初始化全选事件
	$('#select-all').change(function() {
		if (this.checked) {
			$('#data-list-form input[name="ids[]"]').prop("checked", true);
		} else {
			$('#data-list-form input[name="ids[]"]').prop("checked", false);
		}
	});


});

// 删除一条数据
function removeItem(item, callback) {

	JDialog.confirm({
		title : "删除提示",
		width : 400,
		lock : true,
		effect : 1,
		maxEnable : false,
		content : '确定要删除该条记录吗？',
		icon : 'warn',
		offset : 'cc',
		effect : 'zoomIn',
		button : {
			'确认' : function(dialog) {
				dialog.lock();
				setTimeout(function() {
					messageOk("删除成功.");
					dialog.close();
				}, 2000)
			},
			'取消' : function(dialog) {
				dialog.close();
			}
		}
	});

}