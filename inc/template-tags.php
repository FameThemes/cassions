<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Cassions
 */

if ( ! function_exists( 'cassions_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function cassions_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'on %s', 'post date', 'cassions' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'cassions' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline"> ' . $byline . '</span> <span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;


if ( ! function_exists( 'cassions_block_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function cassions_block_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'on %s', 'post date', 'cassions' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);



	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;



if ( ! function_exists( 'cassions_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function cassions_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'cassions' ) );
		if ( $categories_list && cassions_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'cassions' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'cassions' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'cassions' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'cassions' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'cassions' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function cassions_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'cassions_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'cassions_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so cassions_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so cassions_categorized_blog should return false.
		return false;
	}
}

if ( ! function_exists( 'cassions_comments' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own codilight_lite_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @return void
 */
 function cassions_comments( $comment, $args, $depth ) {
 	$GLOBALS['comment'] = $comment;
 	switch ( $comment->comment_type ) :
 		case 'pingback' :
 		case 'trackback' :
 	?>
 	<li class="pingback">
 		<p><?php _e( 'Pingback:', 'techone' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'cassions' ), ' ' ); ?></p>
 	<?php
 			break;
 		default :
 	?>
 	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
 		<article id="comment-<?php comment_ID(); ?>" class="comment">
 			<div class="comment-author vcard">
 				<?php echo get_avatar( $comment, 60 ); ?>
 				<?php //printf( '<cite class="fn">%s</cite>', get_comment_author_link() ); ?>
 			</div><!-- .comment-author .vcard -->

 			<div class="comment-wrapper">
 				<?php if ( $comment->comment_approved == '0' ) : ?>
 					<em><?php _e( 'Your comment is awaiting moderation.', 'cassions' ); ?></em>
 				<?php endif; ?>

 				<div class="comment-meta comment-metadata">
 					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
 					<?php
 						/* translators: 1: date, 2: time */
 						printf( __( '%1$s at %2$s', 'techone' ), get_comment_date(), get_comment_time() ); ?>
 					</time></a>
 				</div><!-- .comment-meta .commentmetadata -->
 				<div class="comment-content"><?php comment_text(); ?></div>
 				<div class="comment-actions">
 					<?php comment_reply_link( array_merge( array( 'after' => '<i class="fa fa-reply"></i>' ), array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
 				</div><!-- .reply -->
 			</div> <!-- .comment-wrapper -->

 		</article><!-- #comment-## -->

 	<?php
 			break;
 	endswitch;
 }
endif;


if ( ! function_exists( 'cassions_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 * @since cassions
 */
function cassions_the_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
endif;

/**
 * Flush out the transients used in cassions_categorized_blog.
 */
function cassions_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'cassions_categories' );
}
add_action( 'edit_category', 'cassions_category_transient_flusher' );
add_action( 'save_post',     'cassions_category_transient_flusher' );
