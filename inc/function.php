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
 * Get a user meta
 *
 * @param $user_id int the ID of the WP User
 * @param $field_name str slug of the WP User meta field
 * @return array a WP_User user meta
 *
 *
 * @throws Exception
 */

//function get_user_meta_callback( $user, $field_name) {
//    return get_user_meta( $user[ 'id' ], $field_name, true );
//}

//function update_user_meta_callback( $value, $user, $field_name) {
//    if ( $value === 0 || $value === 'false' || $value === false ) {
//        return delete_user_meta( $user->ID, $field_name );
//    }
//    return update_user_meta( $user->ID, $field_name, $value );
//}