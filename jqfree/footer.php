<!--页脚-->
<footer>
	<!--页脚图标链接-->
	<?php $footer_icons = jiangqie_option('footer_icons');
	if (is_array($footer_icons) && !empty($footer_icons)) { ?>
		<div class="container ta-c pb-30 main-footer-links">
			<?php
			foreach ($footer_icons as $icon) : ?>
				<a style="color: white;" href="<?php echo $icon['url'] ?>" title="" target="_blank">
					<img src="<?php echo $icon['image']['url'] ?>" width="50" height="50" />
				</a>
			<?php endforeach; ?>
		</div>
	<?php } ?>
	<div class="container ta-c main-footer">
		<!--页脚分栏-->
		<div class="row d-flex flex-wrap">
			<!--关于我们-->
			<div class="column xs-12 sm-6 md-4 pt-30 pb-30">
				<h3 class="mb-30">关于我们</h3>
				<?php echo jiangqie_option('footer_about'); ?>
			</div>
			<!--页脚导航-->
			<div class="column xs-12 sm-6 md-4 pt-30 pb-30">
				<h3 class="mb-30">快速导航</h3>
				<?php $footer_nav = jiangqie_option('footer_nav');
				if (is_array($footer_nav)) :
					foreach ($footer_nav as $nav) :
						echo '<p><a href="' . $nav['url'] . '" title="' . $nav['title'] . '">' . $nav['title'] . '</a></p>';
					endforeach;
				endif; ?>
			</div>
			<!--页脚资讯列表-->
			<div class="column xs-12 md-4 hidden-mdw pt-30 pb-30">
				<h3 class="mb-30">热门推荐</h3>
				<?php $footer_hot_recommend = footer_hot_recommend();
				if ($footer_hot_recommend) :
					foreach ($footer_hot_recommend as $post) : ?>
						<p><a href="<?php echo get_permalink($post['id']) ?>" title="<?php echo $post['title'] ?>"><?php echo $post['title'] ?></a></p>
				<?php endforeach;
				endif;
				?>
			</div>
		</div>
	</div>
	<!--页脚版权-->
	<div class="container ta-c pt-40 main-footer-copyinfo">
		<?php echo jiangqie_option('footer_copyright'); ?>
	</div>
</footer>

<?php wp_footer(); ?>

<div id="toTop">
	<img src="<?php echo get_stylesheet_directory_uri() . '/images/toTop.png'; ?>">
</div>

<div style="display: none;">
	<script>
		<?php echo jiangqie_option('footer_statistics'); ?>
	</script>
	<div>
		</body>

		</html>