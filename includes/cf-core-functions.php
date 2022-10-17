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
		$blogpost = cf_get_posts( 'post', 1, 5 );
		// Returns, if posts is empty.
		if ( empty( $blogpost->posts ) ) {
			return;
		}
		$columns = cf_get_posts_columns();
		?>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th scope="col">Post ID</th>
					<th scope="col">Post Title</th>
					<th scope="col">Post Thumbnail</th>
					<th scope="col">Post Excerpt</th>
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
					$post_id        = $post;
					$post_title     = get_the_title( $post_id );
					$post_excerpt   = wp_filter_nohtml_kses( get_the_excerpt( $post_id ) ); // Strips all HTML from a post content .
					$post_thumbnail = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
					?>
					<tr>
						<th scope="row"> <?php echo esc_html( $post_id ); ?> </th>
						<td> <?php echo esc_html( $post_title ); ?> </td>
						<td> <img src="<?php echo esc_url( $post_thumbnail ); ?>"> </td>
						<td> <?php echo esc_html( $post_excerpt ); ?> </td>
						<?php
						if ( ! empty( $columns ) ) {
							foreach ( $columns as $column => $value ) {
								?>
								<td>
								<?php
									// Retrive the custom columns value added using this action hook.
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
	 */
	function cf_get_posts_columns( $columns = array() ) {
		$columns = array(
			'post_author'  => 'Post Author',
			'author_email' => 'Author Email',
			'view'         => 'View',
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
