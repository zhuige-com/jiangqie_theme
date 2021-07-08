<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

/**
 * 酱茄Free主题由酱茄（www.jiangqie.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

//
// Set a unique slug-like ID
//
$prefix = 'jiangqie_free';

//分类信息
$cats = get_categories([]);
$categories = [];
foreach ($cats as $cat) {
    $categories[$cat->term_id] = $cat->name;
}

//
// Create options
//
CSF::createOptions($prefix, array(
    'framework_title' => '酱茄Free主题 <small>by <a href="https://www.jiangqie.com" target="_blank" title="酱茄">www.jiangqie.com</a></small>',
    'menu_title' => '酱茄Free主题',
    'menu_slug'  => 'jiangqie-free',
    'menu_position' => 2,
    'footer_credit' => 'Thank you for creating with <a href="https://www.jiangqie.com/" target="_blank">酱茄</a>',
));

$content = '欢迎使用酱茄Free主题! <br/><br/> 微信客服：jianbing2011 (加开源群、问题咨询、项目定制、购买咨询) <br/><br/> <a href="https://www.jiangqie.com/xz" target="_blank">更多免费产品</a>';
if (stripos($_SERVER["REQUEST_URI"], 'jiangqie-free')) {
    $res = wp_remote_get("https://key.jiangqie.com/api/goods/description?id=jq_theme_free", ['timeout' => 1]);
    if (!is_wp_error($res) && $res['response']['code'] == 200) {
        $data = json_decode($res['body'], TRUE);
        if ($data['code'] == 1) {
            $content = $data['data'];
        }
    }
}

//
// 概要
//
CSF::createSection($prefix, array(
    'title'  => '概要',
    'icon'   => 'fas fa-rocket',
    'fields' => array(

        array(
            'type'    => 'content',
            'content' => $content,
        ),

    )
));

//
// 基础设置
//
CSF::createSection($prefix, array(
    'title' => '基础设置',
    'icon'  => 'fas fa-map-marker',
    'fields' => array(

        array(
            'id'      => 'site_logo',
            'type'    => 'media',
            'title'   => 'LOGO设置',
            'library' => 'image',
        ),

        array(
            'id'      => 'site_favicon',
            'type'    => 'media',
            'title'   => 'favicon',
            'subtitle' => '.ico格式',
            'library' => 'image',
        ),
    )
));

//
// 首页设置
//
CSF::createSection($prefix, array(
    'title' => '首页设置',
    'icon'  => 'fas fa-th',
    'fields' => array(

        array(
            'id'     => 'home_slide',
            'type'   => 'repeater',
            'title'  => '幻灯片设置',
            'fields' => array(
                array(
                    'id'      => 'image',
                    'type'    => 'media',
                    'title'   => '图片',
                    'library' => 'image',
                ),
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题'
                ),
                array(
                    'id'       => 'url',
                    'type'     => 'text',
                    'title'    => '链接',
                    'default'  => 'https://www.jiangqie.com',
                    'validate' => 'csf_validate_url',
                ),
            ),
        ),

        array(
            'id'     => 'home_slide_ad',
            'type'   => 'repeater',
            'title'  => '幻灯片广告',
            'min'    => 0,
            'max'    => 2,
            'fields' => array(
                array(
                    'id'      => 'image',
                    'type'    => 'media',
                    'title'   => '图片',
                    'library' => 'image',
                ),
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题'
                ),
                array(
                    'id'       => 'url',
                    'type'     => 'text',
                    'title'    => '广告链接',
                    'default'  => 'https://www.jiangqie.com',
                    'validate' => 'csf_validate_url',
                ),
            ),
        ),

        array(
            'id'          => 'home_post_recommend',
            'type'        => 'select',
            'title'       => '精选文章',
            'chosen'      => true,
            'multiple'    => true,
            'sortable'    => true,
            'ajax'        => true,
            'options'     => 'posts',
            'placeholder' => '请选择文章',
        ),

        array(
            'id'          => 'home_cat_show',
            'type'        => 'select',
            'title'       => '显示分类',
            'chosen'      => true,
            'multiple'    => true,
            'sortable'    => true,
            'ajax'        => true,
            'placeholder' => 'Select an option',
            'options'     => $categories,
            'default'     => array()
        ),

        array(
            'id'       => 'home_excerpt_length',
            'type'     => 'spinner',
            'title'    => '文章摘要长度',
            'subtitle' => 'max:100 | min:0 | step:1',
            'max'      => 100,
            'min'      => 0,
            'step'     => 1,
            'default'  => 25,
        ),
    )
));

//
// 列表设置
//
CSF::createSection($prefix, array(
    'title' => '列表设置',
    'icon'  => 'fas fa-ellipsis-h',
    'fields' => array(

        array(
            'type'    => 'notice',
            'style'   => 'info',
            'content' => '此页设置对分类首页文章列表、文章列表、tag文章列表、搜索文章列表等列表有效',
        ),

        array(
            'id'    => 'list_switch_bread',
            'type'  => 'switcher',
            'title' => '面包屑导航',
            'label' => '是否显示面包屑导航.',
            'default' => '1'
        ),

        array(
            'id'    => 'list_switch_author_avatar',
            'type'  => 'switcher',
            'title' => '作者头像',
            'label' => '是否显示作者头像.',
            'default' => '1'
        ),

        array(
            'id'    => 'list_switch_author_name',
            'type'  => 'switcher',
            'title' => '作者名称',
            'label' => '是否显示作者名称.',
            'default' => '1'
        ),

        array(
            'id'    => 'list_switch_thumbup_count',
            'type'  => 'switcher',
            'title' => '点赞数量',
            'label' => '是否显示点赞数量.',
            'default' => '1'
        ),

        array(
            'id'    => 'list_switch_view_count',
            'type'  => 'switcher',
            'title' => '浏览数量',
            'label' => '是否显示浏览数量.',
            'default' => '1'
        ),

        array(
            'id'    => 'list_switch_comment_count',
            'type'  => 'switcher',
            'title' => '评论数量',
            'label' => '是否显示评论数量.',
            'default' => '1'
        ),
    )
));

//
// 文章详情
//
CSF::createSection($prefix, array(
    'title' => '文章详情',
    'icon'  => 'fas fa-code-branch',
    'fields' => array(

        array(
            'id'    => 'detail_switch_bread',
            'type'  => 'switcher',
            'title' => '面包屑导航',
            'label' => '是否显示面包屑导航.',
            'default' => '1'
        ),

        array(
            'id'    => 'detail_switch_author_avatar',
            'type'  => 'switcher',
            'title' => '作者头像',
            'label' => '是否显示作者头像.',
            'default' => '1'
        ),

        array(
            'id'    => 'detail_switch_author_name',
            'type'  => 'switcher',
            'title' => '作者名称',
            'label' => '是否显示作者名称.',
            'default' => '1'
        ),

        array(
            'id'    => 'detail_switch_thumbup_count',
            'type'  => 'switcher',
            'title' => '点赞数量',
            'label' => '是否显示点赞数量.',
            'default' => '1'
        ),

        array(
            'id'    => 'detail_switch_view_count',
            'type'  => 'switcher',
            'title' => '浏览数量',
            'label' => '是否显示浏览数量.',
            'default' => '1'
        ),

        array(
            'id'    => 'detail_switch_comment_count',
            'type'  => 'switcher',
            'title' => '评论数量',
            'label' => '是否显示评论数量.',
            'default' => '1'
        ),

        array(
            'id'          => 'detail_copyright',
            'type'        => 'textarea',
            'title'       => '版权信息',
            'placeholder' => '版权信息',
        ),

        array(
            'id'    => 'detail_switch_copyright',
            'type'  => 'switcher',
            'title' => '版权信息',
            'label' => '是否显示版权信息.',
            'default' => '1'
        ),

        array(
            'id'    => 'detail_switch_tag',
            'type'  => 'switcher',
            'title' => '标签信息',
            'label' => '是否显示标签信息.',
            'default' => '1'
        ),

        array(
            'id'    => 'detail_switch_pre_next',
            'type'  => 'switcher',
            'title' => '上下篇',
            'label' => '是否显示上下篇文章.',
            'default' => '1'
        ),

        array(
            'id'    => 'detail_switch_recommend',
            'type'  => 'switcher',
            'title' => '猜你喜欢',
            'label' => '是否显示猜你喜欢.',
            'default' => '1'
        ),

        array(
            'id'    => 'detail_switch_thumbup',
            'type'  => 'switcher',
            'title' => '点赞按钮',
            'label' => '是否显示点赞按钮.',
            'default' => '1'
        ),

        array(
            'id'      => 'detail_reward_image',
            'type'    => 'media',
            'title'   => '打赏图片',
            'library' => 'image',
        ),

        array(
            'id'    => 'detail_switch_reward',
            'type'  => 'switcher',
            'title' => '打赏按钮',
            'label' => '是否显示打赏按钮.',
            'default' => '1'
        ),
    )
));

//
// 页脚设置
//
CSF::createSection($prefix, array(
    'title' => '页脚设置',
    'icon'  => 'fas fa-redo',
    'fields' => array(

        array(
            'id'     => 'footer_icons',
            'type'   => 'repeater',
            'title'  => '社会化图标',
            'fields' => array(
                array(
                    'id'      => 'image',
                    'type'    => 'media',
                    'title'   => '图片',
                    'library' => 'image',
                ),
                array(
                    'id'       => 'url',
                    'type'     => 'text',
                    'title'    => '链接',
                    'default'  => 'https://www.jiangqie.com',
                    'validate' => 'csf_validate_url',
                ),
            ),
        ),

        array(
            'id'    => 'footer_about',
            'type'  => 'wp_editor',
            'title' => '关于我们',
        ),

        array(
            'id'     => 'footer_nav',
            'type'   => 'repeater',
            'title'  => '快速导航',
            'fields' => array(
                array(
                    'id'       => 'title',
                    'type'     => 'text',
                    'title'    => '标题',
                    'default'  => '',
                ),
                array(
                    'id'       => 'url',
                    'type'     => 'text',
                    'title'    => '链接',
                    'default'  => 'https://www.jiangqie.com',
                    'validate' => 'csf_validate_url',
                ),
            ),
        ),

        array(
            'id'          => 'footer_hot_recommend',
            'type'        => 'select',
            'title'       => '热门推荐',
            'chosen'      => true,
            'multiple'    => true,
            'sortable'    => true,
            'ajax'        => true,
            'options'     => 'posts',
            'placeholder' => '请选择文章',
        ),

        array(
            'id'    => 'footer_copyright',
            'type'  => 'wp_editor',
            'title' => '页脚版权',
        ),

        array(
            'id'       => 'footer_statistics',
            'type'     => 'code_editor',
            'title'    => '网站统计',
            'settings' => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'default' => '',
        ),
    )
));

//
// SEO设置
//
CSF::createSection($prefix, array(
    'title' => 'SEO设置',
    'icon'  => 'fas fa-bolt',
    'fields' => array(

        array(
            'id'          => 'site_title',
            'type'        => 'text',
            'title'       => '网站标题',
            'placeholder' => '网站标题'
        ),

        array(
            'id'          => 'site_keyword',
            'type'        => 'text',
            'title'       => '首页关键词',
            'placeholder' => '首页关键词',
            'after'    => '<p>请用英文逗号分割.</p>',
        ),

        array(
            'id'          => 'site_description',
            'type'        => 'textarea',
            'title'       => '首页描述',
            'placeholder' => '首页描述',
        ),

    )
));
