<?php
/*
Template Name: 酱茄-标签云
*/
?>

<?php get_header(); ?>

<div class="main-body mt-20">
	<div class="container">
		<div class="row d-flex flex-wrap">
			<!--主内容区-->
			<article class="column xs-12 sm-12 md-8 mb-10-xs mb-0-md">

				<div class="base-list search-list mb-20">
					<?php jiangqie_breadcrumbs(); ?>
					<div class="content-wrap">
						<div class="tag-list">
							<?php
							$tags_list = get_tags('orderby=count&order=DESC');
							if ($tags_list) {
								foreach ($tags_list as $tag) {
									echo '<a title="' . $tag->count . '个话题" href="' . get_tag_link($tag) . '">' . $tag->name . ' (' . $tag->count . ')</a>';
								}
							}
							?>
						</div>
					</div>
				</div>

			</article>

			<!--侧边栏-->
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>