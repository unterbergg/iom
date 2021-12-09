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

                $data['bests'] = $bests;
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
                'bests'
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
            // TODO: Review
            'date_format' => array(
                'default' => array(
                    'MM/DD/YYYY' => false,
                    'DD/MM/YYYY' => false,
                    'YYYY/MM/DD' => false
                )
            ),
            // TODO: Review
            'date_format' => array(
                'default' => array(
                    '(GMT +00:00) GMT' => false,
                    '(GMT +00:00) UTC' => false,
                    '(GMT +01:00) ECT' => false,
                    '(GMT +02:00) EET' => false,
                    '(GMT +02:00) ART' => false,
                    '(GMT +03:00) EAT' => false,
                    '(GMT +03:30) MET' => false,
                    '(GMT +04:00) NET' => false,
                    '(GMT +05:00) PLT' => false,
                    '(GMT +05:30) IST' => false,
                    '(GMT +06:00) BST' => false,
                    '(GMT +07:00) VST' => false,
                    '(GMT +08:00) CTT' => false,
                    '(GMT +09:00) JST' => false,
                    '(GMT +09:30) ACT' => false,
                    '(GMT +10:00) AET' => false,
                    '(GMT +11:00) SST' => false,
                    '(GMT +12:00) NST' => false,
                    '(GMT -11:00) MIT' => false,
                    '(GMT -10:00) HST' => false,
                    '(GMT -09:00) AST' => false,
                    '(GMT -08:00) PST' => false,
                    '(GMT -07:00) PNT' => false,
                    '(GMT -07:00) MST' => false,
                    '(GMT -06:00) CST' => false,
                    '(GMT -05:00) EST' => false,
                    '(GMT -05:00) IET' => false,
                    '(GMT -04:00) PRT' => false,
                    '(GMT -03:30) CNT' => false,
                    '(GMT -03:00) AGT' => false,
                    '(GMT -03:00) BET' => false,
                    '(GMT -01:00) CAT' => false,
                )
            ),
        )

    ));
});