<?php

//use my testing plugin\my testing pluginPlugin;

/**
 *
 * Plugin Name:       my_testing_plugin
 * Plugin URI:        https://alvararias.se
 * Description:       plugin testing funciones
 * Version:           4.0
 * Author:            Alvar Arias
 * Author URI:        https://alvararias.se
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       my_testing_plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


function new_post_peepso()
	{
    // 2- get post from MyListing
    global $post;
    $num_list =  $post->ID;
    $author_list = $post->post_author;
    $name_list = $post->post_name;
    $title_list = $post->post_title;
    $content_list = $post->post_content;

    $url_List = 'http://dev.waterspot.org/listing/'.$name_list.'/';

// 3-Create WP Post Using My_List Information
// Variables del Nuevo Post
$post_id = -1;
$author_id = $author_list;
$slug = $name_list;
$title = $title_list;
$post_type = 'post';
$post_content = '<h2>'.$content_list.'</h2><h4 style="text-align:center;"><a herf='.$url_List.'>Go to My_List</a></h4>';
$category = get_category_by_slug( 'waterspot' );

// Insertar el Nuevo Post
	$post_id = wp_insert_post(
		array(
			'comment_status'	=>	'closed',
			'ping_status'		=>	'closed',
			'post_author'		=>	$author_id,
			'post_name'		=> $slug,
			'post_title'		=>	$title,
			'post_status'		=>	'publish',
			'post_type'		=>	$post_type,
			'post_content' =>  $post_content,
			'post_category' => array( $category->term_id )

		)
	);

// Filter change link to my_list_link priority 1

if (function_exists ( peepso_post )) {

	function the_new_link( $url) {

		$url = 'https://dev.waterspot.org/listing/'.$slug;

	return $url;

	}

	add_filter( 'post_link', 'the_new_link', 1, 1 );

}


// add featured image to post

$img_post_peepso = 'https://dev.waterspot.org/wp-content/uploads/2019/06/LogoNoBgkrd1-1.png';

// get img from My_Listing
$cover_link = get_post_meta($num_list,'_job_cover',true);
$attach01 = attachment_url_to_postid($cover_link[0]);
$img_down_cover = image_downsize( $attach01, 'medium_large');

$gallery_link = get_post_meta($num_list,'_job_gallery',true);
$attach02 = attachment_url_to_postid($gallery_link[0]);
$img_down_gallery = image_downsize( $attach02, 'medium_large');

if ($cover_link) {

    $image_cover_Peepso = attachment_url_to_postid($cover_link[0]);
    set_post_thumbnail( $post_id, $image_cover_Peepso);

} elseif ($gallery_link) {

    $image_gallery_Peepso = attachment_url_to_postid($gallery_link[0]);
    set_post_thumbnail( $post_id, $image_gallery_Peepso);

} else {

    $image_def_Peepso = attachment_url_to_postid($img_post_peepso);
    set_post_thumbnail( $post_id, $image_def_Peepso);

};


}

// Confirm post was created or not
if ( FALSE === get_post_status( $post_id ) ) {

	function confirm_new_post_peepso_error() { ?>
		<div class="notice notice-error  is-dismissible">
		<p><strong>Spot-post was not published.</strong></p>
	    </div>
add_action('admin_notices', 'confirm_new_post_peepso_error');

} else {

	function confirm_new_post_peepso() { ?>
		<div class="notice notice-success is-dismissible">
		<p><strong>Spot-post published.</strong></p>
	    </div>
add_action('admin_notices', 'confirm_new_post_peepso');

																			}
			}
																						}
// fin confirmación de post created

// fin de la función

// 1-When a post change  from waiting for review to published
add_action('pending_to_publish','new_post_peepso');




<?php
