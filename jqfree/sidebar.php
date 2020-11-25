<!--侧边栏-->
<aside class="column xs-12 sm-12 md-4">
	<?php
	if (function_exists('dynamic_sidebar') ) {
		dynamic_sidebar('sidebar-site');

		if (is_home()) {
			dynamic_sidebar('sidebar-home');
		} else if(is_tag() || is_category() || is_search()) {
			dynamic_sidebar('sidebar-list');
		} else if (is_single()) {
			dynamic_sidebar('sidebar-post');
		} else if (is_page()) {
			dynamic_sidebar('sidebar-page');
		} else {
			dynamic_sidebar('sidebar-other');
		}
	}
	?>
</aside>