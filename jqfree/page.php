<?php get_header(); ?>

<div class="main-body mt-20">
	<div class="container">
		<div class="row d-flex flex-wrap">
			<!--主内容区-->
			<article class="column xs-12 sm-12 md-8 mb-10-xs mb-0-md">

				<div class="base-list search-list mb-20">
					<?php jiangqie_breadcrumbs(); ?>

					<?php if (have_posts()) : the_post();
						jiangqie_update_post_view_count();
					?>
						<div class="content-wrap">
							<div class="content-author mb-20">
								<h1><?php the_title(); ?></h1>
							</div>
							<div class="content-view mb-20">
								<?php the_content(); ?>
							</div>
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