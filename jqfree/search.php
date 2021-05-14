<?php get_header(); ?>

<!--主内容区-->
<div class="main-body mt-20">
	<div class="container">
		<div class="row d-flex flex-wrap">
			<!--主内容区-->
			<article class="column xs-12 sm-12 md-8 mb-10-xs mb-0-md">

				<div class="base-list article-list search-list mb-20">
					<?php jiangqie_breadcrumbs(); ?>
					<?php if (have_posts()) : ?>
						<div class="base-box">
							<?php while (have_posts()) : the_post();
								$thumbnail = jiangqie_thumbnail_src();
								if (!empty($thumbnail)) : ?>
									<div class="simple-item simple-left-side">
										<!--左侧图列表块-->
										<div class="simple-img simple-left-img">
											<a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
												<strong><?php $the_post_category = get_the_category(get_the_ID());
														echo $the_post_category[0]->cat_name; ?></strong>
												<img alt="<?php the_title() ?>" src="<?php echo $thumbnail; ?>" />
											</a>
										</div>
										<div class="simple-content">
											<h2>
												<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
											</h2>
											<p>
												<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_excerpt() ?></a>
											</p>
											<p class="simple-info">
												<?php
												//作者头像和名称
												$list_switch_author_avatar = jiangqie_option('list_switch_author_avatar');
												$list_switch_author_name = jiangqie_option('list_switch_author_name');
												if ($list_switch_author_avatar || $list_switch_author_name) : ?>
													<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="<?php the_author() ?>">
														<?php if ($list_switch_author_avatar) :
															jiangqie_avatar(get_the_author_meta('ID'));
														endif; ?>
														<?php if ($list_switch_author_name) : ?>
															<em><?php the_author() ?></em>
														<?php endif; ?>
													</a> ·
												<?php endif; ?>
												<!-- 浏览数 -->
												<?php jiangqie_post_list_view_count(); ?>
												<!-- 点赞数 -->
												<?php jiangqie_post_list_thumbup_count(); ?>
												<!-- 评论数 -->
												<?php jiangqie_post_list_comment_count(); ?>
												<!-- 发布时间 -->
												<cite><?php echo jiangqie_time_ago(get_the_time('Y-m-d G:i:s')); ?></cite>
											</p>
										</div>
									</div>
								<?php else : ?>
									<div class="simple-item">
										<!--无图单文字列表块-->
										<div class="simple-content">
											<h2>
												<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
											</h2>
											<p>
												<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_excerpt() ?></a>
											</p>
											<p class="simple-info">
												<?php
												//作者头像和名称
												$list_switch_author_avatar = jiangqie_option('list_switch_author_avatar');
												$list_switch_author_name = jiangqie_option('list_switch_author_name');
												if ($list_switch_author_avatar || $list_switch_author_name) : ?>
													<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="<?php the_author() ?>">
														<?php if ($list_switch_author_avatar) :
															jiangqie_avatar(get_the_author_meta('ID'));
														endif; ?>
														<?php if ($list_switch_author_name) : ?>
															<em><?php the_author() ?></em>
														<?php endif; ?>
													</a> ·
												<?php endif; ?>
												<!-- 浏览数 -->
												<?php jiangqie_post_list_view_count(); ?>
												<!-- 点赞数 -->
												<?php jiangqie_post_list_thumbup_count(); ?>
												<!-- 评论数 -->
												<?php jiangqie_post_list_comment_count(); ?>
												<!-- 发布时间 -->
												<cite><?php echo jiangqie_time_ago(get_the_time('Y-m-d G:i:s')); ?></cite>
											</p>
										</div>
									</div>
								<?php endif; ?>
							<?php endwhile; ?>
						</div>
					<?php else : ?>
						<div class="content-wrap">
							<div class="content-view mb-20">
								没有找到文章！
							</div>
						</div>
					<?php endif; ?>
				</div>

			</article>

			<!--侧边栏-->
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>