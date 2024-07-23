<?php
add_action( 'wp_ajax_load_more', 'ajax_script_post_load_more' );

function ajax_script_post_load_more( bool $initial_request = false ) {

    if ( ! $initial_request && ! check_ajax_referer( 'loadmore_post_nonce', 'ajax_nonce', false ) ) {
        wp_send_json_error( __( 'Invalid security token sent.', 'text-domain' ) );
        wp_die( '0', 400 );
    }

    // Check if it's an ajax call.
    $is_ajax_request = ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&
                       strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest';
    /**
     * Page number.
     * If get_query_var( 'paged' ) is 2 or more, its a number pagination query.
     * If $_POST['page'] has a value which means its a loadmore request, which will take precedence.
     */
    $page_no = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    $page_no = ! empty( $_POST['page'] ) ? filter_var( $_POST['page'], FILTER_VALIDATE_INT ) + 1 : $page_no;

    // Default Argument.
    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 6, // Number of posts per page - default
        'paged'          => $page_no,
    ];

    $query = new WP_Query( $args );

    if ( $query->have_posts() ):
        // Loop Posts.
        while ( $query->have_posts() ): $query->the_post();
            get_template_part( 'template-parts/components/post-card' );
        endwhile;

        // Pagination for Google.
        if ( ! $is_ajax_request ) :
            $total_pages = $query->max_num_pages;
            get_template_part( 'template-parts/common/pagination', null, [
                'total_pages'  => $total_pages,
                'current_page' => $page_no,
            ] );
        endif;
    else:
        // Return response as zero, when no post found.
        wp_die( '0' );
    endif;

    wp_reset_postdata();

    /**
     * Check if its an ajax call, and not initial request
     *
     * @see https://wordpress.stackexchange.com/questions/116759/why-does-wordpress-add-0-zero-to-an-ajax-response
     */
    if ( $is_ajax_request && ! $initial_request ) {
        wp_die();
    }
}