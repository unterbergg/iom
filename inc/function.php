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

    $empty = [
        'Pull-ups',
        'Push-ups',
        'Sit-ups'
    ];

    return in_array($key, $kg);

}
