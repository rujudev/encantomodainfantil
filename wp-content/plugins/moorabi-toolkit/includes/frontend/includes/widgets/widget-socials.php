<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Moorabi socials
 *
 * Displays socials widget.
 *
 * @author   
 * @category Widgets
 * @package  Moorabi/Widgets
 * @version  1.0.0
 * @extends  MOORABI_Widget
 */
if ( !class_exists( 'Moorabi_Socials_Widget' ) ) {
	class Moorabi_Socials_Widget extends MOORABI_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$socials     = array();
			$all_socials = cs_get_option( 'user_all_social' );
			if ( $all_socials ) {
				foreach ( $all_socials as $key => $social ) {
					$socials[$key] = $social['title_social'];
				}
			}
			$array_settings           = apply_filters( 'moorabi_filter_settings_widget_socials',
				array(
					'title'         => array(
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'moorabi-toolkit' ),
					),
					'moorabi_socials' => array(
						'type'    => 'checkbox',
						'class'   => 'horizontal',
						'title'   => esc_html__( 'Select Social', 'moorabi-toolkit' ),
						'options' => $socials,
					),
				)
			);
			$this->widget_cssclass    = 'widget-moorabi-socials';
			$this->widget_description = esc_html__( 'Display the customer Socials.', 'moorabi-toolkit' );
			$this->widget_id          = 'widget_moorabi_socials';
			$this->widget_name        = esc_html__( 'Moorabi: Socials', 'moorabi-toolkit' );
			$this->settings           = $array_settings;
			parent::__construct();
		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance )
		{
			$this->widget_start( $args, $instance );
			$all_socials = cs_get_option( 'user_all_social' );
			ob_start();
			?>
            <div class="content-socials">
				<?php if ( !empty( $instance['moorabi_socials'] ) ) : ?>
                    <ul class="socials-list">
						<?php foreach ( $instance['moorabi_socials'] as $value ) : ?>
							<?php if ( isset( $all_socials[$value] ) ) :
								$array_socials = $all_socials[$value]; ?>
                                <li>
                                    <a href="<?php echo esc_url( $array_socials['link_social'] ) ?>"
                                       target="_blank">
                                        <span class="<?php echo esc_attr( $array_socials['icon_social'] ); ?>"></span>
										<?php echo esc_html( $array_socials['title_social'] ); ?>
                                    </a>
                                </li>
							<?php endif; ?>
						<?php endforeach; ?>
                    </ul>
				<?php endif; ?>
            </div>
			<?php
			echo apply_filters( 'moorabi_filter_widget_socials', ob_get_clean(), $instance );
			$this->widget_end( $args );
		}
	}
}
/**
 * Register Widgets.
 *
 * @since 2.3.0
 */
function Moorabi_Socials_Widget()
{
	register_widget( 'Moorabi_Socials_Widget' );
}

add_action( 'widgets_init', 'Moorabi_Socials_Widget' );