$(function() {
    autoLeftNav();
    $(window).resize(function() {
        autoLeftNav();
        //console.log($(window).width())
    });

    //    if(storageLoad('SelcetColor')){

    //     }else{
    //       storageSave(saveSelectColor);
    //     }
})


// 风格切换

$('.tpl-skiner-toggle').on('click', function() {
    $('.tpl-skiner').toggleClass('active');
})

$('.tpl-skiner-content-bar').find('span').on('click', function() {
    $('body').attr('class', $(this).data('color'))
    saveSelectColor.Color = $(this).data('color');
    // 保存选择项
    storageSave(saveSelectColor);

})




// 侧边菜单开关


function autoLeftNav() {



    $('.tpl-header-switch-button').on('click', function() {
        if ($('.left-sidebar').is('.active')) {
            if ($(window).width() > 1024) {
                $('.tpl-content-wrapper').removeClass('active');
            }
            $('.left-sidebar').removeClass('active');
        } else {

            $('.left-sidebar').addClass('active');
            if ($(window).width() > 1024) {
                $('.tpl-content-wrapper').addClass('active');
            }
        }
    })

    if ($(window).width() < 1024) {
        $('.left-sidebar').addClass('active');
    } else {
        $('.left-sidebar').removeClass('active');
    }
}


// 侧边菜单
$('.sidebar-nav-sub-title').on('click', function() {
    $(this).siblings('.sidebar-nav-sub').slideToggle(80)
        .end()
        .find('.sidebar-nav-sub-ico').toggleClass('sidebar-nav-sub-ico-rotate');
})

//设置左边 body 的高度
$(".left-sidebar").css({
    "height" : $(document).height() +"px"
});

//初始化菜单的展开状态
$("#sidebar-nav .sidebar-nav-link a").each(function (idx, ele) {
    var host = location.protocol+"//"+location.host;
    var href = location.href.replace(host, '');
    if ($(ele).attr("href") == href) {
        $(ele).addClass("sub-active");
        $(ele).closest(".sidebar-nav").prev().trigger("click");
        return;
    }
})