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

            if ( empty( $data['email'] ) || $data['email'] === '' ) {
                return new WP_Error( 'no_email' , __( 'You Must Provide an Email Address.' , 'healthos' ) , array( 'status' => 400 ));
            }

            if( empty( $data['password'] ) || $data['password'] === '' ) {
                return new WP_Error( 'no_code' , __( 'You Must Provide a Password.' , 'healthos' ) , array( 'status' => 400 ) );
            }

            if( empty( $data['new_password'] ) || $data['new_password'] === '' ) {
                return new WP_Error( 'no_code' , __( 'You Must Provide a New Password.' , 'healthos' ) , array( 'status' => 400 ) );
            }

            $exists = email_exists( $data['email'] );

            if( ! $exists ) {
                return new WP_Error( 'bad_email' , __( 'No User Found.' , 'healthos' ) , array( 'status' => 404 ));
            }

            try {
                $user = healthos_get_user( $exists );
                $userdata = $user->get_user_pass();
                $passValidate = wp_check_password( $data['password'], $userdata['user_pass'], $userdata['ID'] );
                if ($passValidate) {
                    $user->set_new_password( $data['password'] );
                } else {
                    return new WP_Error( 'bad_password' , __( 'Incorrect Password.' , 'healthos' ) , array( 'status' => 500 ));
                }
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
            'email' => array(
                'validate_callback' => function($param, $request, $key) {
                    return $param;
                }
            ),
            'password' => array(
                'validate_callback' => function($param, $request, $key) {
                    return $param;
                }
            ),
            'new_password' => array(
                'validate_callback' => function($param, $request, $key) {
                    return $param;
                }
            )
        )

    ));
});
