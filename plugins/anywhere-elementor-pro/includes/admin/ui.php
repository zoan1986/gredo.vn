<?php

namespace Aepro\Admin;

use Aepro\Aepro;
use Aepro\Admin\AdminHelper;

class Ui
{
	private static $_instance = null;

	private $screens = [];

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		add_action('in_admin_header', [$this, 'top_bar']);

		add_action('restrict_manage_posts', [$this, 'render_mode_filter']);

		add_filter('parse_query', [$this, 'filter_templates']);

		$this->set_screens();
	}

	protected function set_screens()
	{

		$this->screens = [
			'ae_global_templates',
			'edit-ae_global_templates',
			'ae_global_templates_page_aepro-settings'
		];
	}

	public function top_bar()
	{

		$nav_links = [
			'edit-ae_global_templates' => [
				'label' => __('Templates', 'ae-pro'),
				'link'  => admin_url('edit.php?post_type=ae_global_templates')
			],
			'ae_global_templates_page_aepro-settings' => [
				'label' => __('Settings', 'ae-pro'),
				'link'  => admin_url('edit.php?post_type=ae_global_templates&page=aepro-settingsedit.php?post_type=ae_global_templates&page=aepro-settings')
			],
			'doc' => [
				'label' => __('Documentation', 'ae-pro'),
				'link'  => 'https://wpvibes.link/go/ae-docs/'
			],
			'support' => [
				'label' => __('Get Support', 'ae-pro'),
				'link'  => 'https://wpvibes.link/go/ea-support/'
			]
		];


		$current_screen = get_current_screen();

		if (!in_array($current_screen->id, $this->screens)) {
			return;
		}


?>

		<div class="ae-admin-topbar">
			<div class="ae-branding">
				<?php echo file_get_contents(AE_PRO_PATH . 'includes/assets/images/ae-logo-color.svg'); ?>
				<h1>Anywhere Elememtor Pro</h1>
				<span class="ae-version"><?php echo AE_PRO_VERSION; ?></span>
			</div>


			<nav class="ae-nav">
				<ul>
					<?php
					if (isset($nav_links) && count($nav_links)) {
						foreach ($nav_links as $id => $link) {

							$active = ($current_screen->id === $id) ? 'ae-nav-active' : '';

							$target = '';
							if ($id === 'doc' || $id === 'support') {
								$target = 'target="_blank"';
							}
					?>
							<li class="<?php echo $active; ?>">
								<a <?php echo $target; ?> href="<?php echo $link['link']; ?>"><?php echo $link['label']; ?></a>
							</li>
					<?php
						}
					}
					?>
				</ul>
			</nav>
		</div>

	<?php
	}

	public function render_mode_filter()
	{
		$current = '';
		$render_modes = Aepro::$_helper->get_ae_render_mode_hook();

		if (isset($_GET['ae-render-mode']) && !empty($_GET['ae-render-mode'])) {
			$current = $_GET['ae-render-mode'];
		}

		$admin_helper = AdminHelper::instance();

	?>
		<select name="ae-render-mode">
			<option value="">All Templates</option>
			<?php
			$admin_helper->render_dropdown($render_modes, $current);
			?>
		</select>
<?php
	}

	public function filter_templates($query)
	{
		global $pagenow;
		$post_type = (isset($_GET['post_type'])) ? esc_attr($_GET['post_type']) : 'post';

		if ($post_type == 'ae_global_templates' && $pagenow == 'edit.php' && isset($_GET['ae-render-mode']) && !empty($_GET['ae-render-mode'])) {
			$query->query_vars['meta_value'] = esc_attr($_GET['ae-render-mode']);
			$query->query_vars['meta_key'] 	= 'ae_render_mode';
		}
	}
}
