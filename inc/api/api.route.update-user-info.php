<?php

/**
 *
 * Add an endpoint to set a new password
 *
 **/

add_action( 'rest_api_init', function () {
    register_rest_route(ROUTE_NAMESPACE, '/user/(?P<id>.+)', array(

        'methods' => 'POST',

        'callback' => function ($data) {

            $data = $data->get_params();

            $user = healthos_get_user( $data['id'] );

            if( ! $user ) {
                return new WP_Error( 'bad_id' , __( 'No User Found.' , 'healthos' ) , array( 'status' => 404 ));
            }

            // TODO: update user field(not meta)
            if ($data['user_email']) {
                $data['user_login'] = $data['user_email'];
            }

            $defaults = array(
                'user_group' => 3830,
                'user_parent' => 1,
            );

            $data = array_merge($data, $defaults);

            $meta_keys = array(
                'iom_id',
                'status',
                'subscription_id',
                'expiration_date',
                'ld_group',
                'user_group',
                'user_parent',
                'phone_number'
            );

            foreach ( $meta_keys as $key ) {
                if ( array_key_exists( $key, $data ) ) {
                    update_user_meta( $user->ID, $key, $data[$key] );
                } else {
                    delete_user_meta( $user->ID, $key );
                }
            }

            $response = $user->get_user_data();
            if ( is_array($response) ) {
                return new WP_REST_Response( $response, 200 );
            } elseif ( is_wp_error( $response ) ) {
                return $response;
            }

        },

        'permission_callback' => function () {
            return true;
        },

        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric( $param );
                }
            ),
            'first_name' => array(
                'default' => null,
                'required' => true
            ),
            'last_name' => array(
                'default' => null,
                'required' => true
            ),
            'user_email' => array(
                'default' => null,
                'required' => true
            ),
            'phone_number' => array(
                'default' => null,
                'required' => true
            ),
            'timezone' => array(
                'default' => null,
            ),
            'date_format' => array(
                'default' => array(
                    'key1' => 'value1',
                    'key2' => 'value2',
                    'key3' => 'value3'
                )
            )
        )

    ));
});