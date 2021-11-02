<?php
require_once get_theme_file_path() . '/inc/codestar-framework/codestar-framework.php';
require_once get_theme_file_path() . '/inc/admin-options.php';
require_once get_theme_file_path() . '/inc/fun-menus.php';
require_once get_theme_file_path() . '/inc/fun-widgets.php';
require_once get_theme_file_path() . '/inc/fun-ajax.php';
require_once get_theme_file_path() . '/inc/jiangqie-dashboard.php';
require_once get_theme_file_path() . '/inc/jiangqie-pages.php';
require_once get_theme_file_path() . '/inc/jiangqie-user-avatar.php';

/**
 * 开启链接管理
 */
add_filter('pre_option_link_manager_enabled', '__return_true');

/**
 * 移除图片的宽高属性
 */
add_filter('post_thumbnail_html', 'remove_width_attribute', 10);
add_filter('image_send_to_editor', 'remove_width_attribute', 10);
function remove_width_attribute($html)
{
    $html = preg_replace('/(width|height)="\d*"\s/', "", $html);
    return $html;
}

/**
 * 开启特色图功能
 */
if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
}

// 在init action处注册脚本，可以与其它逻辑代码放在一起
function jiangqie_theme_init()
{
    $url = get_template_directory_uri();

    // 注册脚本
    // wp_register_script('jquery', 'https://cdn.bootcdn.net/ajax/libs/jquery/1.9.1/jquery.min.js');
    wp_register_script('lib-script', $url . '/js/lib/lb.js', [], '0.3');
    wp_register_script('lib-layer', $url . '/js/layer/layer.js', ['jquery'], '1.0', false);
    wp_register_script('jq-footer-script', $url . '/js/jiangqie.footer.js', ['jquery'], '0.3', true);
    wp_register_script('jq-index-script', $url . '/js/jiangqie.index.js', ['jquery', 'lib-script'], '0.4', true);
    wp_register_script('jq-archive-script', $url . '/js/jiangqie.archive.js', ['jquery'], '0.4', true);
    wp_register_script('jq-single-script', $url . '/js/jiangqie.single.js', ['lib-layer'], '0.3', true);


    // 其它需要在init action处运行的脚本
}
add_action('init', 'jiangqie_theme_init');


function jiangqie_scripts()
{
    //全局加载js脚本
    wp_enqueue_script('jquery');
    wp_enqueue_script('lib-script');
    wp_enqueue_script('jq-footer-script');

    if (is_home()) {
        wp_enqueue_script('jq-index-script');
    }

    if (is_archive()) {
        wp_enqueue_script('jq-archive-script');
    }

    if (is_single()) {
        wp_enqueue_script('lib-layer');
        wp_enqueue_script('jq-single-script');
    }
}
add_action('wp_enqueue_scripts', 'jiangqie_scripts');

/**
 *  清除谷歌字体 
 */
function jiangqie_remove_open_sans_from_wp_core()
{
    wp_deregister_style('open-sans');
    wp_register_style('open-sans', false);
    wp_enqueue_style('open-sans', '');
}
add_action('init', 'jiangqie_remove_open_sans_from_wp_core');

/**
 * 清除wp_head无用内容 
 */
function remove_dns_prefetch($hints, $relation_type)
{
    if ('dns-prefetch' === $relation_type) {
        return array_diff(wp_dependencies_unique_hosts(), $hints);
    }
    return $hints;
}
function jiangqie_remove_laji()
{
    remove_action('wp_head', 'wp_generator'); //移除WordPress版本
    remove_action('wp_head', 'rsd_link'); //移除离线编辑器开放接口
    remove_action('wp_head', 'wlwmanifest_link'); //移除离线编辑器开放接口
    remove_action('wp_head', 'index_rel_link'); //去除本页唯一链接信息
    remove_action('wp_head', 'feed_links', 2); //移除feed
    remove_action('wp_head', 'feed_links_extra', 3); //移除feed
    remove_action('wp_head', 'rest_output_link_wp_head', 10); //移除wp-json链
    remove_action('wp_head', 'print_emoji_detection_script', 7); //头部的JS代码
    remove_action('wp_head', 'wp_print_styles', 8); //emoji载入css
    remove_action('wp_head', 'rel_canonical'); //rel=canonical
    add_filter('wp_resource_hints', 'remove_dns_prefetch', 10, 2); //头部加载DNS预获取（dns-prefetch）
}
add_action('init', 'jiangqie_remove_laji');


function jiangqie_setup()
{
    //关键字
    add_action('wp_head', 'jiangqie_seo_keywords');

    //页面描述 
    add_action('wp_head', 'jiangqie_seo_description');

    //网站图标
    add_action('wp_head', 'jiangqie_favicon');
}
add_action('after_setup_theme', 'jiangqie_setup');

add_action('admin_init', 'jiangqie_theme_free_on_admin_init');
add_action('admin_menu', 'jiangqie_theme_free_add_admin_menu', 20);
function jiangqie_theme_free_add_admin_menu()
{
    add_submenu_page('jiangqie-free', '', '安装文档', 'manage_options', 'jiangqie_theme_free_setup', 'jiangqie_theme_free_handle_external_redirects');
    add_submenu_page('jiangqie-free', '', '专业版主题', 'manage_options', 'jiangqie_theme_free_upgrade', 'jiangqie_theme_free_handle_external_redirects');
}

function jiangqie_theme_free_on_admin_init()
{
    jiangqie_theme_free_handle_external_redirects();
}

function jiangqie_theme_free_handle_external_redirects()
{
    if (empty($_GET['page'])) {
        return;
    }

    if ('jiangqie_theme_free_setup' === $_GET['page']) {
        wp_redirect('https://www.jiangqie.com/theme/6177.html');
        die;
    }

    if ('jiangqie_theme_free_upgrade' === $_GET['page']) {
        wp_redirect('https://pro.jiangqie.com');
        die;
    }
}

/**
 * 缩略图
 */
function jiangqie_thumbnail_src()
{
    global $post;
    return jiangqie_thumbnail_src_d($post->ID, $post->post_content);
}

function jiangqie_thumbnail_src_d($post_id, $post_content)
{
    $post_thumbnail_src = '';
    if (has_post_thumbnail($post_id)) {    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
        $post_thumbnail_src = $thumbnail_src[0];
    } else {
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);
        if ($matches && isset($matches[1]) && isset($matches[1][0])) {
            $post_thumbnail_src = $matches[1][0];   //获取该图片 src
        }
    };
    return $post_thumbnail_src;
}


/**
 * 美化时间
 */
function jiangqie_time_ago($ptime)
{
    date_default_timezone_set("Asia/Shanghai");
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if ($etime < 1) return '刚刚';
    $interval = array(
        12 * 30 * 24 * 60 * 60  =>  '年前 (' . date('Y-m-d', $ptime) . ')',
        30 * 24 * 60 * 60       =>  '个月前 (' . date('m-d', $ptime) . ')',
        7 * 24 * 60 * 60        =>  '周前 (' . date('m-d', $ptime) . ')',
        24 * 60 * 60            =>  '天前',
        60 * 60                 =>  '小时前',
        60                      =>  '分钟前',
        1                       =>  '秒前'
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}

/**
 * 设置项的值
 */
$jiangqie_options = null;
function jiangqie_option($key, $default='')
{
    global $jiangqie_options;
    if (!$jiangqie_options) {
        $jiangqie_options = get_option('jiangqie_free');
    }

    if (isset($jiangqie_options[$key])) {
        return $jiangqie_options[$key];
    }

    return $default;
}

/**
 * 设置文章浏览量
 */
function jiangqie_update_post_view_count()
{
    global $post;
    $post_views = (int) get_post_meta($post->ID, "views", true);
    if (!update_post_meta($post->ID, 'views', ($post_views + 1))) {
        add_post_meta($post->ID, 'views', 1, true);
    }
}


/**
 * 列表-浏览数
 */
function jiangqie_post_list_view_count()
{
    global $post;
    if (jiangqie_option('list_switch_view_count')) {
        $count = get_post_meta($post->ID, "views", true);
        if (!$count) {
            $count = 0;
        }
        echo '<cite>浏览 ' . $count . '</cite> ·';
    }

    echo '';
}

/**
 * 详情-浏览数
 */
function jiangqie_post_detail_view_count()
{
    global $post;
    if (jiangqie_option('detail_switch_view_count')) {
        $count = get_post_meta($post->ID, "views", true);
        if (!$count) {
            $count = 0;
        }
        echo '<cite>浏览 ' . $count . '</cite> ·';
    }

    echo '';
}

/**
 * 列表-点赞数
 */
function jiangqie_post_list_thumbup_count()
{
    global $post;
    if (jiangqie_option('list_switch_thumbup_count')) {
        $count = get_post_meta($post->ID, "jaingqie_thumbup", true);
        if (!$count) {
            $count = 0;
        }
        echo '<cite>点赞 ' . $count . '</cite> ·';
    }

    echo '';
}

/**
 * 详情-点赞数
 */
function jiangqie_post_detail_thumbup_count()
{
    global $post;
    if (jiangqie_option('detail_switch_thumbup_count')) {
        $count = get_post_meta($post->ID, "jaingqie_thumbup", true);
        if (!$count) {
            $count = 0;
        }
        echo '<cite>点赞 ' . $count . '</cite> ·';
    }

    echo '';
}

/**
 * 列表-评论数
 */
function jiangqie_post_list_comment_count()
{
    if (jiangqie_option('list_switch_comment_count')) {
        echo '<cite>评论 ' . get_comments_number() . '</cite> ·';
    }

    echo '';
}

/**
 * 详情-评论数
 */
function jiangqie_post_detail_comment_count()
{
    if (jiangqie_option('detail_switch_comment_count')) {
        echo '<cite>评论 ' . get_comments_number() . '</cite> ·';
    }

    echo '';
}

/**
 * 面包屑导航
 */
function jiangqie_breadcrumbs()
{
    if ((is_category() || is_tag()) && !jiangqie_option('list_switch_bread')) {
        return '';
    }

    if (is_single() && !jiangqie_option('detail_switch_bread')) {
        return '';
    }

    $delimiter = '<em> > </em>'; // 分隔符
    $before = '<span class="current">'; // 在当前链接前插入
    $after = '</span>'; // 在当前链接后插入
    if (!is_home() && !is_front_page() || is_paged()) {
        echo '<div class="base-list-nav" itemscope="">' . __('', 'cmp');
        global $post;
        $homeLink = home_url() . '/';
        echo '<a itemprop="breadcrumb" href="' . $homeLink . '">' . __('首页', 'cmp') . '</a> ' . $delimiter . ' ';
        if (is_404()) { // 404 页面
            echo $before;
            _e('404', 'cmp');
            echo $after;
        } else if (is_category()) { // 分类 存档
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) {
                $cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
                echo $cat_code = str_replace('<a', '<a itemprop="breadcrumb"', $cat_code);
            }
            echo $before . '' . single_cat_title('', FALSE) . '' . $after;
        } elseif (is_day()) { // 天 存档
            echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif (is_month()) { // 月 存档
            echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif (is_year()) { // 年 存档
            echo $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) { // 文章
            if (get_post_type() != 'post') { // 自定义文章类型
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a itemprop="breadcrumb" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
                echo $before . get_the_title() . $after;
            } else { // 文章 post
                $cat = get_the_category();
                $cat = $cat[0];
                $cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                echo $cat_code = str_replace('<a', '<a itemprop="breadcrumb"', $cat_code);
                echo $before . '正文' . $after;
            }
        } elseif (is_attachment()) { // 附件
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo '<a itemprop="breadcrumb" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif (is_page() && !$post->post_parent) { // 页面
            echo $before . get_the_title() . $after;
        } elseif (is_page() && $post->post_parent) { // 父级页面
            $parent_id = $post->post_parent;
            $breadcrumbs = [];
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif (is_search()) { // 搜索结果
            printf(__('搜索：%s', 'cmp'), get_search_query());
        } elseif (is_tag()) { //标签 存档
            echo $before;
            printf(__('标签：%s', 'cmp'), single_tag_title('', FALSE));
            echo $after;
        } elseif (is_author()) { // 作者存档
            global $author;
            $userdata = get_userdata($author);
            echo $before;
            printf(__('作者：%s', 'cmp'), $userdata->display_name);
            echo $after;
        } elseif (!is_single() && !is_page() && get_post_type() != 'post') {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        }

        if (get_query_var('paged')) { // 分页
            if (is_category() || is_day() || is_month() || is_year()  || is_tag() || is_author())
                echo sprintf(__('( Page %s )', 'cmp'), get_query_var('paged'));
        }
        echo '</div>';
    }
}

/**
 * 首页精选文章
 */
function home_post_recommend()
{
    $hot_ids = jiangqie_option('home_post_recommend');
    if (empty($hot_ids)) {
        return false;
    }

    $args = [
        'post__in' => $hot_ids,
        'orderby' => 'post__in',
        'posts_per_page' => -1,
        'ignore_sticky_posts' => 1
    ];

    $hots = [];
    $query = new WP_Query();
    $result = $query->query($args);
    foreach ($result as $post) {
        $thumbnail = jiangqie_thumbnail_src_d($post->ID, $post->post_content);
        if (empty($thumbnail)) {
            $thumbnail = get_stylesheet_directory_uri() . '/images/jiangqie.png';
        }
        $hots[] = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'thumbnail' => $thumbnail
        ];
    }

    if (empty($hots)) {
        return false;
    }

    return $hots;
}

/**
 * 底部热门推荐文章
 */
function footer_hot_recommend()
{
    $hot_ids = jiangqie_option('footer_hot_recommend');
    if (empty($hot_ids)) {
        $args = [
            'posts_per_page' => 3,
            'ignore_sticky_posts' => 1
        ];
    } else {
        $args = [
            'post__in' => $hot_ids,
            'orderby' => 'post__in',
            'posts_per_page' => -1,
            'ignore_sticky_posts' => 1
        ];
    }

    $hots = [];
    $query = new WP_Query();
    $result = $query->query($args);
    foreach ($result as $post) {
        $hots[] = [
            'id' => $post->ID,
            'title' => $post->post_title
        ];
    }

    if (empty($hots)) {
        return false;
    }

    return $hots;
}

/* ---- SEO start ---- */
//标题
function jiangqie_seo_title()
{
    $site_title = jiangqie_option('site_title');
    if (is_home() && !empty($site_title)) {
        echo $site_title;
    } else {
        global $page, $paged;
        wp_title('-', true, 'right');

        // 添加网站标题.
        bloginfo('name');

        // 如果有必要，在标题上显示一个页面数.
        if ($paged >= 2 || $page >= 2) {
            echo ' - ' . sprintf('第%s页', max($paged, $page));
        }
    }
}

//关键字
function jiangqie_seo_keywords()
{
    global $s, $post;
    $keywords = '';
    if (is_single()) {
        if (get_the_tags($post->ID)) {
            foreach (get_the_tags($post->ID) as $tag) $keywords .= $tag->name . ', ';
        }
        foreach (get_the_category($post->ID) as $category) $keywords .= $category->cat_name . ', ';
        $keywords = substr_replace($keywords, '', -2);
    } elseif (is_home()) {
        $keywords = jiangqie_option('site_keyword');
    } elseif (is_tag()) {
        $keywords = single_tag_title('', false);
    } elseif (is_category()) {
        $keywords = single_cat_title('', false);
    } elseif (is_search()) {
        $keywords = esc_html($s, 1);
    } else {
        $keywords = trim(wp_title('', false));
    }
    if ($keywords) {
        echo "<meta name=\"keywords\" content=\"$keywords\">\n";
    }
}

//描述
function jiangqie_seo_description()
{
    global $s, $post;
    $description = '';
    $blog_name = get_bloginfo('name');
    if (is_singular()) {
        if (!empty($post->post_excerpt)) {
            $text = $post->post_excerpt;
        } else {
            $text = $post->post_content;
        }
        $description = trim(str_replace(array("\r\n", "\r", "\n", "　", " "), " ", str_replace("\"", "'", strip_tags($text))));
        if (!($description)) $description = $blog_name . "-" . trim(wp_title('', false));
    } elseif (is_home()) {
        $description = jiangqie_option('site_description');
    } elseif (is_tag()) {
        $description = $blog_name . "'" . single_tag_title('', false) . "'";
    } elseif (is_category()) {
        $description = trim(strip_tags(category_description()));
    } elseif (is_archive()) {
        $description = $blog_name . "'" . trim(wp_title('', false)) . "'";
    } elseif (is_search()) {
        $description = $blog_name . ": '" . esc_html($s, 1) . "' 的搜索結果";
    } else {
        $description = $blog_name . "'" . trim(wp_title('', false)) . "'";
    }
    $description = mb_substr($description, 0, 220, 'utf-8');
    echo "<meta name=\"description\" content=\"$description\">\n";
}
/* ---- SEO end ---- */

/**
 * 站点LOGO
 */
function jiangqie_site_logo()
{
    $logo = jiangqie_option('site_logo');
    if ($logo && $logo['url']) {
        echo '<img alt="picture loss" src="' . $logo['url'] . '" alt="' . get_bloginfo('name') . '" />';
    } else {
        echo '酱茄';
    }
}

/**
 * favicon
 */
function jiangqie_favicon()
{
    $favicon = jiangqie_option('site_favicon');
    if ($favicon && $favicon['url']) {
        echo '<link rel="shortcut icon" type="image/x-icon" href="' . $favicon['url'] . '" />';
    } else {
        echo '';
    }
}

/**
 * 首页显示分类
 */
function jiangqie_nav_catsegories()
{
    $home_cat_show = jiangqie_option('home_cat_show');
    $categories = [];
    if (!empty($home_cat_show)) {
        $include = implode(",", $home_cat_show);
        $args = ['include' => $include];
        $result = get_categories($args);
        foreach ($home_cat_show as $cat_id) {
            foreach ($result as $cat) {
                if ($cat_id == $cat->term_id) {
                    $categories[] = $cat;
                }
            }
        }
    } else {
        $categories = get_categories();
    }

    return $categories;
}

//评论样式
function jiangqie_comment_list($comment, $args, $depth)
{
    echo '<div class="content-comment-item content-comment-item-depth-' . $depth . '">';
    echo '<p class="simple-info">';
    echo '<a href="' . ($comment->comment_author_url ? $comment->comment_author_url : '#') . '" title="">';

    jiangqie_avatar($comment->user_id);

    $comment_author = get_user_meta($comment->user_id, 'nickname', true);
    if (empty($comment_author)) {
        $comment_author = $comment->comment_author;
    }

    echo '<em>' . $comment_author  . '</em>';

    echo '</a>';
    echo comment_reply_link(array_merge($args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'])));
    echo edit_comment_link();
    echo get_comment_time('Y-m-d H:i ');
    echo '</p>';
    echo '<div class="content-comment-info">';
    echo '<p class="content-comment-text">' . get_comment_text() . '</p>';
    echo '</div>';
    echo '</div>';
}

/**
 * 打赏图片
 */
function jiangqie_reward_image()
{
    $image = jiangqie_option('detail_reward_image');
    if ($image && $image['url']) {
        echo '<img src="' . $image['url'] . '" alt="' . get_bloginfo('name') . '" />';
    } else {
        echo '请在后台配置打赏二维码';
    }
}

/**
 * 酱茄头像
 */
function jiangqie_avatar($user_id)
{
    $avatar = get_user_meta($user_id, 'jiangqie_avatar', true);
    if (empty($avatar)) {
        $avatar = get_stylesheet_directory_uri() . '/images/default_avatar.jpg';
    }

    echo '<img alt="picture loss" src="' . $avatar . '" />';
}
