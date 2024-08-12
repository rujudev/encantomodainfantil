<?php
/**
 * The Template for products comparison page
 * This template can be overridden by copying it to yourtheme/dreaming-wccp/compare.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'dreaming-wccp' ); ?>

    <div class="dreaming-wccp-wrap">
        <div class="container">
			<?php Dreaming_Woocompare_Helper::get_template_part( 'compare', 'table' ); ?>
        </div>
    </div>

<?php get_footer( 'dreaming-wccp' );

