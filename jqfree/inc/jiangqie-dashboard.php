<?php

/**
 * 酱茄Free主题由酱茄（www.jiangqie.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

function jiangqie_theme_free_custom_dashboard()
{
	$content = '欢迎使用酱茄Free主题! <br/><br/> 微信客服：jianbing2011 (加开源群、问题咨询、项目定制、购买咨询) <br/><br/> <a href="https://www.jiangqie.com/xz" target="_blank">更多免费产品</a>';
	$res = wp_remote_get("https://www.zhuige.com/api/ad/wordpress?id=jq_theme_free", ['timeout' => 1, 'sslverify' => false]);
	if (!is_wp_error($res) && $res['response']['code'] == 200) {
		$data = json_decode($res['body'], TRUE);
		if ($data['code'] == 1) {
			$content = $data['data'];
		}
	}

	echo $content;
}

function jiangqie_theme_free_add_dashboard_widgets()
{
	wp_add_dashboard_widget('jiangqie_theme_free_dashboard_widget', '酱茄Free主题', 'jiangqie_theme_free_custom_dashboard');
}

add_action('wp_dashboard_setup', 'jiangqie_theme_free_add_dashboard_widgets');
