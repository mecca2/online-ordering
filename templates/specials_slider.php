
 <div class="product-slider slider">
    <?php
    	function printTitleWithLink($post_id){
    		echo '<div class="product_title"><a href="' . get_permalink( $post_id ) . '">';
    		echo woocommerce_template_single_title();
    		echo '</a></div>';
    	}
    	function drawImageDisplay($post_id){
    		$post_thumbnail_id = get_post_thumbnail_id( $post_id );
			$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
			$thumbnail_post    = get_post( $post_thumbnail_id );
			$image_title       = $thumbnail_post->post_content;
			$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';

			$attributes = array(
				'title'                   => $image_title,
				'data-large-image'        => $full_size_image[0],
				'data-large-image-width'  => $full_size_image[1],
				'data-large-image-height' => $full_size_image[2],
			);
    		if ( has_post_thumbnail() ) {
				$html  = '<a href="' . get_permalink( $post_id ) . '">';
				$html .= get_the_post_thumbnail( $post_id, 'shop_single', $attributes );
				$html .= '</a>';
			} else {
				$html  = '<a href="' . get_permalink( $post_id ) . '">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
				$html .= '</a>';
			}
			echo '<div class="product_image">' . $html . '</div>';
    	}
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 12,
			'meta_query'     => array(
                    array( // Simple products type
                        'key'           => '_mp_special',
                        'value'         => 'yes'
                    )
                )
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post(); ?>
				<div>
				<div class="product_container">
				<?php

				 //echo get_the_ID();
				 //echo get_post_thumbnail_id( get_the_ID() );
				 /*$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' );?>

    			<img src="<?php  echo $image[0]; ?>" data-id="<?php echo get_the_ID(); ?>"><?php*/
				//woocommerce_template_single_title();
				printTitleWithLink(get_the_ID());
				drawImageDisplay(get_the_ID());
				//woocommerce_template_single_price();
				if (get_post_meta(get_the_ID())["_product_attributes"][0] == "a:0:{}"){
					//woocommerce_template_single_add_to_cart();
				}
				/**
				 * woocommerce_after_shop_loop_item hook.
				 *
				 * @hooked woocommerce_template_loop_product_link_close - 5
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				//do_action( 'woocommerce_after_shop_loop_item' );
				?>
				</div>
				</div><?php
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
	?>
</div>
<script type="text/javascript" src="<?php echo plugins_url( 'public/slick/slick.min.js', __DIR__ ); ?>"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
      	jQuery('.product-slider').slick({
      		dots: true, 
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 4, 
			adaptiveHeight: true
		});
    });
</script>