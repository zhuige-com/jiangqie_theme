<?php get_header(); ?>

<div class="main-body mt-20">
	<div class="container">
		<div class="row d-flex flex-wrap">
			<!--主内容区-->
			<article class="column xs-12 sm-12 md-8 mb-10-xs mb-0-md">
				<?php if (have_posts()) : the_post();
					jiangqie_update_post_view_count();
				?>

					<div class="base-list search-list mb-20">
						<?php jiangqie_breadcrumbs(); ?>
						<div class="content-wrap">
							<div class="content-author mb-20">
								<h1><?php the_title(); ?></h1>
								<p class="simple-info mb-10-xs">
									<?php
									//作者头像和名称
									$detail_switch_author_avatar = jiangqie_option('detail_switch_author_avatar');
									$detail_switch_author_name = jiangqie_option('detail_switch_author_name');
									if ($detail_switch_author_avatar || $detail_switch_author_name) : ?>
										<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="<?php the_author() ?>">
											<?php if ($detail_switch_author_avatar) :
												jiangqie_avatar(get_the_author_meta('ID'));
											endif; ?>
											<?php if ($detail_switch_author_name) : ?>
												<em><?php the_author() ?></em>
											<?php endif; ?>
										</a> ·
									<?php endif; ?>
									<!-- 浏览数 -->
									<?php jiangqie_post_detail_view_count(); ?>
									<!-- 点赞数 -->
									<?php jiangqie_post_detail_thumbup_count(); ?>
									<!-- 评论数 -->
									<?php jiangqie_post_detail_comment_count(); ?>
									<!-- 发布时间 -->
									<cite><?php echo jiangqie_time_ago(get_the_time('Y-m-d G:i:s')); ?></cite>
								</p>
							</div>
							<div class="content-view mb-20">
								<?php the_content(); ?>
							</div>

							<?php
							//标签信息
							$detail_switch_tag = jiangqie_option('detail_switch_tag');
							if ($detail_switch_tag) : ?>
								<div class="content-tag mb-20">
									<div class="tag-list">
										<?php the_tags('', '', '') ?>
									</div>
								</div>
							<?php endif; ?>

							<?php
							//版权信息
							$detail_switch_copyright = jiangqie_option('detail_switch_copyright');
							if ($detail_switch_copyright) : ?>
								<div class="content-copy mb-20">
									<p><?php echo jiangqie_option('detail_copyright'); ?></p>
								</div>
							<?php endif; ?>

							<?php
							//点赞和打赏
							$detail_switch_thumbup = jiangqie_option('detail_switch_thumbup');
							$detail_switch_reward = jiangqie_option('detail_switch_reward');
							if ($detail_switch_thumbup || $detail_switch_reward) : ?>
								<div class="content-opt mb-20">
									<div class="content-opt-wap">
										<?php if ($detail_switch_thumbup) : ?>
											<div>
												<a href="javascript:;" data-action="jaingqie_thumbup" data-id="<?php the_ID(); ?>" class="btn-thumbup <?php if (isset($_COOKIE['jaingqie_thumbup_' . $post->ID])) echo ' done'; ?>">
													<img alt="" src="<?php echo get_stylesheet_directory_uri() . '/images/zan.png'; ?>" alt="">
													<?php $thumbup = get_post_meta($post->ID, 'jaingqie_thumbup', true); ?>
													<p>已有<cite class="count"><?php echo $thumbup ? $thumbup : '0'; ?></cite>人点赞</p>
												</a>
											</div>
										<?php endif; ?>
										<?php if ($detail_switch_reward) : ?>
											<div>
												<a href="javascript:;" class="btn-reward">
													<img alt="" src="<?php echo get_stylesheet_directory_uri() . '/images/shang.png'; ?>" alt="">
													<p>打赏一下作者</p>
												</a>
											</div>
										<?php endif; ?>
									</div>
								</div>
								<div id="reward-div" style="display: none;">
									<?php jiangqie_reward_image() ?>
								</div>
							<?php endif; ?>

							<?php
							//上一篇 下一篇
							$detail_switch_pre_next = jiangqie_option('detail_switch_pre_next');
							if ($detail_switch_pre_next) : ?>
								<div class="content-nav row d-flex flex-wrap mb-20">
									<div class="content-block column md-6">
										<h6>
											<?php if (get_previous_post()) {
												previous_post_link('%link', '上一篇');
											} else {
												echo "没有了";
											} ?>
										</h6>
										<p>
											<?php if (get_previous_post()) {
												previous_post_link('%link');
											} else {
												echo "没有了";
											} ?>
										</p>
									</div>
									<div class="content-block column md-6">
										<h6>
											<?php if (get_next_post()) {
												next_post_link('%link', '下一篇');
											} else {
												echo "没有了";
											} ?>
										</h6>
										<p>
											<?php if (get_next_post()) {
												next_post_link('%link');
											} else {
												echo "没有了";
											} ?>
										</p>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<?php
						//猜你喜欢
						$detail_switch_recommend = jiangqie_option('detail_switch_recommend');
						if ($detail_switch_recommend) : ?>
							<h5 class="mb-20">猜你喜欢</h5>
							<div class="row d-flex flex-wrap mb-20">
								<?php
								$args = array(
									'post_status' => 'publish',
									'post__not_in' => [$post->ID],
									'ignore_sticky_posts' => 1,
									'orderby' => 'comment_date',
									'posts_per_page' => 3
								);
								$posttags = get_the_tags();
								if ($posttags) {
									$tags = '';
									foreach ($posttags as $tag) {
										$tags .= $tag->term_id . ',';
									}
									$args['tag__in'] = explode(',', $tags);
								}
								query_posts($args);
								while (have_posts()) {
									the_post();
									$thumbnail = jiangqie_thumbnail_src();
									if (empty($thumbnail)) {
										$thumbnail = get_stylesheet_directory_uri() . '/images/jiangqie.png';
									}
								?>
									<div class="column md-4 easy-item">
										<figure class="relative">
											<div>
												<a href="<?php the_permalink(); ?>">
													<img src="<?php echo $thumbnail; ?>" alt="<?php the_title(); ?>">
												</a>
											</div>
										</figure>
										<figcaption class="title">
											<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										</figcaption>
									</div>
								<?php
								}
								wp_reset_query();
								?>
							</div>
						<?php endif; ?>

						<!-- 评论 -->
						<?php comments_template(); ?>

					</div>

				<?php else : ?>
					<div class="base-list search-list mb-20">
						<?php jiangqie_breadcrumbs(); ?>
						<div class="content-wrap">
							<div class="content-view mb-20">
								没有找到文章！
							</div>
						</div>
					</div>
				<?php endif; ?>

			</article>

			<!--侧边栏-->
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>