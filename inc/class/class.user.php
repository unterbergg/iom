<?php


/**
 *
 * Class to handle user related actions
 *
 **/

class HOS_User extends WP_User
{
    /**
     *
     * The class constructor
     *
     * @param $user_id int the ID of the WP User
     * @return $this
     *
     **/

    public function __construct($user_id = false)
    {

        if ( !$user_id || !is_numeric($user_id) ) {
            throw new Exception(__('You must provide a $user_id to initiate a HOS_User object.', 'healthos'));
        }
        parent::__construct($user_id);

    }

    /**
     *
     * Set a new password
     *
     * @param $password str the new password
     * @return bool true on success, false on failure
     *
     **/

    public function set_new_password( $password ) {

        $password = trim( wp_unslash( $password ) );

        return wp_set_password( $password , $this->ID );

    }

    private function get_email_address() {
        return $this->user_email;
    }

    public function get_user_data() {
        $userdata = $this->to_array();
        unset( $userdata['user_pass']);
        unset( $userdata['user_activation_key']);
        $userdata = array_merge($userdata, $this->get_user_meta_callback());
        return $userdata;
    }

    public function get_user_pass() {
        $userdata = $this->to_array();
        return ['user_pass' => $userdata['user_pass'], 'ID' => $this->ID];
    }

    private function get_user_meta_callback( ) {
        return get_user_meta( $this->ID );
    }

    public function update_user_data() {
        $userdata = $this->to_array();
        unset( $userdata['user_pass']);
        unset( $userdata['user_activation_key']);
        $userdata = array_merge($userdata, $this->get_user_meta_callback());
        return $userdata;
    }
}