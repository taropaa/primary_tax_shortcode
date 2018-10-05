<?php
/*
* Plugin Name: Primary Taxonomy
* Description: Display the primary category of a post. Requires YOAST's SEO Plugin. Usage: [primary_term tax="taxonomy"]
* Version: 100
* Author: TD Labs Ltd.
* Author URI: https://tdlabs.ca
*/

function primary_term_func($atts){

	$a = shortcode_atts( array(
		'tax' => 'something'
	), $atts );

	$postId = get_the_ID();
	$yourTaxonomy = $a['tax'];

	// SHOW YOAST PRIMARY CATEGORY, OR FIRST CATEGORY
	$category = get_the_terms( $postId, $yourTaxonomy );
	$useCatLink = false;
	// If post has a category assigned.
	
	if ($category){
		$category_display = '';
		$category_link = '';
		if ( class_exists('WPSEO_Primary_Term') )
		{
			// Show the post's 'Primary' category, if this Yoast feature is available, & one is set
			$wpseo_primary_term = new WPSEO_Primary_Term( $yourTaxonomy, get_the_id() );
			$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
			$term = get_term( $wpseo_primary_term );
			if (is_wp_error($term)) { 
				// Default to first category (not Yoast) if an error is returned
				$category_display = $category[0]->name;
				$category_link = get_bloginfo('url') . '/' . $yourTaxonomy . '/' . strtolower($category[0]->name);
			} else { 
				// Yoast Primary category
				$category_display = $term->name;
				$category_link = get_category_link( $term->term_id );
			}
		} 
		else {
			// Default, display the first category in WP's list of assigned categories
			$category_display = $category[0]->name;
			$category_link = get_category_link( $category[0]->term_id );
		}
		// Display category
		if ( !empty($category_display) ){
			if ( $useCatLink == true && !empty($category_link) ){
				return '<a href="'.$category_link.'">'.htmlspecialchars($category_display).'</a>';
			} else {
				return htmlspecialchars($category_display);
			}
		}
		
	}

}

add_shortcode('primary_term', 'primary_term_func');
