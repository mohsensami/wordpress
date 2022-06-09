<?php

//Register post type
//
if( !function_exists('sami_contact_post_type_callback') ){
	function sami_contact_post_type_callback() {
		$args = array(
			'public'    => true,
			'label'     => __( 'Contacts', 'textdomain' ),
			'supports' => [ 'custom-fields',  'title', 'editor' ]
		);
		register_post_type( 'contact', $args );
	}
	add_action( 'init', 'sami_contact_post_type_callback' );	
}




add_action('init', function(){
    register_rest_route( 'api/v1', '/test/(?P<sami_param>\d+)', array(
        'methods' => 'GET',
        'callback' => 'sami_api_test_callback'

    ));
});

//GET routes
add_action('init', function(){
    register_rest_route( 'api/v1', '/contacts/', array(
        'methods' => 'GET',
        'callback' => 'sami_api_get_callback'

    ));
});
add_action('init', function(){
    register_rest_route( 'api/v1', '/contacts/(?P<contact_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'sami_api_get_callback'

    ));
});

//POST
add_action('init', function(){
    register_rest_route( 'api/v1', '/contacts/create/', array(
        'methods' => 'POST',
        'callback' => 'sami_api_post_callback'

    ));
});

//PUT
add_action('init', function(){
    register_rest_route( 'api/v1', '/contacts/update/(?P<contact_id>\d+)', array(
        'methods' => 'PUT',
        'callback' => 'sami_api_put_callback'

    ));
});

//DELETE
add_action('init', function(){
    register_rest_route( 'api/v1', '/contacts/(?P<contact_id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'sami_api_delete_callback'

    ));
});

function sami_api_test_callback( $request ){
    $response['status'] =  200;
    $response['success'] = true;
    $response['data'] = $request->get_param('sami_param');
    return new WP_REST_Response( $response );
}

/**
 * Get all contacts from our WordPress Installation
 * @param  object $request WP_Request with data
 * @return obeject         WP_REST_Response
 */
function sami_api_get_callback( $request ){

    
    $contact_id = $request->get_param('contact_id');
    if( empty( $contact_id ) ){
    	$posts = get_posts( [ 'post_type' => 'contact', 'post_status' => 'publish' ] );
	   
	    if( count($posts) > 0 ){
	    	$response['status'] =  200;	
	    	$response['success'] = true;
	    	$response['data'] = $posts;
	    }else{
	    	$response['status'] =  200;
	    	$response['success'] = false;	
	    	$response['message'] = 'NO posts!';
	    }
    }else{
    	if( $contact_id > 0 ){
    		$post = get_post( $contact_id );	
    		if( !empty( $post ) ){
    			$response['status'] =  200;	
		    	$response['success'] = true;
		    	$response['data'] = $post;	
    		}else{
    			$response['status'] =  200;	
	    		$response['success'] = false;
	    		$response['message'] = 'No post found!';	
    		}
    		
    	}
    }
    
    wp_reset_postdata();
    return new WP_REST_Response( $response );
}

/**
 * Create a contact post by rest api
 * @param  object $request WP_Request with data
 * @return obeject         WP_REST_Response
 */
function sami_api_post_callback( $request ){


	$post['post_title'] = sanitize_text_field( $request->get_param( 'title' ) );
	$post['post_content'] = sanitize_text_field( $request->get_param( 'content' ) );
	$post['meta_input'] = [
		'genre' => sanitize_text_field( $request->get_param( 'meta_genre' ) )
	];
	$post['post_status'] = 'publish';
	$post['post_type'] = 'contact';
	$new_post_id = wp_insert_post( $post );

	if( !is_wp_error( $new_post_id ) ){
		$response['status'] =  200;	
		$response['success'] = true;
		$response['data'] = get_post( $new_post_id ) ;	
	}else{
		$response['status'] =  200;	
	   	$response['success'] = false;
	    $response['message'] = 'No post found!';	
	}

	return new WP_REST_Response( $response );

}


/**
 * Update a contact post
 * @param  object $request WP_Request with data
 * @return obeject         WP_REST_Response
 */
function sami_api_put_callback( $request ){
	$contact_id = $request->get_param('contact_id');
	if( $contact_id > 0 ){
		$post['ID'] = $contact_id;
		$post['post_title'] = sanitize_text_field( $request->get_param( 'title' ) );
		$post['post_content'] = sanitize_text_field( $request->get_param( 'content' ) );
		$post['meta_input'] = [
			'genre' => sanitize_text_field( $request->get_param( 'meta_genre' ) )
		];
		$post['post_status'] = 'publish';
		$post['post_type'] = 'contact';
		$new_post_id = wp_update_post( $post, true );

		if( !is_wp_error( $new_post_id ) ){
			$response['status'] =  200;	
			$response['success'] = true;
			$response['data'] = $new_post_id;	
		}else{
			$response['status'] =  200;	
		   	$response['success'] = false;
		    $response['message'] = 'No post found!';	
		}

		
	}else{
		$response['status'] =  200;	
		$response['success'] = false;
		$response['message'] = 'Contact id is no set!';	
	}
	return new WP_REST_Response( $response );
}

function sami_api_delete_callback( $request ){
	$contact_id = $request->get_param('contact_id');
	if( $contact_id > 0 ){
		$deleted_post = wp_delete_post( $contact_id );
		if( !empty( $deleted_post ) ){
			$response['status'] =  200;	
			$response['success'] = true;
			$response['data'] = $deleted_post;	
		}else{
			$response['status'] =  200;	
		   	$response['success'] = false;
		    $response['message'] = 'No post found!';	
		}
	}else{
		$response['status'] =  200;	
		$response['success'] = false;
		$response['message'] = 'Contact id is no set!';	
	}
	return new WP_REST_Response( $response );	
}
