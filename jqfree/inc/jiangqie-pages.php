<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access directly.

/**
 * 酱茄Free主题由追格（www.zhuige.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

/**  
 *参数$title 字符串 页面标题  
 *参数$slug  字符串 页面别名  
 *参数$page_template 字符串  模板名  
 *无返回值  
 **/
function zhuige_theme_ow_free_add_page($title, $slug, $page_template = '', $content = '')
{
    $allPages = get_pages(); //获取所有页面   
    $exists = false;
    foreach ($allPages as $page) {
        //通过页面别名来判断页面是否已经存在   
        if (strtolower($page->post_name) == strtolower($slug)) {
            $exists = true;
        }
    }

    if ($exists == false) {
        $new_page_id = wp_insert_post(
            array(
                'post_title' => $title,
                'post_type'     => 'page',
                'post_name'  => $slug,
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_content' => $content,
                'post_status' => 'publish',
                'post_author' => 1,
                'menu_order' => 0
            )
        );
        //如果插入成功 且设置了模板   
        if ($new_page_id && $page_template != '') {
            //保存页面模板信息   
            update_post_meta($new_page_id, '_wp_page_template',  $page_template);
        }
    }
}

function zhuige_theme_ow_free_add_page_action()
{
    global $pagenow;
    //判断是否为激活主题页面   
    if ('themes.php' == $pagenow && isset($_GET['activated'])) {
        zhuige_theme_ow_free_add_page('友情链接', 'links', 'page-links.php', '追格主题占位页-请勿修改');
        zhuige_theme_ow_free_add_page('所有标签', 'tags', 'page-tags.php', '追格主题占位页-请勿修改');
    }
}
add_action('load-themes.php', 'zhuige_theme_ow_free_add_page_action');
