/**
 * bootstrap v4.0 分页插件
 * 分页样式，每页记录数，参数过滤
 * @author yangjian
 * @since 18-12-25 上午11:37.
 */
$.fn.extend({
	pageHandler: function(options) {
		options = $.extend({
			total: 0, // 总记录数
			pageNo: 1, // 当前页面
			pageSize: 12, // 每页显示记录数
			align: 'center', // 页码排列方式，默认居中显示
			outputNum: 3, // 输出页码数
			changePage: function(pageNo, pageSize) {}
		}, options);

		// 计算总页数
		var totalPage = Math.ceil(options.total/options.pageSize);
		if (totalPage <= 1) { // 少于一页
			return;
		}
		var root = this;
		render(options.pageNo);

		/**
		 * 渲染分页组件
		 * @param pageNo
		 */
		function render(pageNo) {
			var $pageUL = $('<ul class="pagination"></ul>');
			$pageUL.addClass("justify-content-"+options.align);
			// 输出上一页
			var $pagePrev = $('<li class="page-item"></li>'), $prevIcon = $('<em class="oi oi-chevron-left"></em>'), $pageLink;
			if (pageNo == 1) {
				$pagePrev.addClass('disabled');
				$pageLink = $('<span class="page-link" title="上一页"></span>');
			} else {
				$pageLink = $('<a href="javascript:void(0);" data-page="'+(pageNo - 1)+'" class="page-link" title="上一页"></a>');
				// 绑定上一页事件
				$pageLink.click(function() {
					options.changePage(pageNo-1, options.pageSize);
					render(pageNo-1);
				});
			}
			$pageLink.append($prevIcon);
			$pagePrev.append($pageLink);
			$pageUL.append($pagePrev);
			// 输出页码
			for (var i = 1; i <= totalPage; i++) {
				var $li = $('<li class="page-item"></li>'), $link;
				if (i == pageNo) { // 当前页码
					$li.addClass('active');
					$link = $('<span class="page-link">'+i+'</span>');
				} else {
					$link = $('<a class="page-link" href="javascript:void(0);" data-page="'+i+'">'+i+'</a>');
					$link.click(function() {
						var _pageNo = parseInt($(this).attr('data-page'));
						options.changePage(_pageNo, options.pageSize);
						render(_pageNo);
					});
				}
				$li.append($link);
				$pageUL.append($li);
			}
			// 输出下一页
			var $pageNext = $('<li class="page-item"></li>'), $nextIcon = $('<em class="oi oi-chevron-right"></em>'), $pageLinkNext;
			if (pageNo == totalPage) {
				$pageNext.addClass('disabled');
				$pageLinkNext = $('<span class="page-link" title="下一页"></span>');
			} else {
				$pageLinkNext = $('<a href="javascript:void(0);" data-page="'+(pageNo + 1)+'" class="page-link" title="下一页"></a>');
				// 绑定下一页事件
				$pageLinkNext.click(function() {
					options.changePage(pageNo+1, options.pageSize);
					render(pageNo+1);
				});
			}
			$pageLinkNext.append($nextIcon);
			$pageNext.append($pageLinkNext);
			$pageUL.append($pageNext);
			$(root).empty().append($pageUL);
		}
	}
});

