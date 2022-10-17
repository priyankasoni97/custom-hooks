<?php
/**
 * This file is used for writing all the re-usable custom functions.
 *
 * @since   1.0.0
 * @package Blog_List
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'cf_get_posts' ) ) {
	/**
	 * Get the posts.
	 *
	 * @param string $post_type Post type.
	 * @param int    $paged Paged value.
	 * @param int    $posts_per_page Posts per page.
	 * @return object
	 * @since 1.0.0
	 */
	function cf_get_posts( $post_type = 'post', $paged = 1, $posts_per_page = -1 ) {
		// Prepare the arguments array.
		$args = array(
			'post_type'      => $post_type,
			'paged'          => $paged,
			'posts_per_page' => $posts_per_page,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		/**
		 * Posts/custom posts listing arguments filter.
		 *
		 * This filter helps to modify the arguments for retreiving posts of default/custom post types.
		 *
		 * @param array $args Holds the post arguments.
		 * @return array
		 */
		$args = apply_filters( 'cf_posts_args', $args );

		return new WP_Query( $args );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'cf_display_posts_html' ) ) {
	/**
	 * Function to display post detail.
	 */
	function cf_display_posts_html() {
		// Fetching posts.
		$blogpost = cf_get_posts( 'post', 1, 5 );

		// Returns, if posts is empty.
		if ( empty( $blogpost->posts ) ) {
			return;
		}

		// Fetching post columns.
		$columns = cf_get_posts_columns();
		?>
		<table class="table table-bordered">
			<thead>
				<tr>
					<?php
					if ( ! empty( $columns ) ) {
						foreach ( $columns as $column ) {
							?>
							<th><?php echo esc_html( $column ); ?></th>
							<?php
						}
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $blogpost->posts as $post ) {
					$post_id = $post;
					?>
					<tr>
						<?php
						if ( ! empty( $columns ) ) {
							foreach ( $columns as $column => $value ) {
								?>
								<td>
								<?php
									/**
									 * Posts/custom posts listing custom columns value.
									 *
									 * This filter helps you to modify the argument for retriving post columns value.
									 *
									 * @param string $column Holds column name.
									 * @param int    $post_id Holds post id.
									 * @return string
									 */
									do_action( 'cf_manage_posts_columns_value', $column, $post_id );
								?>
								</td>
								<?php
							}
						}
						?>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<?php
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'cf_get_posts_columns' ) ) {
	/**
	 * Function for return the posts columns.
	 *
	 * @param array $columns Holds posts columns.
	 * @return array
	 * @since 1.0.0
	 */
	function cf_get_posts_columns( $columns = array() ) {
		$columns = array(
			'post_id'        => 'Id',
			'post_title'     => 'Title',
			'post_thumbnail' => 'Thumbnail',
			'post_excerpt'   => 'Excerpt',
		);

		/**
		 * Posts/custom posts listing columns arguments filter.
		 *
		 * This filter helps to modify the arguments for retreiving posts columns.
		 *
		 * @param array $columns Holds post columns.
		 * @return array
		 */
		$columns = apply_filters( 'cf_manage_posts_columns', $columns );

		return $columns;
	}
}
