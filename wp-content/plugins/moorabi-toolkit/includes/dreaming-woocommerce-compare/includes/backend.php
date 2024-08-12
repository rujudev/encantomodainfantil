<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function dreaming_wccp_save_all_settings_via_ajax() {
	
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'Cheating!? Huh?', 'moorabi-toolkit' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! wp_verify_nonce( $nonce, 'dreaming_wccp_backend_nonce' ) ) {
		$response['message'][] = esc_html__( 'Security check error!', 'moorabi-toolkit' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$all_settings = isset( $_POST['all_settings'] ) ? Dreaming_Woocompare_Helper::clean( $_POST['all_settings'] ) : array();
	// $response['all_settings'] = $all_settings;
	
	update_option( 'dreaming_wccp_all_settings', $all_settings );
	
	$response['message'][] = esc_html__( 'All settings saved', 'moorabi-toolkit' );
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_dreaming_wccp_save_all_settings_via_ajax', 'dreaming_wccp_save_all_settings_via_ajax' );


/**
 * Compare page
 *
 * @param array   $post_states An array of post display states.
 * @param WP_Post $post        The current post object.
 */
function dreaming_wccp_add_display_post_states( $post_states, $post ) {
	$page_for_compare = Dreaming_Woocompare_Helper::get_page( 'compare' );
	if ( $page_for_compare === $post->ID ) {
		$post_states['_dreaming_wccp_page_for_compare'] = __( 'Page For Compare', 'moorabi-toolkit' );
	}
	
	return $post_states;
}

add_filter( 'display_post_states', 'dreaming_wccp_add_display_post_states', 10, 2 );

/**
 * @return string
 */
function dreaming_wccp_export_settings_link() {
	$nonce = wp_create_nonce( 'dreaming-wccp-export-settings' );
	$url   = add_query_arg(
		array(
			'action' => 'dreaming_wccp_export_all_settings',
			'nonce'  => $nonce
		),
		admin_url( 'admin-ajax.php' )
	);
	
	return esc_url( $url );
}

function dreaming_wccp_import_settings_action_link() {
	$nonce = wp_create_nonce( 'dreaming-wccp-import-settings' );
	$url   = add_query_arg(
		array(
			'action' => 'dreaming_wccp_import_all_settings',
			'nonce'  => $nonce
		),
		admin_url( 'admin-ajax.php' )
	);
	
	return esc_url( $url );
}

/**
 * Export all settings via ajax
 */
function dreaming_wccp_export_all_settings() {
	$all_setings = Dreaming_Woocompare_Helper::get_all_settings();
	
	header( 'Content-disposition: attachment; filename=dreaming_wccp.json' );
	header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
	
	$security     = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
	$nonce_action = 'dreaming-wccp-export-settings';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		die( esc_html__( 'Security check error', 'moorabi-toolkit' ) );
	}
	
	if ( ! current_user_can( 'manage_options' ) ) {
		die( esc_html__( 'Cheating!? Huh?', 'moorabi-toolkit' ) );
	}
	
	die( wp_json_encode( $all_setings ) );
}

add_action( 'wp_ajax_dreaming_wccp_export_all_settings', 'dreaming_wccp_export_all_settings' );

function dreaming_wccp_import_all_settings() {
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$security     = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
	$nonce_action = 'dreaming-wccp-import-settings';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error!!!', 'moorabi-toolkit' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'Cheating!? Huh?', 'moorabi-toolkit' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$response['files'] = $_FILES;
	
	if ( ! isset( $_FILES['dreaming_wccp_import_file']['error'] ) || is_array( $_FILES['dreaming_wccp_import_file']['error'] ) ) {
		$response['message'][] = esc_html__( 'Invalid parameters.', 'moorabi-toolkit' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	switch ( $_FILES['dreaming_wccp_import_file']['error'] ) {
		case UPLOAD_ERR_OK:
			break;
		case UPLOAD_ERR_NO_FILE:
			$response['message'][] = esc_html__( 'No file sent.', 'moorabi-toolkit' );
			$response['err']       = 'yes';
			wp_send_json( $response );
			break;
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			$response['message'][] = esc_html__( 'Exceeded filesize limit.', 'moorabi-toolkit' );
			$response['err']       = 'yes';
			wp_send_json( $response );
			break;
		default:
			$response['message'][] = esc_html__( 'Unknown errors.', 'moorabi-toolkit' );
			$response['err']       = 'yes';
			wp_send_json( $response );
			break;
	}
	
	$imported_file_name = isset( $_FILES['dreaming_wccp_import_file']['name'] ) ? Dreaming_Woocompare_Helper::clean( $_FILES['dreaming_wccp_import_file']['name'] ) : null;
	
	// Check file size
	if ( $_FILES['dreaming_wccp_import_file']['size'] > 500000 ) {
		$response['message'][] = esc_html__( 'Sorry, uploaded file is too large!!!', 'moorabi-toolkit' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Check file type
	$file_ext = dreaming_wccp_get_file_ext_if_allowed( $imported_file_name );
	if ( $file_ext !== 'json' ) {
		$response['message'][] = esc_html__( 'Wrong file extension!!!', 'moorabi-toolkit' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$upload_dir  = wp_upload_dir();
	$target_file = $upload_dir . basename( $imported_file_name );
	if ( move_uploaded_file( $_FILES['dreaming_wccp_import_file']['tmp_name'], $target_file ) ) {
		if ( file_exists( $target_file ) ) {
			WP_Filesystem();
			global $wp_filesystem;
			$file_content = $wp_filesystem->get_contents( $target_file );
			
			$response['file_content'] = $file_content;
			// Remove file after read
			$wp_filesystem->delete( $target_file );
			
			// Check JSON format
			$all_settings = Dreaming_Woocompare_Helper::clean( json_decode( $file_content, true ) );
			if ( ! $all_settings ) {
				$response['message'][] = esc_html__( 'Wrong file format!!!', 'moorabi-toolkit' );
				$response['err']       = 'yes';
				wp_send_json( $response );
			}
			
			// EVERYTHING IS OK FOR IMPORT SETTINGS
			update_option( 'dreaming_wccp_all_settings', $all_settings );
			$response['message'][] = esc_html__( 'All settings imported. Reloading the page...', 'moorabi-toolkit' );
			wp_send_json( $response );
			
		} else {
			$response['message'][] = esc_html__( 'Can\'t find moved file!!!', 'moorabi-toolkit' );
			$response['err']       = 'yes';
			wp_send_json( $response );
		}
	} else {
		$response['message'][] = esc_html__( 'Can\'t move file!!!', 'moorabi-toolkit' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_dreaming_wccp_import_all_settings', 'dreaming_wccp_import_all_settings' );

/**
 * @param      $filename
 * @param null $mimes
 *
 * @return array
 */
function dreaming_wccp_check_filetype( $filename, $mimes = null ) {
	if ( empty( $mimes ) ) {
		$mimes = array(
			'json' => 'application/json'
		);
	}
	
	$type = false;
	$ext  = false;
	
	foreach ( $mimes as $ext_preg => $mime_match ) {
		$ext_preg = '!\.(' . $ext_preg . ')$!i';
		if ( preg_match( $ext_preg, $filename, $ext_matches ) ) {
			$type = $mime_match;
			$ext  = $ext_matches[1];
			break;
		}
	}
	
	return compact( 'ext', 'type' );
}

function dreaming_wccp_get_file_ext_if_allowed( $filename, $mimes = null ) {
	$filetype = dreaming_wccp_check_filetype( $filename, $mimes );
	if ( isset( $filetype['ext'] ) ) {
		return $filetype['ext'];
	}
	
	return '';
}