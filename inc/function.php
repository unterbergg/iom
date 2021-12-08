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

function getMeasurement($inches) {

    $feet = floor(($inches/12));
    $inch = round($inches - ($feet*12), 0);

    return [$feet, $inch];

}
