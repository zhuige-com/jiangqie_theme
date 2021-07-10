/**
 * 酱茄Free主题由酱茄（www.jiangqie.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

jQuery(document).ready(function ($) {
    var loading = false;
    var nomore = false;

    function loadPosts() {
        if (nomore) {
            return;
        }

        if (loading) {
            return;
        }
        loading = true;
        $('.spinner').show();

        let start = $('.post-div').length;
        let params = {
            action: 'ajax_more_posts',
            start: start
        };

        if (gCatId) {
            params.catid = gCatId;
        }

        if (gTagId) {
            params.tagid = gTagId;
        }

        if (gAuthorId) {
            params.author = gAuthorId;
        }

        $.post("/wp-admin/admin-ajax.php", params,
            function (posts, status) {
                nomore = posts.length < 10;

                let tabbox = $('.base-box');

                for (let i = 0; i < posts.length; i++) {
                    let post = posts[i];
                    let element = '';
                    if (post.thumbnail) {
                        element = '<div class="post-div simple-item simple-left-side">'
                        element += '<div class="simple-img simple-left-img">'
                        element += '<a href="' + post.cat_link + '" title="' + post.cat_name + '">'
                        element += '<strong>' + post.cat_name + '</strong>'
                        element += '<img alt="" src="' + post.thumbnail + '" />'
                        element += '</a>'
                        element += '</div>'
                        element += '<div class="simple-content">'
                        element += '<h2>'

                        if (post.stick) {
                            element += '<strong>置顶</strong>'
                        }

                        element += '<a href="' + post.link + '" title="">' + post.title + '</a>'
                        element += '</h2>'
                        element += '<p>' + post.excerpt + '</p>'
                        element += '<p class="simple-info">'

                        if (post.author_avatar != '' || post.author_name != '') {
                            element += '<a href="' + post.author_link + '" title="' + post.author_name + '">'

                            if (post.author_avatar != '') {
                                element += post.author_avatar
                            }

                            if (post.author_name != '') {
                                element += '<em>' + post.author_name + '</em>'
                            }

                            element += '</a> · '
                        }

                        if (post.views_count != '') {
                            element += '<cite>浏览 ' + post.views_count + '</cite> · '
                        }

                        if (post.thumbup_count != '') {
                            element += '<cite>点赞 ' + post.thumbup_count + '</cite> · '
                        }

                        if (post.comment_count != '') {
                            element += '<cite>评论 ' + post.comment_count + '</cite> · '
                        }

                        element += '<cite>' + post.time + '</cite>'

                        element += '</p></div></div>'
                    } else {
                        element += '<div class="post-div simple-item">'
                        element += '<div class="simple-content">'
                        element += '<h2>'

                        if (post.stick) {
                            element += '<strong>置顶</strong>'
                        }

                        element += '<a href="' + post.link + '" title="">' + post.title + '</a>'
                        element += '</h2>'
                        element += '<p>' + post.excerpt + '</p>'
                        element += '<p class="simple-info">'

                        if (post.author_avatar != '' || post.author_name != '') {
                            element += '<a href="' + post.author_link + '" title="' + post.author_name + '">'

                            if (post.author_avatar != '') {
                                element += post.author_avatar
                            }

                            if (post.author_name != '') {
                                element += '<em>' + post.author_name + '</em>'
                            }

                            element += '</a> · '
                        }

                        if (post.views_count != '') {
                            element += '<cite>浏览 ' + post.views_count + '</cite> · '
                        }

                        if (post.thumbup_count != '') {
                            element += '<cite>点赞 ' + post.thumbup_count + '</cite> · '
                        }

                        if (post.comment_count != '') {
                            element += '<cite>评论 ' + post.comment_count + '</cite> · '
                        }

                        element += '<cite>' + post.time + '</cite>'

                        element += '</p></div></div>'
                    }
                    tabbox.append(element);
                }

                loading = false;
                $('.spinner').hide();
            });
    }

    loadPosts();

    $(window).scroll(function (event) {
        if ($(this).scrollTop() + $(window).height() + 200 > $(document).height()) {
            // load data
            loadPosts();
        }
    });

});