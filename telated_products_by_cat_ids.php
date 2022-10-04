<?php
if( current_user_can('administrator') ) {



    global $wp_query;
    $terms_post = get_the_terms( $post->cat_ID , 'product_cat' );
    foreach ($terms_post as $term_cat) {
        $term_cat_id[] = $term_cat->term_id;
    }



    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'order'                  => 'ASC',
        'orderby'                => 'ID',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $term_cat_id,
                'operator' => 'IN',
            )
        ),
    );

    $products = new WP_Query($args);
    if ( $products->have_posts() ) {
        echo '<ul class="container">';
        while ( $products->have_posts() ) {
            $products->the_post();
            echo '<li>' . get_the_title() . '</li>';
//            echo '<li>' . the_post_thumbnail('thumbnail') . '</li>';
        }
        echo '</ul>';
    } else {
        // no posts found
    }
    /* Restore original Post Data */
    wp_reset_postdata();

}
?>
