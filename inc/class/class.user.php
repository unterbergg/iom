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

    private $dateformat_list = [
        'MM/DD/YYYY',
        'DD/MM/YYYY',
        'YYYY/MM/DD'
    ];
    private $timezone_list = [
        '(GMT +00:00) GMT',
        '(GMT +00:00) UTC',
        '(GMT +01:00) ECT',
        '(GMT +02:00) EET',
        '(GMT +02:00) ART',
        '(GMT +03:00) EAT',
        '(GMT +03:30) MET',
        '(GMT +04:00) NET',
        '(GMT +05:00) PLT',
        '(GMT +05:30) IST',
        '(GMT +06:00) BST',
        '(GMT +07:00) VST',
        '(GMT +08:00) CTT',
        '(GMT +09:00) JST',
        '(GMT +09:30) ACT',
        '(GMT +10:00) AET',
        '(GMT +11:00) SST',
        '(GMT +12:00) NST',
        '(GMT -11:00) MIT',
        '(GMT -10:00) HST',
        '(GMT -09:00) AST',
        '(GMT -08:00) PST',
        '(GMT -07:00) PNT',
        '(GMT -07:00) MST',
        '(GMT -06:00) CST',
        '(GMT -05:00) EST',
        '(GMT -05:00) IET',
        '(GMT -04:00) PRT',
        '(GMT -03:30) CNT',
        '(GMT -03:00) AGT',
        '(GMT -03:00) BET',
        '(GMT -01:00) CAT',
    ];
    private $messenger_list = [
        'Facebook' => false,
        'Skype' => false,
        'WhatsApp' => false,
        'WeChat' => false,
        'Twitter' => false,
        'Telegram' => false,
        'Signal' => false
    ];

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
        $userdata = array_merge($userdata, $this->get_user_meta());
        return $userdata;
    }

    public function get_user_pass() {
        $userdata = $this->to_array();
        return ['user_pass' => $userdata['user_pass'], 'ID' => $this->ID];
    }

    private function get_user_meta( ) {
        $usermeta = get_user_meta( $this->ID );
        $usermeta['date_format'] = $this->get_formated_field($usermeta['date_format'][0], $this->dateformat_list);
        $usermeta['timezonez'] = $this->get_formated_field($usermeta['timezone'][0], $this->timezone_list);
        $usermeta['messenger'] = $this->get_multiple_field($usermeta['messenger'][0], $this->messenger_list);
        return $usermeta;
    }

    public function update_user_data( $userdata ) {
        $userdata['ID']  = $this->ID;
        $user_id = wp_update_user( $userdata );
        return healthos_get_user($user_id);
    }

    public function get_formated_field($usermeta, $field) {
        $result = [];
        foreach ($field as $value) {
            $result[$value] = $value == $usermeta;
        }
        return $result;
    }

    public function get_multiple_field($usermeta, $field) {
        $result = $field;
        $usermeta = json_decode($usermeta, true);
        foreach ($field as $key => $value) {
            if (array_key_exists( strtolower($key), $usermeta)) {
                $result[$key] = $usermeta[strtolower($key)];
            }
        }
        return $result;
    }
}