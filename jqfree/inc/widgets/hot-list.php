<?php
/**
 * 酱茄Free主题由酱茄（www.jiangqie.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

add_action('widgets_init', 'jiangqie_hot_list');

function jiangqie_hot_list()
{
	register_widget('JQ_Widget_HotList');
}

class JQ_Widget_HotList extends WP_Widget
{
	function __construct()
	{
		$widget_ops = ['classname' => 'jaingqie-widget-hot-list', 'description' => '热门文章'];
		$control_ops = [];
		parent::__construct('jaingqie-widget-hot-list', '酱茄-热门文章', $widget_ops, $control_ops);
	}

	function widget($args, $instance)
	{
		extract($args);

		$title        = apply_filters('widget_name', $instance['title']);
		$limit        = $instance['limit'];
		$cat          = $instance['cat'];
		$orderby      = $instance['orderby'];
		$more = $instance['more'];
		$link = $instance['link'];
		$img = $instance['img'];

		$mo = '';
		if ($more != '' && $link != '') {
			$mo = '<a class="btn-more" href="' . $link . '">' . $more . '</a>';
		}
		echo $before_widget;
		echo $before_title . $title . $mo . $after_title;
		echo jiangqie_hot_posts_list($orderby, $limit, $cat, $img);
		echo $after_widget;
	}

	function form($instance)
	{
?>
		<p>
			<label>
				标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</label>
		</p>
		<p>
			<label>
				排序：
				<select style="width:100%;" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']); ?>>评论数</option>
					<option value="date" <?php selected('date', $instance['orderby']); ?>>发布时间</option>
					<option value="rand" <?php selected('rand', $instance['orderby']); ?>>随机</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				分类限制：
				<a style="font-weight:bold;color:#f60;text-decoration:none;" href="javascript:;" title="格式：1,2 &nbsp;表限制ID为1,2分类的文章&#13;格式：-1,-2 &nbsp;表排除分类ID为1,2的文章&#13;也可直接写1或者-1；注意逗号须是英文的">？</a>
				<input style="width:100%;" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo $instance['cat']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				显示数目：
				<input style="width:100%;" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				More 显示文字：
				<input style="width:100%;" id="<?php echo $this->get_field_id('more'); ?>" name="<?php echo $this->get_field_name('more'); ?>" type="text" value="<?php echo $instance['more']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				More 链接：
				<input style="width:100%;" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="url" value="<?php echo $instance['link']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['img'], 'on'); ?> id="<?php echo $this->get_field_id('img'); ?>" name="<?php echo $this->get_field_name('img'); ?>">显示图片
			</label>
		</p>

	<?php
	}
}


function jiangqie_hot_posts_list($orderby, $limit, $cat, $img)
{
	$args = array(
		'order'            => 'DESC',
		'cat'              => $cat,
		'orderby'          => $orderby,
		'showposts'        => $limit,
		'ignore_sticky_posts' => 1
	);
	query_posts($args);
	while (have_posts()) : the_post();
		$thumbnail = jiangqie_thumbnail_src();
		if (!empty($thumbnail)) : 
	?>
		<div class="simple-item simple-left-side">
			<div class="simple-img simple-left-img">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<img alt="" src="<?php echo $thumbnail; ?>" />
				</a>
			</div>
			<div class="simple-content">
				<p>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</p>
				<p class="simple-time"><?php the_time('Y-m-d'); ?></p>
			</div>
		</div>
<?php else : ?>
		<div class="simple-item">
			<!--无图单文字列表块-->
			<div class="simple-content">
				<p>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</p>
				<p class="simple-time"><?php the_time('Y-m-d'); ?></p>
			</div>
		</div>
<?php
	endif;

	endwhile;
	wp_reset_query();
}

?>