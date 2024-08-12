<?php
if ( !class_exists( 'Moorabi_Welcome' ) ) {
	class Moorabi_Welcome
	{
		public $tabs = array();
		public $theme_name;

		public function __construct()
		{
			$this->set_tabs();
			$this->theme_name = wp_get_theme()->get( 'Name' );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
		}

		public function admin_menu()
		{
			if ( current_user_can( 'edit_theme_options' ) ) {
				add_menu_page( 'Moorabi', 'Moorabi', 'manage_options', 'moorabi_menu', array( $this, 'welcome' ), MOORABI_TOOLKIT_URL . '/assets/images/menu-icon.png', 2 );
				add_submenu_page( 'moorabi_menu', 'Moorabi Dashboard', 'Dashboard', 'manage_options', 'moorabi_menu', array( $this, 'welcome' ) );
			}
		}

		public function set_tabs()
		{
			$this->tabs = array(
				'dashboard' => esc_html__( 'Welcome', 'moorabi-toolkit' ),
				'plugins'   => esc_html__( 'Plugins', 'moorabi-toolkit' ),
			);
		}

		public function active_plugin()
		{
			if ( empty( $_GET['magic_token'] ) || wp_verify_nonce( $_GET['magic_token'], 'panel-plugins' ) === false ) {
				esc_html_e( 'Permission denied', 'moorabi-toolkit' );
				die;
			}
			if ( isset( $_GET['plugin_slug'] ) && $_GET['plugin_slug'] != "" ) {
				$plugin_slug = $_GET['plugin_slug'];
				$plugins     = TGM_Plugin_Activation::$instance->plugins;
				foreach ( $plugins as $plugin ) {
					if ( $plugin['slug'] == $plugin_slug ) {
						activate_plugins( $plugin['file_path'] );
						?>
						<script type="text/javascript">
							window.location = "admin.php?page=moorabi_menu&tab=plugins";
						</script>
						<?php
						break;
					}
				}
			}
		}

		public function deactivate_plugin()
		{
			if ( empty( $_GET['magic_token'] ) || wp_verify_nonce( $_GET['magic_token'], 'panel-plugins' ) === false ) {
				esc_html_e( 'Permission denied', 'moorabi-toolkit' );
				die;
			}
			if ( isset( $_GET['plugin_slug'] ) && $_GET['plugin_slug'] != "" ) {
				$plugin_slug = $_GET['plugin_slug'];
				$plugins     = TGM_Plugin_Activation::$instance->plugins;
				foreach ( $plugins as $plugin ) {
					if ( $plugin['slug'] == $plugin_slug ) {
						deactivate_plugins( $plugin['file_path'] );
						?>
						<script type="text/javascript">
							window.location = "admin.php?page=moorabi_menu&tab=plugins";
						</script>
						<?php
						break;
					}
				}
			}
		}

		/**
		 * Render HTML of intro tab.
		 *
		 * @return  string
		 */

		public function welcome()
		{
			/* deactivate_plugin */
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'deactivate_plugin' ) {
				$this->deactivate_plugin();
			}
			/* deactivate_plugin */
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'active_plugin' ) {
				$this->active_plugin();
			}
			$tab = 'dashboard';
			if ( isset( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			}
			?>
			<div class="moorabi-wrap">
				<div id="tabs-container" role="tabpanel">
					<div class="nav-tab-wrapper">
						<?php foreach ( $this->tabs as $key => $value ): ?>
							<a class="nav-tab moorabi-nav <?php if ( $tab == $key ): ?> active<?php endif; ?>"
							   href="admin.php?page=moorabi_menu&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></a>
						<?php endforeach; ?>
					</div>
					<div class="tab-content">
						<?php $image_logo = get_theme_file_uri( 'assets/images/logo.svg' );?>
						<div class="logo-demo">
							<img src="<?php echo esc_url($image_logo); ?>" alt="logo-demo" width="150" height="53">
						</div>
						<?php $this->$tab(); ?>
					</div>
				</div>
			</div>
			<?php
		}

		public function dashboard()
		{
			?>
			<div class="dashboard">
				<div class="dashboard-intro">
					<h4 class="info-theme"><strong><?php echo ucfirst( esc_html( $this->theme_name ) ); ?></strong>
						<?php esc_html_e('is a modern, clean and professional WooCommerce Wordpress Theme, It is fully responsive, it looks stunning on all types of screens and devices.','moorabi-toolkit') ?>
					</h4>
					<div class="image">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/theme-prev.jpg' ); ?>" alt="moorabi">
					</div>
				</div>
			</div>
			<?php
		}

		public static function plugins()
		{
			$moorabi_tgm_theme_plugins = TGM_Plugin_Activation::$instance->plugins;
			$tgm                     = TGM_Plugin_Activation::$instance;
			?>
			<h4 class="info-theme"><?php esc_html_e( 'Before importing the demo content ensure that you have Installed and Activated all the plugins', 'moorabi-toolkit' ); ?></h4>
			<div class="plugins rp-row">
				<?php
				$wp_plugin_list = get_plugins();
				foreach ( $moorabi_tgm_theme_plugins as $moorabi_tgm_theme_plugin ) {
					if ( $tgm->is_plugin_active( $moorabi_tgm_theme_plugin['slug'] ) ) {
						$status_class = 'is-active';
						if ( $tgm->does_plugin_have_update( $moorabi_tgm_theme_plugin['slug'] ) ) {
							$status_class = 'plugin-update';
						}
					} else if ( isset( $wp_plugin_list[$moorabi_tgm_theme_plugin['file_path']] ) ) {
						$status_class = 'plugin-inactive';
					} else {
						$status_class = 'no-intall';
					}
					?>
					<div class="rp-col">
						<div class="plugin <?php echo esc_attr( $status_class ); ?>">
							<div class="preview">
								<?php if ( isset( $moorabi_tgm_theme_plugin['image'] ) && $moorabi_tgm_theme_plugin['image'] != "" ): ?>
									<img src="<?php echo esc_url( $moorabi_tgm_theme_plugin['image'] ); ?>"
										 alt="moorabi">
								<?php else: ?>
									<?php $image_plugin = MOORABI_TOOLKIT_URL.'assets/images/'.$moorabi_tgm_theme_plugin['slug'].'.jpg';?>
									<img src="<?php echo esc_url( $image_plugin ); ?>"
										 alt="moorabi">
								<?php endif; ?>
							</div>
							<div class="plugin-name">
								<h3 class="theme-name"><?php echo $moorabi_tgm_theme_plugin['name'] ?></h3>
							</div>
							<div class="actions">
								<a class="button button-primary button-install-plugin" href="<?php
								echo esc_url( wp_nonce_url(
										add_query_arg(
											array(
												'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
												'plugin'        => urlencode( $moorabi_tgm_theme_plugin['slug'] ),
												'tgmpa-install' => 'install-plugin',
											),
											admin_url( 'themes.php' )
										),
										'tgmpa-install',
										'tgmpa-nonce'
									)
								);
								?>"><?php esc_html_e( 'Install', 'moorabi-toolkit' ); ?></a>

								<a class="button button-primary button-update-plugin" href="<?php
								echo esc_url( wp_nonce_url(
										add_query_arg(
											array(
												'page'         => urlencode( TGM_Plugin_Activation::$instance->menu ),
												'plugin'       => urlencode( $moorabi_tgm_theme_plugin['slug'] ),
												'tgmpa-update' => 'update-plugin',
											),
											admin_url( 'themes.php' )
										),
										'tgmpa-install',
										'tgmpa-nonce'
									)
								);
								?>"><?php esc_html_e( 'Update', 'moorabi-toolkit' ); ?></a>

								<a class="button button-primary button-activate-plugin" href="<?php
								echo esc_url(
									add_query_arg(
										array(
											'page'        => 'moorabi_menu&tab=plugins',
											'plugin_slug' => urlencode( $moorabi_tgm_theme_plugin['slug'] ),
											'action'      => 'active_plugin',
											'magic_token' => wp_create_nonce( 'panel-plugins' ),
										),
										admin_url( 'admin.php' )
									)
								);
								?>"><?php esc_html_e( 'Activate', 'moorabi-toolkit' ); ?></a>
								<a class="button button-secondary button-uninstall-plugin" href="<?php
								echo esc_url(
									add_query_arg(
										array(
											'page'        => 'moorabi_menu&tab=plugins',
											'plugin_slug' => urlencode( $moorabi_tgm_theme_plugin['slug'] ),
											'action'      => 'deactivate_plugin',
											'magic_token' => wp_create_nonce( 'panel-plugins' ),
										),
										admin_url( 'admin.php' )
									)
								);
								?>"><?php esc_html_e( 'Deactivate', 'moorabi-toolkit' ); ?></a>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php
		}
	}

	new Moorabi_Welcome();
}
