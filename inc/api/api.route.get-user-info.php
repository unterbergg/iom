<?php
/**
 *
 *
 *
 */

/*add_action( 'rest_api_init', function () {
    $user_keys = array(
        'iom_id' => 'integer',
        'status' => 'string',
        'subscription_id' => 'integer',
        'expiration_date' => 'integer',
        'ld_group' => 'integer',
        'user_group' => 'integer',
        'user_parent' => 'integer',
        'user_new' => 'integer',
    );

    foreach ( $user_keys as $key => $type ) {
        register_rest_field (
            'user',
            $key,
            array(
                'get_callback'    => 'get_user_meta_callback',
                'update_callback' => 'update_user_meta_callback',
                'schema'          => array(
                    'description' => __( 'user meta' ),
                    'type'        => $type
                ),
            )
        );
    }
} );*/


/**
 *
 * Add an endpoint to get user info
 *
 **/

add_action( 'rest_api_init', function () {


    $user_keys = array(
        'iom_id' => 'integer',
        'status' => 'string',
        'subscription_id' => 'integer',
        'expiration_date' => 'integer',
        'ld_group' => 'integer',
        'user_group' => 'integer',
        'user_parent' => 'integer',
        'user_new' => 'integer',
        'phone_number' => 'string',
        'timezone' => 'string',
        'date_format' => 'string'
    );

    foreach ( $user_keys as $key => $type ) {
        register_rest_field (
            'user',
            $key,
            array(
                'get_callback'    => 'get_user_meta_callback',
                'update_callback' => 'update_user_meta_callback',
                'schema'          => array(
                    'description' => __( 'user meta' ),
                    'type'        => $type
                ),
            )
        );
    }

    register_rest_route( ROUTE_NAMESPACE , '/user/(?P<id>.+)' , array(

        'methods' => 'GET',

        'callback' => function( $data ) {

            $data = $data->get_params();

            if ( empty( $data['id'] ) || $data['id'] === '' ) {
                return new WP_Error( 'no_email' , __( 'You Must Provide an User ID.' , 'healthos' ) , array( 'status' => 400 ));
            }

            try {
                $user = healthos_get_user( $data['id'] );
                if( ! $user ) {
                    return new WP_Error( 'bad_id' , __( 'No User Found.' , 'healthos' ) , array( 'status' => 404 ));
                }
                $response = $user->get_user_data();
            } catch( Exception $e ) {
                return new WP_Error( 'bad_request' , $e->getMessage() , array( 'status' => 500 ));
            }

            if ( is_array( $response ) ) {
                return new WP_REST_Response( $response, 200 );
            } elseif ( is_wp_error( $response ) ) {
                return $response;
            }

        },

        'permission_callback' => function() {
            return true;
        },

    ));
});
