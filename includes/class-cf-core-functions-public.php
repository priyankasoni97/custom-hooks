<?php
/**
 * The file that defines the core plugin class.
 *
 * A class definition that holds all the hooks regarding all the custom functionalities.
 *
 * @link       https://github.com/priyankasoni97/
 * @since      1.0.0
 *
 * @package    Blog_List_Public
 * @subpackage Blog_List_Public/includes
 */

/**
 * The core plugin class.
 *
 * A class definition that holds all the hooks regarding all the custom functionalities.
 *
 * @since      1.0.0
 * @package    Blog_List
 * @author     Priyanka Soni <priyanka.soni@cmsminds.com>
 */
class Cf_Core_Functions_Public {
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Load all the hooks here.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_shortcode( 'cf_list_posts', array( $this, 'cf_list_posts_callback' ) );
		add_filter( 'cf_manage_posts_columns', array( $this, 'cf_manage_posts_columns_callback' ), 10, 1 );
		add_action( 'cf_manage_posts_columns_value', array( $this, 'cf_manage_posts_columns_value_callback' ), 100, 2 );
	}

	/**
	 * Function for shorcode callback to list posts.
	 */
	public function cf_list_posts_callback() {
		// Preparing html.
		ob_start();
		cf_display_posts_html();

		return ob_get_clean();
	}

	/**
	 * Function for cf_manage_posts_columns action callback.
	 *
	 * @param array $columns Holds columns array.
	 */
	public function cf_manage_posts_columns_callback( $columns ) {
		$columns['post_id']      = 'Post Id';
		$columns['post_author']  = 'Post Author';
		$columns['author_email'] = 'Author Email';
		$columns['view']         = 'View';

		return $columns;
	}

	/**
	 * Function for cf_manage_posts_columns_value action callback.
	 *
	 * @param string $column Holds columns name.
	 * @param int    $post_id Holds post id.
	 */
	public function cf_manage_posts_columns_value_callback( $column, $post_id ) {
		switch ( $column ) {
			case 'post_id':
				echo esc_html( $post_id );
				break;
			case 'post_title':
				echo esc_html( get_the_title( $post_id ) );
				break;
			case 'post_thumbnail':
				$post_thumbnail = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
				echo '<img src="' . esc_url( $post_thumbnail ) . '" alt="img">';
				break;
			case 'post_excerpt':
				$post_excerpt = wp_filter_nohtml_kses( get_the_excerpt( $post_id ) ); // Strips all HTML from a post content .
				echo esc_html( $post_excerpt );
				break;
			case 'post_author':
				$author_id = get_post( $post_id )->post_author;
				$author    = get_userdata( $author_id );
				echo esc_html( $author->first_name . ' ' . $author->last_name );
				break;
			case 'author_email':
				$author_id = get_post( $post_id )->post_author;
				$author    = get_userdata( $author_id );
				echo esc_html( $author->user_email );
				break;
			case 'view':
				echo '<a href="' . esc_url( get_permalink( $post_id ) ) . '">View</a>';
				break;
		}
	}
}
