<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access directly.

/**
 * 酱茄Free主题由追格（www.zhuige.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

/**
 * 点赞功能
 */
add_action('wp_ajax_nopriv_jaingqie_thumbup', 'jaingqie_theme_thumbup');
add_action('wp_ajax_jaingqie_thumbup', 'jaingqie_theme_thumbup');
function jaingqie_theme_thumbup()
{
    $id = isset($_POST["um_id"]) ? (int)($_POST["um_id"]) : 0;
    $action = isset($_POST["um_action"]) ? sanitize_text_field(wp_unslash($_POST["um_action"])) : '';
    if ($action == 'jaingqie_thumbup') {
        $specs_raters = get_post_meta($id, 'jaingqie_thumbup', true);
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
        setcookie('jaingqie_thumbup_' . $id, $id, $expire, '/', $domain, false);
        if (!$specs_raters || !is_numeric($specs_raters)) {
            update_post_meta($id, 'jaingqie_thumbup', 1);
        } else {
            update_post_meta($id, 'jaingqie_thumbup', ($specs_raters + 1));
        }
        echo get_post_meta($id, 'jaingqie_thumbup', true);
    }
    die;
}

/**
 * 加载文章
 */
add_action('wp_ajax_nopriv_ajax_more_posts', 'jiangqie_theme_more_posts');
add_action('wp_ajax_ajax_more_posts', 'jiangqie_theme_more_posts');

/**
 * 获取摘要
 */
function _jiangqie_theme_get_excerpt($post)
{
    $length = jiangqie_option('home_excerpt_length');
    if (!$length) {
        $length = 50;
    }

    if ($post->post_excerpt) {
        return html_entity_decode(wp_trim_words($post->post_excerpt, $length, '...'));
    } else {
        $content = apply_filters('the_content', $post->post_content);
        return html_entity_decode(wp_trim_words($content, $length, '...'));
    }
}

function _jiangqie_theme_get_thumb($post_id, $post_content)
{
    $post_thumbnail_src = '';
    if (has_post_thumbnail($post_id)) {    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
        if ($thumbnail_src) {
            $post_thumbnail_src = $thumbnail_src[0];
        }
    } 
    
    if (empty($post_thumbnail_src)) {
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
function _jiangqie_theme_time_ago($ptime)
{
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if ($etime < 1) return '刚刚';
    $interval = array(
        12 * 30 * 24 * 60 * 60  =>  '年前 (' . wp_date('Y-m-d', $ptime) . ')',
        30 * 24 * 60 * 60       =>  '个月前 (' . wp_date('m-d', $ptime) . ')',
        7 * 24 * 60 * 60        =>  '周前 (' . wp_date('m-d', $ptime) . ')',
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
 * 获取置顶文章
 */

/**
 * 格式化文章
 */
function _jiangqie_theme_format_post($post)
{
    $item = [
        'id' => $post->ID,
        'title' => $post->post_title,
        'excerpt' => _jiangqie_theme_get_excerpt($post),
        'thumbnail' => _jiangqie_theme_get_thumb($post->ID, $post->post_content),
        'link' => get_permalink($post->ID)
    ];

    //分类
    $categories = get_the_category($post->ID);
    $item['cat_name'] = $categories[0]->cat_name;
    $item['cat_link'] = get_category_link($categories[0]->cat_ID);

    if (jiangqie_option('list_switch_author_avatar')) {
        $author_avatar = get_user_meta($post->post_author, 'jiangqie_avatar', true);
        if (empty($author_avatar)) {
            $author_avatar = get_stylesheet_directory_uri() . '/images/default_avatar.jpg';
        }
        $item['author_avatar'] = '<img alt="picture loss" src="' . $author_avatar . '" />';
    } else {
        $item['author_avatar'] = '';
    }

    if (jiangqie_option('list_switch_author_name')) {
        $item['author_name'] = get_user_meta($post->post_author, 'nickname', true);
    } else {
        $item['author_name'] = '';
    }

    if ($item['author_avatar'] || $item['author_name']) {
        $item['author_link'] = get_author_posts_url($post->post_author);
    }

    if (jiangqie_option('list_switch_view_count')) {
        $count = get_post_meta($post->ID, "views", true);
        if (!$count) {
            $count = 0;
        }
        $item['views_count'] = $count;
    } else {
        $item['views_count'] = '';
    }

    if (jiangqie_option('list_switch_thumbup_count')) {
        $count = get_post_meta($post->ID, "jaingqie_thumbup", true);
        if (!$count) {
            $count = 0;
        }
        $item['thumbup_count'] = $count;
    } else {
        $item['thumbup_count'] = '';
    }

    if (jiangqie_option('list_switch_comment_count')) {
        $item['comment_count'] = $post->comment_count;
    } else {
        $item['comment_count'] = '';
    }

    $item['time'] = _jiangqie_theme_time_ago($post->post_date_gmt);

    return $item;
}

/**
 * 获取置顶的文章
 */
function _jiangqie_theme_get_stick_posts()
{
    $stickies = get_option('sticky_posts');
    if (!is_array($stickies) || empty($stickies)) {
        return [];
    }

    $query = new WP_Query();
    $result = $query->query([
        'post__in' => $stickies,
    ]);

    $posts = [];
    foreach ($result as $post) {
        $item = _jiangqie_theme_format_post($post);
        $item['stick'] = 1;
        $posts[] = $item;
    }

    return $posts;
}

/**
 * 根据参数获取文章
 */
function _jiangqie_theme_get_posts($args)
{
    $query = new WP_Query();
    $result = $query->query($args);

    $posts = [];
    foreach ($result as $post) {
        $posts[] = _jiangqie_theme_format_post($post);
    }

    return $posts;
}

/**
 * 文章列表
 */
function jiangqie_theme_more_posts()
{
    header("Content-Type: application/json");

    $start = isset($_POST['start']) ? (int)($_POST['start']) : 0;

    $args = [
        'posts_per_page' => 10,
        'offset' => $start,
        'orderby' => 'date',
        'post_status' => ['publish'],
    ];

    $tag_id = isset($_POST['tagid']) ? (int)($_POST['tagid']) : 0;
    $author = isset($_POST['author']) ? (int)($_POST['author']) : 0;
    $cat_id = isset($_POST['catid']) ? (int)($_POST['catid']) : 0;

    $stick_posts = [];
    if ($tag_id) {
        $args['tag_id'] = $tag_id;
    } else if ($author) {
        $args['author'] = $author;
    } else if ($cat_id) {
        $args['cat'] = $cat_id;
    } else {
        $home_cat_show = jiangqie_option('home_cat_show');
        if (!empty($home_cat_show)) {
            $args['category__in'] = $home_cat_show;
        }

        $stickies = get_option('sticky_posts');
        if (is_array($stickies) && !empty($stickies)) {
            $args['post__not_in'] = $stickies;
        }

        if ($start == 0) {
            $stick_posts = _jiangqie_theme_get_stick_posts();
        }
    }

    $posts = _jiangqie_theme_get_posts($args);

    echo json_encode(array_merge($stick_posts, $posts));

    die;
}

/**
 * 市场相关
 */
add_action('wp_ajax_nopriv_zhuige_market_event', 'zhuige_market_event');
add_action('wp_ajax_zhuige_market_event', 'zhuige_market_event');
function zhuige_market_event()
{
    $action = isset($_POST["zgaction"]) ? sanitize_text_field(wp_unslash($_POST["zgaction"])) : '';

    if ($action == 'get_list') { // 查询产品
        $cat = isset($_POST["cat"]) ? (int)($_POST["cat"]) : 0;
        $params = [];
        if ($cat) {
            $params['cat'] = $cat;
        }

        $free = isset($_POST["free"]) ? sanitize_text_field($_POST["free"]) : '';
        if ($free !== '') {
            $params['free'] = $free;
        }

        $init = isset($_POST["init"]) ? (int)($_POST["init"]) : 0;
        if ($init == 1) {
            $params['init'] = $init;
        }

        $response = wp_remote_post("https://www.zhuige.com/api/market/list", array(
            'method'      => 'POST',
            'body'        => $params
        ));

        if (is_wp_error($response) || $response['response']['code'] != 200) {
            wp_send_json_error();
        }

        $data = json_decode($response['body'], TRUE);
        $datadata = $data['data'];

        if ($data['code'] == 1) {
            wp_send_json_success($datadata);
        } else {
            wp_send_json_error();
        }
    }

    die;
}
