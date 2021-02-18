<?php get_header(); ?>

<!--主内容区-->
<div class="main-body mt-20">
	<div class="container">
		<div class="row d-flex flex-wrap">
			<!--主内容区-->
			<article class="column xs-12 sm-12 md-8 mb-10-xs mb-0-md">

				<div class="base-list article-list search-list mb-20">
					<?php jiangqie_breadcrumbs(); ?>
					<?php if (is_category()): ?>
						<h5 class="mb-10">
							<?php 
							$categories = get_the_category();
							$cat_name = $categories[0]->cat_name;
							$cat_link = get_category_link($categories[0]->cat_ID);
							echo '<a href="' . $cat_link . '">' . $cat_name . '</a>';
							?>
						</h5>
					<?php endif; ?>
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