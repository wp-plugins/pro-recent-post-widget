<?php
/*
Plugin Name: Pro Recent Post Widget
Plugin URI: http://aynsoft.com/
Description: Pro Recent Post Widget plugin.You have choice to specific category recent post show
Version: 1.1
Author: Shambhu Prasad Patnaik
Author URI:http://aynsoft.com/
*/
class Pro_Recent_Post_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'pro_recent_post', // Base ID
			'Pro Recent Posts', // Name
			array( 'description' => __( 'The most recent posts on your site', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	 extract( $args );
	 $title = apply_filters( 'widget_title', $instance['title'] );
	 if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
 	 $number = 5;
	 $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
	 $post_category = empty( $instance['post_category'] ) ? '' : $instance['post_category'];
	 $exclude_post = explode(',',empty( $instance['exclude_post'] ) ? '' : $instance['exclude_post']);

     $r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true,'cat'=>$post_category,'post__not_in'=>$exclude_post ) ) );
	 //print_r($r);die();
	 if ($r->have_posts()) :
     ?>
	 <?php echo $before_widget; ?>
	 <?php if ( $title ) echo $before_title . $title . $after_title; ?>
	 <ul>
	 <?php while ( $r->have_posts() ) : $r->the_post(); ?>
		<li>
		<a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
		<?php if ( $show_date ) : ?>
		<span class="post-date"><?php echo get_the_date(); ?></span>
		<?php endif; ?>
		</li>
	 <?php endwhile; ?>
	 </ul>
	 <?php echo $after_widget; ?>
     <?php
	 // Reset the global $the_post as this query will have stomped on it
	 endif;
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = (bool) $new_instance['show_date'];
		$instance['post_category'] = strip_tags($new_instance['post_category']);
		$instance['exclude_post'] = strip_tags($new_instance['exclude_post']);
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
	 if ( isset( $instance[ 'title' ] ) ) {
	  $title = $instance[ 'title' ];
	 }
	 else {
	  $title = __( 'Recent Posts', 'text_domain' );
 	 }
	 $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
	 $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
	 $post_category    = isset( $instance['post_category'] ) ? esc_attr($instance['post_category']) : '';
	 $exclude_post    = isset( $instance['exclude_post'] ) ? esc_attr($instance['exclude_post']) : '';
	?>
	 <p>
	  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	   <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	 </p>
	 <p>
	  <label for="<?php echo $this->get_field_id( 'post_category' ); ?>"><?php _e( 'Post Categories:' ); ?></label> 
	  <input class="widefat" id="<?php echo $this->get_field_id( 'post_category' ); ?>" name="<?php echo $this->get_field_name( 'post_category' ); ?>" type="text" value="<?php echo esc_attr( $post_category); ?>" />
	  <br />
	  <small><?php _e( 'category IDs, separated by commas.' ); ?></small>
	 </p>
	 <p>
	  <label for="<?php echo $this->get_field_id( 'exclude_post' ); ?>"><?php _e( 'Exclude Post  ID\'s:' ); ?></label> 
	  <input class="widefat" id="<?php echo $this->get_field_id( 'exclude_post' ); ?>" name="<?php echo $this->get_field_name( 'exclude_post' ); ?>" type="text" value="<?php echo esc_attr( $exclude_post); ?>" />
	  <br />
	  <small><?php _e( 'Exclude Post IDs, separated by commas.' ); ?></small>
	 </p>
	 <p>
	  <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
	  <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	 </p>
	 <p>
	  <input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
	  <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label>
	 </p>
	 <?php 
	}
} // class pro_recent_post

// register Pro_Recent_Post_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "Pro_Recent_Post_Widget" );' ) );
register_deactivation_hook(__FILE__, 'pro_recent_post_widget_deactivate');

if (!function_exists('pro_recent_post_widget_deactivate')):
function pro_recent_post_widget_deactivate ()
{
 unregister_widget('Pro_Recent_Post_Widget');
}
endif;
?>