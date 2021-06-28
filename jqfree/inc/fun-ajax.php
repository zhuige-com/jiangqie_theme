<?php

/**
 * 酱茄Free主题由酱茄（www.jiangqie.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

/**
 * 点赞功能
 */
add_action('wp_ajax_nopriv_jaingqie_thumbup', 'jaingqie_thumbup');
add_action('wp_ajax_jaingqie_thumbup', 'jaingqie_thumbup');
function jaingqie_thumbup()
{
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
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
 * 打赏图片
 */
// add_action('wp_ajax_nopriv_jaingqie_reward', 'jaingqie_reward');
// add_action('wp_ajax_jaingqie_reward', 'jaingqie_reward');
// function jaingqie_reward()
// {
//     $detail_reward_image = jiangqie_option('detail_reward_image');
//     if ($detail_reward_image && $detail_reward_image['url']) {
//         $data = [
//             'error' => '',
//             'image' => $detail_reward_image['url']
//         ];
//     } else {
//         $data = [
//             'error' => '请在后台配置打赏二维码'
//         ];
//     }

//     echo json_encode($data);
//     die;
// }


/**
 * 加载文章
 */
add_action('wp_ajax_nopriv_ajax_more_posts', 'ajax_more_posts');
add_action('wp_ajax_ajax_more_posts', 'ajax_more_posts');

/**
 * 获取摘要
 */
function _getExcerpt($post)
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

function _getThumbnail($post_id, $post_content)
{
    $post_thumbnail_src = '';
    if (has_post_thumbnail($post_id)) {    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
        $post_thumbnail_src = $thumbnail_src[0];
    } else {
        // ob_start();
        // ob_end_clean();
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
function _timeAgo($ptime)
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
 * 获取文章
 */
function _getPosts($args)
{
    $query = new WP_Query();
    $result = $query->query($args);

    $posts = [];
    foreach ($result as $post) {
        $item = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'excerpt' => _getExcerpt($post),
            'thumbnail' => _getThumbnail($post->ID, $post->post_content),
            'link' => get_permalink($post->ID)
        ];

        //置顶
        // $stickies = get_option('sticky_posts');
        // if (!is_array($stickies)) {
        //     $stickies = [];
        // }ies
        // $item['stick'] = in_array($post->ID, $stickies, true);

        //分类
        $categories = get_the_category($post->ID);
        $item['cat_name'] = $categories[0]->cat_name;
        $item['cat_link'] = get_category_link($categories[0]->cat_ID);

        if (jiangqie_option('list_switch_author_avatar')) {
            $author_avatar = get_user_meta($post->post_author, 'jiangqie_avatar', true);
            if (empty($author_avatar)) {
                $author_avatar = get_stylesheet_directory_uri() . '/images/default_avatar.jpg';
            }
            $item['author_avatar'] = '<img alt="" src="' . $author_avatar . '" />';
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

        $item['time'] = _timeAgo($post->post_date);

        $posts[] = $item;
    }

    return $posts;
}

/**
 * 文章列表
 */
function ajax_more_posts()
{
    header("Content-Type: application/json");

    $start = $_POST['start'];
    if (empty($start)) {
        $start = 0;
    }

    $args = [
        'posts_per_page' => 10,
        'offset' => $start,
        'orderby' => 'date',
        'post_status' => ['publish']
    ];

    if (isset($_POST['tagid'])) {
        $catid = $_POST['tagid'];
        $args['tag_id'] = $catid;
    } else if (isset($_POST['author'])) {
        $author = $_POST['author'];
        $args['author'] = $author;
    } else if (isset($_POST['catid'])) {
        $catid = $_POST['catid'];
        $args['cat'] = $catid;
    } else {
        $home_cat_show = jiangqie_option('home_cat_show');
        if (!empty($home_cat_show)) {
            $args['category__in'] = implode(",", $home_cat_show);
        }
    }

    $posts = _getPosts($args);

    echo json_encode($posts);
    die;
}
