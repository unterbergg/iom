<?php

/**
 *
 * Add an endpoint to set a new password
 *
 **/

add_action( 'rest_api_init', function () {
    register_rest_route(ROUTE_NAMESPACE, '/user/(?P<id>.+)', array(

        'methods' => 'PUT',

        'callback' => function ($data) {

            $data = $data->get_params();
            $user = healthos_get_user( $data['id'] );

            if( ! $user ) {
                return new WP_Error( 'bad_id' , __( 'No User Found.' , 'healthos' ) , array( 'status' => 404 ));
            }

            $userdata = array(
                'user_login' => $data['user_email'],
                'user_email' => $data['user_email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'display_name' => $data['display_name'],
            );

            $userdata['display_name'] = $data['display_name'] ?? $data['first_name'] . " " . $data['last_name'];

            $user = $user->update_user_data($userdata);

            $defaults = array(
                'user_group' => 3830,
                'user_parent' => 1,
            );

            $data = array_merge($data, $defaults);


            // TODO: move to functions
            if ($data['weight']) {
                $measurement = [];
                switch ($data['units']) {
                    case 'imperial' :
                        $measurement['imperial'] = floatval($data['weight']);
                        $measurement['metric'] = $data['weight'] / 2.2046;
                        break;
                    case 'metric' :
                        $measurement['metric'] = floatval($data['weight']) ;
                        $measurement['imperial'] = $data['weight'] * 2.2046;
                        break;
                }
                $data['weight'] = json_encode($measurement);
            }

            // TODO: move to functions
            if ($data['height']) {
                $measurement = [];
                switch ($data['units']) {
                    case 'imperial' :
                        $inches = (json_decode($data['height'])[0]*12) + json_decode($data['height'])[1];
                        $measurement['imperial'] = $inches;
                        $measurement['metric'] = $inches * 2.54;
                        break;
                    case 'metric' :
                        $measurement['metric'] = floatval($data['height']) ;
                        $measurement['imperial'] = $data['height'] / 2.54;
                        break;
                }
                $data['height'] = json_encode($measurement);
            }

            if ($data['bests']) {
                $bests = json_decode($data['bests'], true);
                if (isset($bests['strength'])) {
                    foreach ($bests['strength'] as $key => $value ) {
                        if (healthos_check_measurement_units($key)) {
                            $bests['strength'][$key] = healthos_get_strength_units($data['units'], $value);
                        }
                    }
                }
                $data['bests'] = json_encode($bests);
            }

            $meta_keys = array(
                'iom_id',
                'status',
                'subscription_id',
                'expiration_date',
                'ld_group',
                'user_group',
                'user_parent',
                'phone_number',
                'date_format',
                'timezone',
                'messenger',
                'notifications',
                'notification_switcher',
                'birthday',
                'gender',
                'units',
                'weight',
                'height',
                'bests',
                'equipment',
                'profile_photo'
            );

//            return json_decode($data['notifications']);

            foreach ( $meta_keys as $key ) {

                if ( array_key_exists( $key, $data ) && $data[$key] ) {
                    update_user_meta( $user->ID, $key, $data[$key] );
                }
            }

            $response = $user->get_user_data();
            if ( is_array($response) ) {
                return new WP_REST_Response( $response, 200 );
            } elseif ( is_wp_error( $response ) ) {
                return $response;
            }

        },

        /*'permission_callback' => 'is_user_logged_in',*/
        'permission_calback' => function () {
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
                'default' => null
            ),
            'messenger' => array(
                'default' => null,
                'items' => [
                    'type' => 'array'
                ]
            ),
            'notifications' => array(
                'default' => null,
                'items' => [
                    'type' => 'array'
                ]
            ),
            'notification_switcher' => array(
                'default' => null
            ),
            'birthday' => array(
                'default' => null
            ),
            'gender' => array(
                'default' => null
            ),
            'units' => array(
                'default' => null
            ),
            'weight' => array(
                'default' => null,
            ),
            'height' => array(
                'default' => null
            ),
            'bests' => array(
                'default' => null,
            ),
            'equipment' => array(
                'default' => null,
                'items' => [
                    'type' => 'array'
                ]
            ),
            'profile_photo' => array(
                'default' => null,
                'description' => __('"wp-json/wp/v2/media" response["source_url"]')
            ),
        )

    ));
});