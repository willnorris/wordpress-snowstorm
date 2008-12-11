<?php
/*
 Plugin Name: Snow Storm
 Description: Add's Javascript snow effect to your blog, thanks to <a href="http://www.schillmania.com/projects/snowstorm/">Scott Schiller</a>.
 Author: Will Norris
 Author URI: http://willnorris.com
 Version: trunk
 */

register_activation_hook('snowstorm/snowstorm.php', 'snowstorm_activate');
register_uninstall_hook('snowstorm/snowstorm.php', 'snowstorm_uninstall');
add_action('init', 'snowstorm_js');
add_action('parse_request', 'snowstorm_parse_request');
add_action('admin_menu', 'snowstorm_admin_menu');
add_action('admin_init', 'snowstorm_admin_init');

function snowstorm_activate() {
	$snowstorm = array(
		'max' => 128,
		'active' => 64,
		'velocity' => 2.5,
		'size' => 5,
		'bottom' => 0,
		'collect' => true,
	);
	$old = get_option('snowstorm');
	if (is_array($old)) {
		$snowstorm = array_merge($snowstorm, $old);
	}

	update_option('snowstorm', $snowstorm);
}

function snowstorm_uninstall() {
	delete_option('snowstorm');
}


function snowstorm_js() {
	$js = add_query_arg('snowstorm_js', '1', site_url());
	if (is_admin()) $js = add_query_arg('admin', '1', $js);
	wp_enqueue_script('snowstorm', $js);
}

function snowstorm_parse_request() {
	if (array_key_exists('snowstorm_js', $_REQUEST)) {
		header('Content-Type: text/javascript');
		$snowstorm = get_option('snowstorm');
		require_once dirname(__FILE__) . '/javascript.php';
		exit;
	}
}

function snowstorm_admin_init() {
	register_setting('snowstorm', 'snowstorm');
}


function snowstorm_admin_menu() {
	$hookname = add_theme_page(__('Snowstorm'), __('Snowstorm'), 8, 'snowstorm', 'snowstorm_options' );
}

function snowstorm_options() {
	$snowstorm = get_option('snowstorm');

	screen_icon('snowstorm');
?>
	<style type="text/css">
		#icon-snowstorm { background-image: url("<?php echo plugins_url('snowstorm/images/icon.png'); ?>"); }
	</style>

	<div class="wrap">
		<form method="post" action="options.php">
			<h2><?php _e('Snowstorm Options'); ?></h2>
			<table class="form-table">
				<tr valign="top">
                    <th scope="row"><label for="max"><?php _e('Max Snowflakes') ?></label></th>
                    <td>
						<input type="text" name="snowstorm[max]" id="max" value="<?php echo $snowstorm['max'] ?>" class="small-text" />
							<span class="setting-description"><?php _e('The maximum number of snowflakes. (default: 128)') ?></span>
					</td>
				</tr>

				<tr valign="top">
                    <th scope="row"><label for="active"><?php _e('Max Active') ?></label></th>
                    <td>
						<input type="text" name="snowstorm[active]" id="active" value="<?php echo $snowstorm['active'] ?>" class="small-text" />
							<span class="setting-description"><?php _e('The maximum number of "falling" snowflakes. (default: 64)') ?></span>
					</td>
				</tr>

				<tr valign="top">
                    <th scope="row"><label for="velocity"><?php _e('Speed') ?></label></th>
                    <td>
						<input type="text" name="snowstorm[velocity]" id="velocity" value="<?php echo $snowstorm['velocity'] ?>" class="small-text" />
							<span class="setting-description"><?php _e('The maximum velocity of snowflakes. (default: 2.5)') ?></span>
					</td>
				</tr>

				<tr valign="top">
                    <th scope="row"><label for="size"><?php _e('Size') ?></label></th>
                    <td>
						<input type="text" name="snowstorm[size]" id="size" value="<?php echo $snowstorm['size'] ?>" class="small-text" />
							<span class="setting-description"><?php _e('The size (in pixels) of each snowflake image. (default: 5)') ?></span>
					</td>
				</tr>

				<tr valign="top">
                    <th scope="row"><label for="bottom"><?php _e('Bottom') ?></label></th>
                    <td>
						<input type="text" name="snowstorm[bottom]" id="bottom" value="<?php echo $snowstorm['bottom'] ?>" class="small-text" />
							<span class="setting-description"><?php _e('Limits the bottom coordinate of the snow. (default: 0)') ?></span>
					</td>
				</tr>

				<tr valign="top">
                    <th scope="row"><label for="collect"><?php _e('Collect') ?></label></th>
                    <td>
						<label for="collect"><input type="checkbox" name="snowstorm[collect]" id="collect" <?php checked(true, $snowstorm['collect']) ?> />
						<?php _e('Enables the snow to pile up (slowly) at the bottom of the window.') ?></label>
                    </td>
                </tr>
			</table>

			<?php settings_fields('snowstorm'); ?>

			<p class="submit"> <input type="submit" name="Submit" class="button-primary" value="Save Changes" /> </p>
		</form>
	</div>
<?php
}

?>
