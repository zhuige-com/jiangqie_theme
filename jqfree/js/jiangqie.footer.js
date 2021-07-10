/**
 * 酱茄Free主题由酱茄（www.jiangqie.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

/**
 * 顶部导航
 */
{
    const menuBtn = document.querySelector(".menu-icon span");
    const searchBtn = document.querySelector(".search-icon");
    const cancelBtn = document.querySelector(".cancel-icon");
    const items = document.querySelector(".nav-box");
    const form = document.querySelector("form");
    menuBtn.onclick = () => {
        items.classList.add("active");
        menuBtn.classList.add("hide");
        searchBtn.classList.add("hide");
        cancelBtn.classList.add("show");
    }
    cancelBtn.onclick = () => {
        items.classList.remove("active");
        menuBtn.classList.remove("hide");
        searchBtn.classList.remove("hide");
        cancelBtn.classList.remove("show");
        form.classList.remove("active");
        cancelBtn.style.color = "#ff3d00";
    }
    searchBtn.onclick = () => {
        form.classList.add("active");
        searchBtn.classList.add("hide");
        cancelBtn.classList.add("show");
    }
}

jQuery(document).ready(function ($) {
    /** -- 导航悬停 -- start -- */
    $.fn.stick = function () {
        var $cur = this;
        var offsetTop = $cur.offset().top;
        var isFixed = false;

        //  克隆元素，用于占位
        var $curClone = $cur.clone()
            .css({ visibility: "hidden", display: "none" })
            .insertBefore($cur);

        //  设置监听函数
        $(window).on("scroll", function () {
            var winScroll = $(this).scrollTop();
            if (offsetTop < winScroll) {
                if (!isFixed) {
                    setFixed();
                }
            } else {
                if (isFixed) {
                    unsetFixed();
                }
            }
        });

        // 设置添加和删除stick函数
        function setFixed() {
            $cur.css({
                "box-shadow": "0px 0px 2px 3px rgba(99,99,99,0.1)",
                "position": "fixed",
                'max-width': 'none',
                "top": offsetTop,
                "left": 0,
                "margin": 0,
                "z-index": 100,
            });
            $curClone.show();
            isFixed = true;
        }

        function unsetFixed() {
            $cur.removeAttr("style");
            $curClone.hide();
            isFixed = false;
        }
    }

    $("#top-nav-wraper").stick();
    /** -- 导航悬停 -- end -- */

    /* 二级菜单 -- start -- */
    $('.nav-box>.nav-items .menu-item').each(function () {
        let submenu = $(this).children('.sub-menu');
        if (submenu.length > 0) {
            let a = $(this).children('a');
            if (a.length > 0) {
                a.attr('href', '#');
                a.attr('target', '_self');
            }
        }
    });

    $('.nav-box>.nav-items .menu-item').mouseenter(function () {
        if ($(document).width() >= 1140) {
            $(this).children('.sub-menu').show();
        }
    });

    $('.nav-box>.nav-items .menu-item').mouseleave(function () {
        if ($(document).width() >= 1140) {
            $(this).children('.sub-menu').hide();
        }
    });

    $('.nav-box>.nav-items .menu-item').click(function () {
        if ($(document).width() < 1140) {
            $(this).children('.sub-menu').toggle();
        }
    });

    /* 二级菜单 -- start -- */

    /** 返回顶部 start */
    $(window).scroll(function (event) {
        let scrollTop = $(this).scrollTop();
        if (scrollTop == 0) {
            $("#toTop").hide();
        } else {
            $("#toTop").show();
        }
    });

    $("#toTop").click(function (event) {
        $("html,body").animate(
            { scrollTop: "0px" },
        )
    });
    /** 返回顶部 end */

    /* 右侧悬停 start */
    let gLastAside = $(".aside-block:last");
    var gFixedLimit = gLastAside.offset().top + gLastAside.height();
    $(window).resize(function () {
        $('.widgetRoller').remove();
        gLastAside = $(".aside-block:last");
        gFixedLimit = gLastAside.offset().top + gLastAside.height();
    });

    $(window).scroll(function (event) {
        let scrollTop = $(this).scrollTop();
        if (scrollTop > gFixedLimit) {
            if ($('.widgetRoller').length == 0) { 
                gLastAside.parent().append('<div class="widgetRoller"></div>'); 
                gLastAside.clone().appendTo('.widgetRoller');
            }

            let top = $('#top-nav-wraper').offset().top + $('#top-nav-wraper').height();
            let bottom = $('footer').offset().top;
            let offset = scrollTop + top + 10 + gLastAside.height() + 10 - bottom;
            
            if (offset > 0) {
                $('.widgetRoller').css({ position: "fixed", top: top - 30 - offset, zIndex: 99, width: gLastAside.parent().width() });
            } else {
                $('.widgetRoller').css({ position: "fixed", top: top + 10, zIndex: 99, width: gLastAside.parent().width() });
            }
            
            $('.widgetRoller').fadeIn(300)
        } else {
            $('.widgetRoller').hide() 
        }
    });

    $("#toTop").click(function (event) {
        $("html,body").animate(
            { scrollTop: "0px" },
            666
        )
    });
    /* 右侧悬停 end */
});