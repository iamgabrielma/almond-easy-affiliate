<?php
/*
Plugin Name: Almond Easy Affiliate
Plugin URI: http://almondwp.com/almond-easy-affiliate
Description: Manage your affiliate links through shortcodes with this simple solution for WordPress. Keep track of your affiliate links and use them through your site and manage them in a simple database.
Version: 1.0
Author: Gabriel Maldonado
Author URI: http://almondwp.com
License: GPL2
Tags: ads, advertising, affiliate, affiliate marketing, affiliate plugin, affiliate tool, affiliates, amazon, auto, automatic, earn money, lead, link, linker, marketing, money, online sale, order, partner, referral, referral links, referrer, sales, track, transaction.
*/

global $awp_almondwp_easy_affiliate_db_version;
$awp_almondwp_easy_affiliate_db_version = '1.0';

function load_admin_things() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}
add_action( 'admin_enqueue_scripts', 'load_admin_things' );

function show_awp_almondwp_easy_affiliate_link_on_activation($widget_links) {
		
	$widget_links[] = '<a href="' . get_site_url() . '/wp-admin/options-general.php?page=awp_almondwp_easy_affiliate_plugin' . '">Settings</a>';
	$widget_links[] = '<a href="http://almondwp.com" target="_blank">More plugins by AlmondWP</a>';
	return $widget_links;
}
add_filter("plugin_action_links_".plugin_basename(__FILE__), 'show_awp_almondwp_easy_affiliate_link_on_activation', 10, 5);

function awp_almondwp_easy_affiliate_create_db(){

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'my_affiliate_table';
    $sql = "CREATE TABLE $table_name (
		id tinyint NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,	
		imgurl tinytext NOT NULL,
		url tinytext NOT NULL,
		UNIQUE KEY id (id)
		) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	dbDelta( $sql );
	add_option( 'awp_almondwp_easy_affiliate_db_version', $awp_almondwp_easy_affiliate_db_version );

}
register_activation_hook(__FILE__,'awp_almondwp_easy_affiliate_create_db');

function awp_almondwp_easy_affiliate_add_initial_data_to_db() {
	
	global $wpdb;
	
	$welcome_name = 'Almond Easy Affiliate';
	$welcome_url = 'http://themeforest.net/user/gma992/portfolio?ref=gma992';
	$welcome_img = '';
	$table_name = $wpdb->prefix . 'my_affiliate_table';
	
	$wpdb->insert( 
		$table_name, 
		array(
			'name' 	=> $welcome_name, 
			'url' 	=> $welcome_url,
			'imgurl'=> $welcome_img
		) 
	);
}
register_activation_hook(__FILE__,'awp_almondwp_easy_affiliate_add_initial_data_to_db');

function awp_almondwp_easy_affiliate_custom_script() {

	wp_enqueue_script( 'awp-affiliate-script', plugin_dir_url( __FILE__ ) . '/js/awp_affiliate.js', array('jquery'), '1.0.0', true );

}
add_action('admin_menu', 'awp_almondwp_easy_affiliate_custom_script');

function awp_almondwp_easy_affiliate_custom_style() {
	
    wp_enqueue_style("awp-affiliate-style", plugin_dir_url( __FILE__ ) ."css/awp_affiliate.css");
        
}
add_action('admin_print_styles', 'awp_almondwp_easy_affiliate_custom_style');

function awp_almondwp_easy_affiliate_add_page(){

	add_options_page( 'Almond Easy Affiliate', 'Almond Easy Affiliate','manage_options', 'awp_almondwp_easy_affiliate_plugin', 'awp_almondwp_easy_affiliate_options_page' );
}
add_action('admin_menu', 'awp_almondwp_easy_affiliate_add_page');

function awp_almondwp_easy_affiliate_options_page(){
	
	global $wpdb;
	$results = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'my_affiliate_table');

	?>

	<div class="wrap">
		<div class="welcome-panel">
			<h2>Welcome to Almond Easy Affiliate! <span id="by-almondwp">by AlmondWP</span></h2>
			<div class="main">
				<div id="main-options-page-welcome-panel">
					<p></p>
					<p class="about-description">1) Enter the text you want to be displayed as an affiliate link.</p>
					<p class="about-description">2) Enter your affiliate link.</p>
					<p class="about-description">3) Use the shortcode in your posts, pages or anywhere and get the affiliate link in return!</p>
					<p class="about-description">4) For editing entries or adding affiliate images just click on the buttons below and fill the necessary fields, is super intuitive!</p>
					<p></p>
					<p>If you have any question, do not hesitate to check the online <a href="http://almondwp.com/almond-easy-affiliate" target="_blank">documentation</a>, check the <a href="https://youtu.be/tQBMTx9x45c" target="_blank">videotutorials</a> or send an email to <a href="mailto:gabriel@almondwp.com" target="_blank">support</a>.</p>
				</div>
				<span id="hide-button"><a href="#"> <span id="hide-more-info">Hide</span> <span id="show-more-info">Show</span> more information </a></span>

			</div>
		</div>
		<form method="post">
			<input class="input-text-wrap" type="text" id="affiliate-text" name="affiliate-text" placeholder="Text to display"></input>
			<input class="input-text-wrap" type="text" id="affiliate-link" name="affiliate-link" placeholder="Affiliate link"></input>
			<input class="button button-primary" id="save" type="submit" name="save" value="Save"></input>
			<br>
			<input class="button button-secondary" type="button" id="edit-button" name="edit" value="Edit"></input>
			<input class="input-text-wrap" id="id-to-edit" type="text" name="id-to-edit" placeholder="ID to edit"></input>
			<input class="button button-primary" id="ok-to-edit-button" type="submit" name="ok-to-edit-button" value="Save"></input>
			<br>
			<input class="input-text-wrap" id="add-image-field-input" type="text" name="add-image-field-input" placeholder="Url of the image to attach"></input>
			<input class="button button-secondary" type="button" id="add-image-button" name="add-image-button" value="Add Image"></input>		
			<input class="button button-primary" id="ok-add-image-button" type="submit" name="ok-add-image-button" value="Save"></input>
			<input class="button button-primary" id="upload-image-button" type="submit" name="upload-image-button" value="Upload image"></input>
			
		</form>	

		<div class="">
			<h3>Information in the affiliate database:</h3>
			<form method="post">
				<input class="button button-primary" type="submit" name="refresh-site" value="Refresh"></input>
			</form>

			<table class="wp-list-table widefat fixed striped posts">
				<thead>
				<tr>
					<th class="manage-column column-tags">ID</th>
					<th class="manage-column column-tags">Name</th>
					<th class="manage-column column-tags">Affiliate Link</th>
					<th class="manage-column column-tags">Shortcode</th>
					<th class="manage-column column-tags">Attached Image</th>
					<th class="manage-column column-tags">Delete</th>
				</tr>
				</thead>

				<?php
					
				$html_output = '';	
				$my_empty_array = array();
				
				foreach ($results as $result) {
						
					$html_output.= "<tr>";
					$html_output.= "<td id='' class='item-pulled-from-db'>$result->id</td>";
					$html_output.= "<td>$result->name</td>";
					$html_output.= "<td>$result->url</td>";
					$html_output.= '<td> [aff link="' . absint($result->id) . '"]' . '<br>';
					$html_output.= ' [aff image="' . absint($result->id) . '"] </td>';
					$html_output.= '<form method="post">';
					$html_output.= "<td><p>" . $result->imgurl . "</p></td>";
					$html_output.= '<td><input class="button" type="submit" name="delete' . absint($result->id) . '" value="x"></input></td>';
					$html_output.= "</tr>";
					$html_output.= '</form>';

					array_push($my_empty_array, $result->id);
				}
				echo $html_output;
				?>

			</table>
	</div>



	<?php

	if (isset($_POST['save'])) {
		
		awp_almondwp_easy_affiliate_save_info();
	}
	foreach ($my_empty_array as $key) {
		foreach ($results as $result) {
			if ($key == $result->id) {
				if(isset($_POST["delete" . $key])){
					awp_almondwp_easy_affiliate_delete_info($key);
				}
			}
		}
	}
	if (isset($_POST['CLEAN-DB'])) {
		awp_almondwp_easy_affiliate_clean_db_info();
	}
	if (isset($_POST['ok-to-edit-button'])) {
		awp_almondwp_easy_affiliate_edit_info();	
	}
}


function awp_almondwp_easy_affiliate_save_info(){
	
	global $wpdb;

	$url_regex_checker = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
	
	if (!empty($_POST['affiliate-text']) && !empty($_POST['affiliate-link']) && preg_match($url_regex_checker, $_POST['affiliate-link'])) {
		
		$new_data = array(
				'name' 	=> sanitize_text_field($_POST['affiliate-text']),
				'imgurl' => '',
				'url' 	=> sanitize_text_field($_POST['affiliate-link'])
		);	
		$success = $wpdb->insert($wpdb->prefix . 'my_affiliate_table', $new_data);
		
		if ($success) {
			echo 'Data has been saved, click the Refresh button if does not show up in the affiliate database.';
		}

	} else {
		echo 'Data has NOT been saved, check if 1) Any field was empty, 2) Url is correctly written, like http://example.com';
	}

}

function awp_almondwp_easy_affiliate_delete_info($key){
	
	global $wpdb;

	$wpdb->query('DELETE  FROM '.$wpdb->prefix.'my_affiliate_table WHERE id = "'.$key.'"');
	echo 'ID = ' . $key . ' has been deleted from the database.';

}

function awp_almondwp_easy_affiliate_edit_info(){
	
	global $wpdb;
	$url_regex_checker = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

	if (!empty($_POST['affiliate-text']) && !empty($_POST['affiliate-link']) && !empty($_POST['id-to-edit']) && preg_match($url_regex_checker, $_POST['affiliate-link'])) {

		$table = $wpdb->prefix . 'my_affiliate_table';
		$new_data = array(
				'id' 	=> sanitize_text_field($_POST['id-to-edit']),
				'name' 	=> sanitize_text_field($_POST['affiliate-text']),
				'url' 	=> sanitize_text_field($_POST['affiliate-link'])
		);
		$where = array(
			'ID' => $_POST['id-to-edit']
		);
		$wpdb->update( $table, $new_data, $where);

	} else {
		echo 'Data has NOT been saved, check if 1) Any field was empty, 2) Url is correctly written, aka: http://example.com';
	}

}

function awp_almondwp_easy_affiliate_clean_db_info(){

	global $wpdb;
	$wpdb->query('DELETE FROM wp_my_affiliate_table WHERE name IS NULL');
	echo 'DEBUG: cleaning DB...';

};

if (isset($_POST['refresh-site'])) {
	awp_almondwp_easy_affiliate_refresh_db_info();
}

function awp_almondwp_easy_affiliate_refresh_db_info(){
	
	Header('Location: '.$_SERVER['PHP_SELF'] . '?page=awp_almondwp_easy_affiliate_plugin');
}

if (isset($_POST['ok-add-image-button'])) {
	
	awp_almondwp_easy_affiliate_attach_image_to_affiliate();
}

function awp_almondwp_easy_affiliate_attach_image_to_affiliate(){

	global $wpdb;
	if (!empty($_POST['add-image-field-input'])) {

		$table = $wpdb->prefix . 'my_affiliate_table';
		$new_data = array(
				'imgurl' => $_POST['add-image-field-input']
		);
		$where = array(
				'ID' 	=> $_POST['id-to-edit']
		);
		$wpdb->update( $table, $new_data, $where);
	}


}

function awp_almond_easy_affiliate_shortcode($attr){

	global $wpdb;
	$results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'my_affiliate_table');

	foreach ($results as $key) {

		if (isset($attr['link']) && $key->id == $attr['link']) {

			switch ( absint($attr['link']) ) {

				case in_array($attr['link'], $attr):
					
					return " <a href=" . $key->url . " target='_blank'>" . $key->name . "</a>";

					break;

				default:
				
					return '<strong>No affiliated ID.</strong> ';

					break;
			}
		} 

		else if (isset($attr['image']) && $key->id == $attr['image']) {
			
			switch ( absint($attr['image']) ) {

				case in_array($attr['image'], $attr):
					
					return "<a href=" . $key->url . " target='_blank'><img src=" . $key->imgurl . "></img></a>";

					break;

				default:
				
					return '<strong>No affiliated ID.</strong> ';

					break;
			}


		} else {

			continue;
		}
	}

}
add_shortcode( 'aff', 'awp_almond_easy_affiliate_shortcode' );
