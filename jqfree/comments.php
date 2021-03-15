<?php
if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
    die('Please do not load this page directly. Thanks!');
}

if (!comments_open()) {
    echo '文章评论已关闭！';
    return;
}

date_default_timezone_set("Asia/Shanghai");
$closeTimer = (time() - strtotime(get_the_time('Y-m-d G:i:s'))) / 86400;

?>
<h5 class="mb-20 mt-40">发表评论</h5>
<div class="content-post mb-20">
    <p>电子邮件地址不会被公开。 必填项已用*标注</p>
</div>

<div id="respond" class="comment-respond mb-20-xs">
    <?php if (get_option('comment_registration') && !is_user_logged_in()) { ?>
		<a href="<?php echo wp_login_url(get_permalink()); ?>" class="comment-login-textarea">
			<textarea cols="45" rows="8" maxlength="65525" placeholder="请回复有价值的信息，无意义的评论讲很快被删除，账号将被禁止发言。" required="required"></textarea>
		 </a>
        <h3 class="queryinfo comment-login-tip">
            <?php printf('您必须 <a href="%s">登录</a> 才能发表评论！', wp_login_url(get_permalink())); ?>
        </h3> 
    <?php } elseif (get_option('close_comments_for_old_posts') && $closeTimer > get_option('close_comments_days_old')) { ?>
        <h3 class="queryinfo">
            文章评论已关闭！
        </h3>
    <?php } else { ?>

        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
            <div class="comment-form-box <?php echo is_user_logged_in()?'comment-form-login':'' ?>">
                <p class="comment-form-comment">
                    <label for="comment">评论</label>
                    <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" placeholder="请回复有价值的信息，无意义的评论讲很快被删除，账号将被禁止发言。" required="required"></textarea>
                </p>

                <?php if (!is_user_logged_in()) { ?>
                    <ul>
                        <li class="comment-form-author"><label for="author">姓名 *</label> <input id="author" name="author" type="text" value="" size="30" maxlength="245"></li>
                        <li class="comment-form-email"><label for="email">电子邮件 *</label> <input id="email" name="email" type="text" value="" size="30" maxlength="100" aria-describedby="email-notes"></li>
                        <li class="comment-form-url"><label for="url">站点</label> <input id="url" name="url" type="text" value="" size="30" maxlength="200"></li>
                    </ul>
                <?php } else { ?>
                    <p class="comment-form-user">您已登录:<a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="退出登录">退出</a></p>
                <?php } ?>
                <p class="form-submit">
                    <input name="submit" type="submit" id="submit" class="submit" value="发表评论">
                </p>
                <?php comment_id_fields(); ?>
                <?php do_action('comment_form', $post->ID); ?>
            </div>
        </form>

    <?php } ?>
</div>

<h5 class="mb-20">评论信息</h5>
<div class="content-comment mb-20">
    <?php wp_list_comments('type=comment&callback=jiangqie_comment_list') ?>
</div>