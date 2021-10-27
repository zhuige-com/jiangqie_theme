<?php get_header(); ?>

<div class="main-body mt-20">
	<div class="container">
		<div class="row d-flex flex-wrap">
			<!--主内容区-->
			<article class="column xs-12 sm-12 md-8 mb-10-xs mb-0-md">
				<!--大图区-->
				<div class="row d-flex flex-wrap mb-20-xs mb-0-md">

					<!--幻灯片-->
					<?php $home_slide = jiangqie_option('home_slide');
					if (is_array($home_slide)) : ?>
						<div class="column xs-12 sm-12 md-9 mb-20-xs mb-0-md">
							<div class="lb-box" id="lb-1">
								<!-- 轮播内容 -->
								<div class="lb-content">
									<?php foreach ($home_slide as $slide) : ?>
										<div class="lb-item active">
											<a href="<?php echo $slide['url'] ?>" target="_blank">
												<img alt="picture loss" src="<?php echo $slide['image']['url'] ?>" />
												<div>
													<h2><?php echo $slide['title'] ?></h2>
												</div>
											</a>
										</div>
									<?php endforeach; ?>
								</div>
								<!-- 轮播标志 -->
								<ol class="lb-sign">
									<?php for ($i = 1; $i <= count($home_slide); $i++) :
										if ($i == 1) echo '<li class="active">' . $i . '</li>';
										else echo '<li>' . $i . '</li>';
									endfor; ?>
								</ol>
								<!-- 轮播控件 -->
								<div class="lb-ctrl left">＜</div>
								<div class="lb-ctrl right">＞</div>
							</div>
						</div>
					<?php endif; ?>

					<!--小图区-->
					<?php $home_slide_ad = jiangqie_option('home_slide_ad');
					if (is_array($home_slide_ad)) : ?>
						<div class="column xs-12 sm-12 md-3">
							<!-- row start-->
							<div class="row d-flex flex-wrap lb-side">
								<!--小图区块-->
								<?php foreach ($home_slide_ad as $slide_ad) : ?>
									<div class="column xs-6 sm-6 md-12 mb-20">
										<figure class="relative">
											<div>
												<a href="<?php echo $slide_ad['url'] ?>" target="_blank">
													<img alt="picture loss" src="<?php echo $slide_ad['image']['url'] ?>" />
												</a>
											</div>
											<figcaption class="absolute bottom left">
												<a href="<?php echo $slide_ad['url'] ?>" target="_blank">
													<span class="title"><?php echo $slide_ad['title'] ?></span>
												</a>
											</figcaption>
										</figure>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

				</div>

				<?php $home_post_recommend = home_post_recommend();
				if ($home_post_recommend) : ?>
					<div class="base-list mb-20">
						<h5 class="mb-20">精选文章</h5>
						<div class="row d-flex flex-wrap mb-0-md">
							<!--横向图文列表-->
							<?php foreach ($home_post_recommend as $post) : ?>
								<div class="column md-4 easy-item">
									<figure class="relative">
										<div>
											<a href="<?php echo get_permalink($post['id']) ?>">
												<img alt="picture loss" src="<?php echo $post['thumbnail'] ?>" />
											</a>
										</div>
									</figure>
									<figcaption class="title">
										<h3><a href="<?php echo get_permalink($post['id']) ?>"><?php echo $post['title'] ?></a></h3>
									</figcaption>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<!--列表tab-->
				<div class="article-list mb-20">
					<div class="tab_nav-wraper">
						<h1>最新文章</h1>

						<ul class="tab_nav">
							<li class="tab-nav-li tabNav_active">
								<text>全部</text>
							</li>
							<?php $categories = jiangqie_nav_catsegories();
							foreach ($categories as $cat) : ?>
								<li class="tab-nav-li" data-catid="<?php echo $cat->term_id; ?>">
									<text><?php echo  $cat->name; ?></text>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

					<!--列表面板区-->
					<ul class="tab_box">
						<li class="tabBox_active">
							<!-- 文章列表 -->
						</li>
					</ul>

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