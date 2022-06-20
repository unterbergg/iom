<?php

/**
 *
 * Add an endpoint to set a new password
 *
 **/

add_action( 'rest_api_init', function () {
    register_rest_route( ROUTE_NAMESPACE , '/user/set-password' , array(

        'methods' => 'POST',

        'callback' => function( $data ) {

            if( empty( $data['password'] ) || $data['password'] === '' ) {
                return new WP_Error( 'no_code' , __( 'You Must Provide a New Password.' , 'healthos' ) , array( 'status' => 400 ) );
            }

            $exists = wp_get_current_user();

            if( ! $exists ) {
                return new WP_Error( 'bad_email' , __( 'No User Found.' , 'healthos' ) , array( 'status' => 404 ));
            }

            try {
                $user = healthos_get_user( $exists->ID );
                $user->set_new_password( $data['password'] );

            } catch( Exception $e ) {
                return new WP_Error( 'bad_request' , $e->getMessage() , array( 'status' => 500 ));
            }

            return array(
                'data' => array(
                    'status' => 200,
                ),
                'message' => __( 'Password Reset Successfully.' , 'healthos' ),
            );

        },

        'permission_callback' => function() {
            return true;
        },

        'args' => array(
            'password' => array(
                'validate_callback' => function($param, $request, $key) {
                    return $param;
                }
            ),
        )

    ));
});
