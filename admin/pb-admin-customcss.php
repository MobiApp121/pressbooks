<?php
/**
 * @author  PressBooks <code@pressbooks.org>
 * @license GPLv2 (or any later version)
 */
namespace PressBooks\Admin\CustomCss;


use PressBooks\Book;
use PressBooks\CustomCss;

/**
 * Add Edit CSS menu.
 */
function add_menu() {

	if ( Book::isBook() && CustomCss::isCustomCss() ) {
		add_theme_page( __( 'Edit CSS', 'pressbooks' ), __( 'Edit CSS', 'pressbooks' ), 'edit_theme_options', 'pb_custom_css', __NAMESPACE__ . '\display_custom_css' );
	}
}

/**
 * Force the user to edit custom-css posts in our custom editor.
 */
function redirect_css_editor() {

	$post_id = absint( @$_REQUEST['post'] );
	if ( ! $post_id )
		return; // Do nothing

	$post = get_post( $post_id );
	if ( ! $post )
		return; // Do nothing

	if ( 'custom-css' != $post->post_type )
		return; // Do nothing

	$redirect_url = get_bloginfo( 'url' ) . '/wp-admin/themes.php?page=pb_custom_css&slug=' . $post->post_name;
	\PressBooks\Redirect\location( $redirect_url );
}


/**
 * Displays the Edit CSS Page
 */
function display_custom_css() {

	$custom_css = new CustomCss();

	$slug = isset( $_GET['slug'] ) ? $_GET['slug'] : get_transient( 'pb-last-custom-css-slug' );
	if ( ! $slug ) $slug = 'web';

	$supported = array_keys( $custom_css->supported );
	if ( ! in_array( $slug, $supported ) ) {
		wp_die( "Unknown slug: $slug" );
	}

	$css_post = $custom_css->getPost( $slug );
	if ( false === $css_post ) {
		wp_die( sprintf( __( 'Unexpected Error: There was a problem trying to query slug: %s - Please contact technical support.', 'pressbooks' ), $slug ) );
	}

	$vars = array(
		'slugs_dropdown' => render_dropdown_for_slugs( $custom_css, $slug ),
		'css_copy_dropdown' => render_dropdown_for_css_copy( $custom_css, $slug ),
		'revisions_table' => render_revisions_table( $custom_css, $slug, $css_post->ID ),
		'post_id' => absint( $css_post->ID ),
		'my_custom_css' => $css_post->post_content,
	);
	load_custom_css_template( $vars );

	set_transient( 'pb-last-custom-css-slug', $slug );
}


/**
 * Simple templating function.
 *
 * @param array $vars
 */
function load_custom_css_template( $vars ) {

	extract( $vars );
	require( PB_PLUGIN_DIR . 'admin/templates/custom-css.php' );
}


/**
 * Render table for revisions.
 *
 * @param \PressBooks\CustomCss $custom_css
 * @param string $slug
 * @param int $post_id
 *
 * @return string
 */
function render_revisions_table( $custom_css, $slug, $post_id ) {

	$args = array(
		'posts_per_page' => 10,
		'post_type' => 'revision',
		'post_status' => 'inherit',
		'post_parent' => $post_id,
		'orderby' => 'date',
		'order' => 'DESC'
	);

	$q = new \WP_Query();
	$results = $q->query( $args );


	$html = '<table class="widefat fixed" cellspacing="0">';
	$html .= '<thead><th>' . __( 'Last 10 CSS Revisions', 'pressbooks' ) . " <em>({$custom_css->supported[$slug]})</em> </th></thead><tbody>";
	foreach ( $results as $post ) {
		$html .= '<tr><td>' . wp_post_revision_title( $post ) . ' ';
		$html .= __( 'by', 'pressbooks' ) . ' ' . get_userdata( $post->post_author )->user_login . '</td></tr>';
	}
	$html .= '</tbody></table>';

	return $html;
}


/**
 * Render dropdown and JavaScript for slugs.
 *
 * @param \PressBooks\CustomCss $custom_css
 * @param string $slug
 *
 * @return string
 */
function render_dropdown_for_slugs( $custom_css, $slug ) {

	$select_id = $select_name = 'slug';
	$redirect_url = get_bloginfo( 'url' ) . '/wp-admin/themes.php?page=pb_custom_css&slug=';
	$html = '';

	$html .= "
	<script type='text/javascript'>
    // <![CDATA[
	jQuery.noConflict();
	jQuery(function ($) {
		$('#" . $select_id . "').change(function() {
		  window.location = '" . $redirect_url . "' + $(this).val();
		});
	});
	// ]]>
    </script>";

	$html .= '<select id="' . $select_id . '" name="' . $select_name . '">';
	foreach ( $custom_css->supported as $key => $val ) {
		$html .= '<option value="' . $key . '"';
		if ( $key == $slug ) $html .= ' selected="selected"';
		$html .= '>' . __( $val, 'pressbooks' ) . '</option>';
	}
	$html .= '</select>';

	return $html;
}


/**
 * Render dropdown and JavaScript for CSS copy.
 *
 * @param \PressBooks\CustomCss $custom_css
 * @param string $slug
 *
 * @return string
 */
function render_dropdown_for_css_copy( $custom_css, $slug ) {

	$select_id = $select_name = 'pb-load-css-from';
	$themes = wp_get_themes( array( 'allowed' => true ) );
	$ajax_nonce = wp_create_nonce( 'pb-load-css-from' );
	$html = '';

	$html .= "
	<script type='text/javascript'>
    // <![CDATA[
	jQuery.noConflict();
	jQuery(function ($) {
		$('#" . $select_id . "').change(function() {
			var enable = confirm('" . __( 'This will overwrite existing custom CSS. Are you sure?', 'pressbooks' ) . "');
			if (enable == true) {
				var my_slug = $(this).val();
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'pb_load_css_from',
						slug: my_slug,
						_ajax_nonce: '" . $ajax_nonce . "'
					},
					beforeSend: function() {
						$('input[type=\"submit\"]').attr('disabled', 'disabled');
					},
					success: function(data) {
						$('#my_custom_css').val(data.content);
						$('form#pb-custom-css-form').submit();
					}
				});
			}
			$('#" . $select_id . " option:first-child').attr('selected', 'selected');
		});
	});
	// ]]>
    </script>";

	$html .= '<select id="' . $select_id . '" name="' . $select_name . '">';
	$html .= '<option value="">---</option>';
	foreach ( $themes as $key => $theme ) {
		if ( 'pressbooks-custom-css' == $key ) continue; // Skip
		$html .= '<option value="' . "{$key}__{$slug}" . '"'; // Explode on __
		$html .= '>' . $theme->name . '</option>';
	}
	$html .= '</select>';

	return $html;
}


/**
 * WP_Ajax hook. Copy book style from an existing template.
 */
function load_css_from() {

	check_ajax_referer( 'pb-load-css-from' );
	if ( false == current_user_can( 'edit_theme_options' ) ) die( - 1 );

	$css = '';
	$themes = wp_get_themes( array( 'allowed' => true ) );
	list( $theme, $slug ) = explode( '__', @$_POST['slug'] );

	if ( isset( $themes[$theme] ) ) {

		$theme = $themes[$theme]; // Get theme object

		if ( 'web' == $slug ) {
			$path_to_style = realpath( $theme->stylesheet_dir . '/style.css' );
		} else {
			$path_to_style = realpath( $theme->stylesheet_dir . "/export/$slug/style.css" );
		}

		if ( $path_to_style ) {
			$css = file_get_contents( $path_to_style );
		}

		// Copy images, too...
		if ( 'web' != $slug ) {
			$path_to_images = realpath( $theme->stylesheet_dir . "/export/$slug/images/" );
			if ( $path_to_images ) {
				$copy_from = scandir( $path_to_images );
				$copy_to = CustomCss::getCustomCssFolder() . "images/";
				foreach ( $copy_from as $copy ) {
					if ( '.' == $copy || '..' == $copy ) continue;
					copy( $path_to_images . '/' . $copy, $copy_to . $copy );
				}
			}
		}
	}

	// Send back JSON
	header( 'Content-Type: application/json' );
	$json = json_encode( array( 'content' => $css ) );
	echo $json;

	// @see http://codex.wordpress.org/AJAX_in_Plugins#Error_Return_Values
	// Will append 0 to returned json string if we don't die()
	die();
}
