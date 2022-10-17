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
		add_action( 'wp_enqueue_scripts', array( $this, 'cf_wp_enqueue_scripts_callback' ) );
		add_shortcode( 'cf_list_posts', array( $this, 'cf_list_posts_callback' ) );
		add_filter( 'cf_manage_posts_columns', array( $this, 'cf_manage_posts_columns_callback' ), 10, 1 );
		add_action( 'cf_manage_posts_columns_value', array( $this, 'cf_manage_posts_columns_value_callback' ), 10, 2 );
	}

	/**
	 * Enqueue scripts for public end.
	 */
	public function cf_wp_enqueue_scripts_callback() {
		// Bootstarap min style.
		wp_enqueue_style(
			'bootstrap-min-style',
			CF_PLUGIN_URL . 'assets/public/css/lib/bootstrap.min.css',
			array(),
			filemtime( CF_PLUGIN_PATH . 'assets/public/lib/bootstrap.min.css' ),
		);

		// Font Awsome Style.
		wp_enqueue_style( // phpcs:ignore
			'font-awsome-style',
			'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css',
		);

		// Custom public style.
		wp_enqueue_style(
			'core-functions-public-style',
			CF_PLUGIN_URL . 'assets/public/css/core-functions-public.css',
			array(),
			filemtime( CF_PLUGIN_PATH . 'assets/public/css/core-functions-public.css' ),
		);

		// Bootstrap min script.
		wp_enqueue_script(
			'bootstrap-min-script',
			CF_PLUGIN_URL . 'assets/public/js/lib/bootstrap.min.js',
			array( 'jquery' ),
			filemtime( CF_PLUGIN_PATH . 'assets/public/js/lib/bootstrap.min.js' ),
			true
		);

		// Custom public script.
		wp_enqueue_script(
			'core-functions-public-script',
			CF_PLUGIN_URL . 'assets/public/js/core-functions-public.js',
			array( 'jquery' ),
			filemtime( CF_PLUGIN_PATH . 'assets/public/js/core-functions-public.js' ),
			true
		);

		// Localize public script.
		wp_localize_script(
			'core-functions-public-script',
			'CF_Public_JS_Obj',
			array(
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'is_blog_page' => is_page( 'blog' ) ? 'yes' : 'no',
			)
		);
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
	 * Function for custom hook action callback.
	 *
	 * This filter provide the facility to add custom columns in post list.
	 *
	 * @param array $columns Holds columns array.
	 */
	public function cf_manage_posts_columns_callback( $columns ) {
		$columns['edit']   = 'Edit';
		$columns['delete'] = 'Delete';

		return $columns;
	}

	/**
	 * Function for custom hook action callback.
	 *
	 * This filter provide the facility to add custom columns value in post list.
	 *
	 * @param string $column Holds columns name.
	 * @param int    $post_id Holds post id.
	 */
	public function cf_manage_posts_columns_value_callback( $column, $post_id ) {
		switch ( $column ) {
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
			case 'edit':
				echo '<a href="#">Edit</a>';
				break;
			case 'delete':
				echo '<a href="#">Delete</a>';
				break;
		}
	}
}
