<?php


/**
 *
 * Class to handle user related actions
 *
 **/

class HOS_User extends WP_User
{

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
    private $notification_list = [
        'turn_on' => true,
        'new_message' => [
            'label' => 'New Message',
            'notification' => [
                'in-app' => false,
                'e-mail' => false
            ]
        ],
        'new_comment' => [
            'label' => 'New Comment/Reply on Session',
            'notification' => [
                'in-app' => false,
                'e-mail' => false
            ]
        ],
        'new_program' => [
            'label' => 'New Program',
            'notification' => [
                'in-app' => false,
                'e-mail' => false
            ]
        ],
        'program_updates' => [
            'label' => 'Program Updates',
            'notification' => [
                'in-app' => false,
                'e-mail' => false
            ]
        ],
        'new_session' => [
            'label' => 'New Session',
            'notification' => [
                'in-app' => false,
                'e-mail' => false
            ]
        ],
        'session_updates' => [
            'label' => 'Session Updates',
            'notification' => [
                'in-app' => false,
                'e-mail' => false
            ]
        ],
        'new_activity' => [
            'label' => 'New Activity',
            'notification' => [
                'in-app' => false,
                'e-mail' => false
            ]
        ],
        'activity_updates' => [
            'label' => 'Activity Updates',
            'notification' => [
                'in-app' => false,
                'e-mail' => false
            ]
        ],
        'Goal_updates' => [
            'label' => 'Goal Updates',
            'notification' => [
                'in-app' => false,
                'e-mail' => false
            ]
        ],
        'application_updates' => [
            'label' => 'Application Updates',
            'notification' => [
                'in-app' => false,
                'e-mail' => false
            ]
        ],
    ];
    private $gender_list = [
        'Male',
        'Female',
        'Prefer not to Say',
        'Other'
    ];
    private $vitals_list = [
        'units' => 'imperial',
        'weight' => [
            'imperial' => null,
            'metric' => null
        ],
        'height' => [
            'imperial' => null,
            'metric' => null
        ]
    ];

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

    /**
     *
     * Get user's email
     *
     * @return string $user_email
     *
     **/

    private function get_email_address() {
        return $this->user_email;
    }

    /**
     *
     * Get user's data
     *
     * @return array $userdata
     *
     **/

    public function get_user_data() {
        $userdata = $this->to_array();
        unset( $userdata['user_pass']);
        unset( $userdata['user_activation_key']);
        $userdata = array_merge($userdata, $this->get_user_meta());
        return $userdata;
    }

    /**
     *
     * Get user's password
     *
     * @return array user_pass & ID
     *
     **/

    public function get_user_pass() {
        $userdata = $this->to_array();
        return ['user_pass' => $userdata['user_pass'], 'ID' => $this->ID];
    }

    /**
     *
     * Get user's meta
     *
     * @return array $usermeta
     *
     **/

    private function get_user_meta() {
        $usermeta = get_user_meta( $this->ID );

        unset( $usermeta['session_tokens']);
        unset( $usermeta['rich_editing']);
        unset( $usermeta['syntax_highlighting']);
        unset( $usermeta['comment_shortcuts']);
        unset( $usermeta['admin_color']);
        unset( $usermeta['use_ssl']);
        unset( $usermeta['show_admin_bar_front']);
        unset( $usermeta['locale']);
        unset( $usermeta['wp_user_level']);
        unset( $usermeta['default_password_nag']);

        $usermeta['date_format'] = $this->get_formatted_field($usermeta['date_format'][0], $this->dateformat_list);
        $usermeta['gender'] = $this->get_formatted_field($usermeta['gender'][0], $this->gender_list);
        $usermeta['timezonez'] = $this->get_formatted_field($usermeta['timezone'][0], $this->timezone_list);
        $usermeta['messenger'] = $this->get_multiple_field($usermeta['messenger'][0], $this->messenger_list);

        if ($usermeta['notification_switcher']) {
            $usermeta['notifications'] = $this->get_notifications($usermeta['notifications'][0]);
        } else {
            $usermeta['notifications'] = $this->get_notifications($usermeta['notifications'][0], false);
        }

        $usermeta['vitals'] = $this->get_vitals( $usermeta['units'][0], $usermeta['weight'][0], $usermeta['height'][0]);
        unset($usermeta['weight']);
        unset($usermeta['height']);

        return $usermeta;
    }

    /**
     *
     * Update user's data
     *
     * @return obj a HOS_User user object
     *
     *
     * @throws Exception
     **/

    // TODO: Review access modifiers
    public function update_user_data( $userdata ) {
        $userdata['ID']  = $this->ID;
        $user_id = wp_update_user( $userdata );
        return healthos_get_user($user_id);
    }

    /**
     *
     * Get formatted value of user's meta field
     *
     * @param $usermeta array value of the field
     * @param $field array key of the field
     * @return array $result
     *
     **/

    // TODO: Review access modifiers. Add function's return type.
    public function get_formatted_field($usermeta, $field) {
        $result = [];
        foreach ($field as $value) {
            $result[$value] = $value == $usermeta;
        }
        return $result;
    }

    /**
     *
     * Get multiple values of user's meta field
     *
     * @param $usermeta array value of the field
     * @param $field array key of the field
     * @return array $result
     *
     **/

    // TODO: Review access modifiers. Add function's return type.
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

    /**
     *
     * Get user's notifications
     *
     * @param $usermeta array value of the notification field
     * @param $flag bool turn on/turn off notifications
     * @return array $result
     *
     **/

    // TODO: Review access modifiers. Add function's return type.
    public function get_notifications($usermeta, $flag = true) {
        $result = $this->notification_list;
        if (!$flag) {
            $result['turn_on'] = false;
            return $result;
        }
        $usermeta = json_decode($usermeta, true);
        foreach ($result as $key => $value) {
            if (array_key_exists( $key, $usermeta)) {
                foreach ($usermeta[$key] as $item) {
                    if (isset($result[$key]['notification'][$item])) {
                        $result[$key]['notification'][$item] = true;
                    }
                }
            }
        }
        return $result;
    }

    /**
     *
     * Get user's vitals
     *
     * @param $units string units of measurement
     * @param $weight string JSON weight in both units of measurement
     * @param $height string JSON height in both units of measurement
     * @return array $result
     *
     **/

    // TODO: Review access modifiers. Add function's return type.
    public function get_vitals($units, $weight = null, $height= null) {
        $result = $this->vitals_list;
        $result['units'] = $units;
        if ($weight) {
            $result['weight'] = json_decode($weight, true);
        }
        if ($height) {
            $result['height'] = json_decode($height, true);
            $result['height']['imperial'] = getMeasurement($result['height']['imperial']);
        }
        return $result;
    }
}