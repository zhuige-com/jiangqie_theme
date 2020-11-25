<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

/**
 * 酱茄Free主题由酱茄（www.jiangqie.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

require_once get_theme_file_path() . '/inc/widgets/rand-list.php';
require_once get_theme_file_path() . '/inc/widgets/hot-list.php';
require_once get_theme_file_path() . '/inc/widgets/tags.php';


/** 小程序占位 */
if (function_exists('register_sidebar')) {
    // register_sidebar(array(
    //     'id'            => 'footer-site',
    //     'name'          => '全站底栏',
    //     'description'   => '全站底部显示的小工具',
    //     'before_widget' => '<div class="aside-block mb-20">',
    //     'after_widget'  => '</div>',
    //     'before_title'  => '<h2 class="mb-10">',
    //     'after_title'   => '</h2>'
    // ));
    register_sidebar(array(
        'id'            => 'sidebar-site',
        'name'          => '全站边栏',
        'description'   => '全站边栏显示的小工具',
        'before_widget' => '<div class="aside-block mb-20">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="mb-10">',
        'after_title'   => '</h2>'
    ));
    register_sidebar(array(
        'id'            => 'sidebar-home',
        'name'          => '首页边栏',
        'description'   => '首页边栏显示的小工具',
        'before_widget' => '<div class="aside-block mb-20">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="mb-10">',
        'after_title'   => '</h2>'
    ));
    register_sidebar(array(
        'id'            => 'sidebar-list',
        'name'          => '列表边栏',
        'description'   => '分类、Tag、搜索等页面边栏的小工具',
        'before_widget' => '<div class="aside-block mb-20">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="mb-10">',
        'after_title'   => '</h2>'
    ));
    register_sidebar(array(
        'id'            => 'sidebar-post',
        'name'          => '文章边栏',
        'description'   => '文章详情页边栏的小工具',
        'before_widget' => '<div class="aside-block mb-20">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="mb-10">',
        'after_title'   => '</h2>'
    ));
    register_sidebar(array(
        'id'            => 'sidebar-page',
        'name'          => '单页边栏',
        'description'   => '单页边栏的小工具',
        'before_widget' => '<div class="aside-block mb-20">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="mb-10">',
        'after_title'   => '</h2>'
    ));
    register_sidebar(array(
        'id'            => 'sidebar-other',
        'name'          => '其他边栏',
        'description'   => '其他页面边栏显示的小工具',
        'before_widget' => '<div class="aside-block mb-20">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="mb-10">',
        'after_title'   => '</h2>'
    ));
}

/**
 * 删除不用的小工具
 * WP_Widget_Pages                   = 页面
 * WP_Widget_Calendar                = 日历
 * WP_Widget_Archives                = 存档
 * WP_Widget_Links                   = 链接
 * WP_Widget_Meta                    = 功能
 * WP_Widget_Search                  = 搜索
 * WP_Widget_Text                    = Text
 * WP_Widget_Categories              = 分类
 * WP_Widget_Recent_Posts            = 近期文章
 * WP_Widget_Recent_Comments         = 最新评论
 * WP_Widget_RSS                     = RSS
 * WP_Widget_Tag_Cloud               = 标签云
 * WP_Nav_Menu_Widget                = 菜单
 * WP_Widget_Media_Image             = 图片
 * WP_Widget_Media_Gallery           = 画廊
 * WP_Widget_Media_Audio             = 音频
 * WP_Widget_Media_Video             = 视频
 */
function widgets_init_unregister_widgets()
{
    unregister_widget('WP_Widget_Media_Image');
    unregister_widget('WP_Widget_Media_Gallery');
    unregister_widget('WP_Widget_Media_Audio');
    unregister_widget('WP_Widget_Media_Video');

    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    // unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    // unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Widget_Gallery');
}
add_action('widgets_init', 'widgets_init_unregister_widgets');