<?php
define( 'ROUTE_NAMESPACE', apply_filters( 'healthos_route_namespace' , 'healthos/v2' ));

/**
 *
 * Get a user
 *
 * @param $user_id int the ID of the WP User
 * @return obj a HOS_User user object
 *
 *
 * @throws Exception
 */

function healthos_get_user( $user_id = false ) {
    return new HOS_User( $user_id );
}

/**
 *
 * Convert inches to cm
 *s
 * @param $height double the height in inches
 * @return array
 *
 *
 */

function healthos_get_measurement($inches) {

    $feet = floor(($inches/12));
    $inch = round($inches - ($feet*12), 0);

    return [$feet, $inch];

}


/**
 *
 * Convert kg/lbs to lbs/kg
 *
 * @param $units string the measurement's units
 * @param $value double the height in inches
 * @return string JSON
 *
 *
 */

function healthos_get_strength_units($units, $value) {
    $result = [];
    if ($units == 'imperial') {
        $result['imperial'] = floatval($value);
        $result['metric'] = $value / 2.20462;
    } else if ($units == 'metric') {
        $result['imperial'] = $value * 2.20462;
        $result['metric'] = floatval($value);
    }

    return $result;

}


/**
 *
 * Convert kg/lbs to lbs/kg
 *
 * @param $key string name of activity
 * @return bool
 *
 *
 */

function healthos_check_measurement_units( $key ) {
    $kg = [
        'Bench Press',
        'Squat',
        'Dead Lift',
        'OH Press',
        'Curl'
    ];
    return in_array($key, $kg);
}


//TODO: Add descriptions

add_action( 'rest_api_init', 'create_api_posts_meta_field' );

function create_api_posts_meta_field() {
    register_rest_field( 'active_workout', 'meta', array(
            'get_callback' => 'get_post_meta_for_api',
            'schema' => null,
        )
    );
}

function get_post_meta_for_api( $object ) {
    //get the id of the post object array
    $post_id = $object['id'];

    $metaval = get_post_meta( $post_id );

    foreach ($metaval as &$date) {
        $date = unserialize($date[0]);
    }
    //return the post meta
    return $metaval;
}

if (!function_exists('post_meta_rest_api_request')) {
    function post_meta_rest_api_request($argu, $request)
    {

        $argu += array(
            'meta_key' => $request['meta_key'],
            'meta_value' => $request['meta_value']
        );

        return $argu;
    }

    add_filter('rest_active_workout_query', 'post_meta_rest_api_request', 99, 2);
}
