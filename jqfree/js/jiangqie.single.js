/**
 * 酱茄Free主题由酱茄（www.jiangqie.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

jQuery(document).ready(function ($) {

    /** -- 点赞 -- start -- */
    $('.btn-thumbup').click(function () {
        if ($(this).hasClass('done')) {
            alert('已赞过');
            return false;
        } else {
            $(this).addClass('done');
            let id = $(this).data("id");
            let action = $(this).data('action');
            var ajax_data = {
                action: "jaingqie_thumbup",
                um_id: id,
                um_action: action
            };
            $.post("/wp-admin/admin-ajax.php", ajax_data, function (data) {
                $('.count').html(data);
            });
            return false;
        }
    });
    /** -- 点赞 -- end -- */

    /** -- 打赏 -- start -- */
    $('.btn-reward').click(function () {
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            area: ['auto'],
            skin: 'layui-layer-nobg', //没有背景色
            shadeClose: true,
            content: $('#reward-div')
        });
    });
    /** -- 打赏 -- end -- */

}); 