<?php get_header(); ?>

<!--主内容区-->
<div class="main-body mt-20">
	<div class="container">
		<div class="row d-flex flex-wrap">
			<!--主内容区-->
			<article class="column xs-12 sm-12 md-8 mb-10-xs mb-0-md">

				<div class="base-list article-list search-list mb-20">
					<?php jiangqie_breadcrumbs(); ?>
					
					<?php
						$userdata = get_userdata($author);
						// echo json_encode($userdata);
						
						// global $wp_query;
						// $curauth = $wp_query->get_queried_object();
						// echo json_encode($curauth);

						$author_name = get_user_meta($author, 'nickname', true);
					?>
					
					<div class="author-wap d-flex flex-wrap">
						<div class="column md-2 author-avatar">
						<?php jiangqie_avatar($author); ?>
						</div>
						<div class="column md-10 author-info">
							<h4><?php echo $author_name ?>的文章</h4>
							<p>
								<?php
									echo $userdata->description;
								?>
							</p>
						</div>
					</div>
					
					
					<div class="base-box">
						<!-- 文章列表 -->
					</div>

					<div class="spinner">
						<div class="rect1"></div>
						<div class="rect2"></div>
						<div class="rect3"></div>
						<div class="rect4"></div>
						<div class="rect5"></div>
					</div>
				</div>

			</article>

			<!--侧边栏-->
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>