<?php
/**
 * 酱茄Free主题由酱茄（www.jiangqie.com）开发的一款免费开源的WordPress主题，专为WordPress博客、资讯、自媒体网站而设计。
 */

if (!class_exists('JiangQie_User_Avatar')) {
	/**
	 * add field to user profiles
	 */
	class JiangQie_User_Avatar
	{
		private $user_id_being_edited;

		public function __construct()
		{
			add_action('show_user_profile', array($this, 'edit_user_profile'));
			add_action('edit_user_profile', array($this, 'edit_user_profile'));

			add_action('personal_options_update', array($this, 'edit_user_profile_update'));
			add_action('edit_user_profile_update', array($this, 'edit_user_profile_update'));
		}

		public function edit_user_profile($profileuser)
		{
?>
			<!-- <h3>酱茄属性</h3> -->

			<table class="form-table">
				<tr>
					<th><label for="jiangqie-avatar">酱茄头像</label></th>
					<td style="width: 64px;" valign="top">
						<?php
						$avatar = get_user_meta($profileuser->ID, 'jiangqie_avatar', true);
						if (empty($avatar)) {
							$avatar = get_stylesheet_directory_uri() . '/images/default_avatar.jpg';
						}
						echo '<img src="' . $avatar . '" width="64" height="64" />';
						?>
					</td>
					<td>
						<?php
						if (current_user_can('upload_files')) {
							wp_nonce_field('jiangqie_avatar_nonce', '_jiangqie_avatar_nonce', false);
						?>
							<input type="file" name="jiangqie-avatar" id="jiangqie-avatar" /><br />
						<?php
							if (empty($profileuser->jiangqie_avatar))
								echo '<span class="description">上传图片，修改头像</span>';
							else
								echo '<input type="checkbox" name="jiangqie-avatar-erase" value="1" />恢复默认头像<br />';
						} else {
							echo '<span class="description">无设置头像权限</span>';
						}
						?>
					</td>
				</tr>
			</table>
			<script type="text/javascript">
				var form = document.getElementById('your-profile');
				form.encoding = 'multipart/form-data';
				form.setAttribute('enctype', 'multipart/form-data');
			</script>
<?php
		}

		public function edit_user_profile_update($user_id)
		{
			if (!isset($_POST['_jiangqie_avatar_nonce']) || !wp_verify_nonce($_POST['_jiangqie_avatar_nonce'], 'jiangqie_avatar_nonce'))			//security
				return;

			if (!empty($_FILES['jiangqie-avatar']['name'])) {
				$mimes = array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'gif' => 'image/gif',
					'png' => 'image/png',
					'bmp' => 'image/bmp',
					'tif|tiff' => 'image/tiff'
				);

				// front end (theme my profile etc) support
				if (!function_exists('wp_handle_upload'))
					require_once(ABSPATH . 'wp-admin/includes/file.php');

				$this->avatar_delete($user_id);	// delete old images if successful

				// need to be more secure since low privelege users can upload
				if (strstr($_FILES['jiangqie-avatar']['name'], '.php'))
					wp_die('For security reasons, the extension ".php" cannot be in your file name.');

				$this->user_id_being_edited = $user_id; // make user_id known to unique_filename_callback function
				$avatar = wp_handle_upload($_FILES['jiangqie-avatar'], array('mimes' => $mimes, 'test_form' => false, 'unique_filename_callback' => array($this, 'unique_filename_callback')));

				if (empty($avatar['file'])) {		// handle failures
					switch ($avatar['error']) {
						case 'File type does not meet security guidelines. Try another.':
							add_action('user_profile_update_errors', create_function('$a', '$a->add("avatar_error", "Please upload a valid image file for the avatar.");'));
							break;
						default:
							add_action('user_profile_update_errors', create_function('$a', '$a->add("avatar_error", "There was an error uploading the avatar.");'));
					}

					return;
				}

				update_user_meta($user_id, 'jiangqie_avatar', $avatar['url']);		// save user information (overwriting old)
			} elseif (!empty($_POST['jiangqie-avatar-erase'])) {
				$this->avatar_delete($user_id);
			}
		}

		/**
		 * delete avatars based on user_id
		 */
		public function avatar_delete($user_id)
		{
			$old_avatars = get_user_meta($user_id, 'jiangqie_avatar', true);
			$upload_path = wp_upload_dir();

			if (is_array($old_avatars)) {
				foreach ($old_avatars as $old_avatar) {
					$old_avatar_path = str_replace($upload_path['baseurl'], $upload_path['basedir'], $old_avatar);
					@unlink($old_avatar_path);
				}
			}

			delete_user_meta($user_id, 'jiangqie_avatar');
		}

		public function unique_filename_callback($dir, $name, $ext)
		{
			$user = get_user_by('id', (int) $this->user_id_being_edited);
			$name = $base_name = sanitize_file_name($user->display_name . '_avatar');
			$number = 1;

			while (file_exists($dir . "/$name$ext")) {
				$name = $base_name . '_' . $number;
				$number++;
			}

			return $name . $ext;
		}
	}
}

if (!isset($jiangqie_user_avatar))
	$jiangqie_user_avatar = new JiangQie_User_Avatar;
