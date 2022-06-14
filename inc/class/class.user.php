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
        '(UTC-12:00) International Date Line West',
        '(UTC-11:00) Coordinated Universal Time-11',
        '(UTC-10:00) Hawaii',
        '(UTC-09:00) Alaska',
        '(UTC-08:00) Baja California',
        '(UTC-08:00) Pacific Time (US and Canada)',
        '(UTC-07:00) Chihuahua, La Paz, Mazatlan',
        '(UTC-07:00) Arizona',
        '(UTC-07:00) Mountain Time (US and Canada)',
        '(UTC-06:00) Central America',
        '(UTC-06:00) Central Time (US and Canada)',
        '(UTC-06:00) Saskatchewan',
        '(UTC-06:00) Guadalajara, Mexico City, Monterey',
        '(UTC-05:00) Bogota, Lima, Quito',
        '(UTC-05:00) Indiana (East)',
        '(UTC-05:00) Eastern Time (US and Canada)',
        '(UTC-04:30) Caracas',
        '(UTC-04:00) Atlantic Time (Canada)',
        '(UTC-04:00) Asuncion',
        '(UTC-04:00) Georgetown, La Paz, Manaus, San Juan',
        '(UTC-04:00) Cuiaba',
        '(UTC-04:00) Santiago',
        '(UTC-03:30) Newfoundland',
        '(UTC-03:00) Brasilia',
        '(UTC-03:00) Greenland',
        '(UTC-03:00) Cayenne, Fortaleza',
        '(UTC-03:00) Buenos Aires',
        '(UTC-03:00) Montevideo',
        '(UTC-02:00) Coordinated Universal Time-2',
        '(UTC-01:00) Cape Verde',
        '(UTC-01:00) Azores',
        '(UTC+00:00) Casablanca',
        '(UTC+00:00) Monrovia, Reykjavik',
        '(UTC+00:00) Dublin, Edinburgh, Lisbon, London',
        '(UTC+00:00) Coordinated Universal Time',
        '(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
        '(UTC+01:00) Brussels, Copenhagen, Madrid, Paris',
        '(UTC+01:00) West Central Africa',
        '(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague',
        '(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb',
        '(UTC+01:00) Windhoek',
        '(UTC+02:00) Athens, Bucharest, Istanbul',
        '(UTC+02:00) Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius',
        '(UTC+02:00) Cairo',
        '(UTC+02:00) Damascus',
        '(UTC+02:00) Amman',
        '(UTC+02:00) Harare, Pretoria',
        '(UTC+02:00) Jerusalem',
        '(UTC+02:00) Beirut',
        '(UTC+03:00) Baghdad',
        '(UTC+03:00) Minsk',
        '(UTC+03:00) Kuwait, Riyadh',
        '(UTC+03:00) Nairobi',
        '(UTC+03:30) Tehran',
        '(UTC+04:00) Moscow, St. Petersburg, Volgograd',
        '(UTC+04:00) Tbilisi',
        '(UTC+04:00) Yerevan',
        '(UTC+04:00) Abu Dhabi, Muscat',
        '(UTC+04:00) Baku',
        '(UTC+04:00) Port Louis',
        '(UTC+04:30) Kabul',
        '(UTC+05:00) Tashkent',
        '(UTC+05:00) Islamabad, Karachi',
        '(UTC+05:30) Sri Jayewardenepura Kotte',
        '(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi',
        '(UTC+05:45) Kathmandu',
        '(UTC+06:00) Astana',
        '(UTC+06:00) Dhaka',
        '(UTC+06:00) Yekaterinburg',
        '(UTC+06:30) Yangon',
        '(UTC+07:00) Bangkok, Hanoi, Jakarta',
        '(UTC+07:00) Novosibirsk',
        '(UTC+08:00) Krasnoyarsk',
        '(UTC+08:00) Ulaanbaatar',
        '(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
        '(UTC+08:00) Perth',
        '(UTC+08:00) Kuala Lumpur, Singapore',
        '(UTC+08:00) Taipei',
        '(UTC+09:00) Irkutsk',
        '(UTC+09:00) Seoul',
        '(UTC+09:00) Osaka, Sapporo, Tokyo',
        '(UTC+09:30) Darwin',
        '(UTC+09:30) Adelaide',
        '(UTC+10:00) Hobart',
        '(UTC+10:00) Yakutsk',
        '(UTC+10:00) Brisbane',
        '(UTC+10:00) Guam, Port Moresby',
        '(UTC+10:00) Canberra, Melbourne, Sydney',
        '(UTC+11:00) Vladivostok',
        '(UTC+11:00) Solomon Islands, New Caledonia',
        '(UTC+12:00) Coordinated Universal Time+12',
        '(UTC+12:00) Fiji, Marshall Islands',
        '(UTC+12:00) Magadan',
        '(UTC+12:00) Auckland, Wellington',
        '(UTC+13:00) Nuku\'alofa',
        '(UTC+13:00) Samoa'
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
        'disabled' => false,
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
    private $bests_list = [
        'units' => 'imperial',
        'run' => [
            '100M' => null,
            '200M' => null,
            '400M' => null,
            '800M' => null,
            '5K' => null,
            '10K' => null,
            '1 Mile' => null,
            '5 Mile' => null,
            '1/2 Marathon' => null,
            'Marathon' => null
        ],
        'bike' => [
            '5mi' => null,
            '10mi' => null,
            '25mi' => null,
            '50mi' => null,
            '100mi' => null,
            '5k' => null,
            '10k' => null,
            '25k' => null,
            '50k' => null,
            '100k' => null,
        ],
        'swim' => [
            '25m' => null,
            '50m' => null,
            '100m' => null,
            '200m' => null,
            '400m' => null,
            '800m' => null,
        ],
        'strength' => [
            //kg
            'Bench Press' => null,
            'Squat' => null,
            'Dead Lift' => null,
            'OH Press' => null,
            'Curl' => null,
            //empty
            'Pull-ups' => null,
            'Push-ups' => null,
            'Sit-ups' => null,
        ]
    ];
    private $equipment_list = [
        'home' => [
            'Band' => false,
            'Suspension Trainer' => false,
            'BOSU' => false,
            'Barbell' => false,
            'Bicycle' => false,
            'Box/Step' => false,
            'Cable' => false,
            'Dumbbell' => false,
            'Free Weights' => false,
            'Kettlebell' => false,
            'Medicine Ball' => false,
            'Stability Ball' => false,
            'Stationary Bike' => false,
            'ViPR® PRO' => false
        ],
        'gym' => [
            'Band' => false,
            'Suspension Trainer' => false,
            'BOSU' => false,
            'Barbell' => false,
            'Bicycle' => false,
            'Box/Step' => false,
            'Cable' => false,
            'Dumbbell' => false,
            'Free Weights' => false,
            'Kettlebell' => false,
            'Medicine Ball' => false,
            'Stability Ball' => false,
            'Stationary Bike' => false,
            'ViPR® PRO' => false
        ],
        'other' => [
            'Band' => false,
            'Suspension Trainer' => false,
            'BOSU' => false,
            'Barbell' => false,
            'Bicycle' => false,
            'Box/Step' => false,
            'Cable' => false,
            'Dumbbell' => false,
            'Free Weights' => false,
            'Kettlebell' => false,
            'Medicine Ball' => false,
            'Stability Ball' => false,
            'Stationary Bike' => false,
            'ViPR® PRO' => false
        ],
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

    // TODO: Review. Error if data not exist
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

        $usermeta['date_format'] = $this->get_formatted_field($usermeta['date_format'][0] ?? "", $this->dateformat_list);
        $usermeta['gender'] = $this->get_formatted_field($usermeta['gender'][0] ?? "", $this->gender_list);
        $usermeta['timezone'] = $this->get_formatted_field($usermeta['timezone'][0] ?? "", $this->timezone_list);
        $usermeta['messenger'] = $this->get_multiple_field($usermeta['messenger'][0] ?? "", $this->messenger_list);


        $usermeta['notifications'] = $this->get_notifications($usermeta['notifications'][0] ?? "", json_decode($usermeta['notification_switcher'][0]) ?? false);
        
        if (isset($usermeta['units']) && (isset($usermeta['weight']) || isset($usermeta['height']))) {
            $usermeta['vitals'] = $this->get_vitals($usermeta['units'][0], $usermeta['weight'][0] ?? "", $usermeta['height'][0] ?? "");
            unset($usermeta['weight']);
            unset($usermeta['height']);
        }
        $usermeta['bests'] = $this->get_bests($usermeta['units'][0] ?? "", $usermeta['bests'][0] ?? "");
        $usermeta['equipment'] = $this->get_equipment($usermeta['equipment'][0] ?? "");
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

    public function update_user_data( $userdata ) {
        $userdata['ID']  = $this->ID;
        $user_id = wp_update_user( $userdata );
        return healthos_get_user($user_id);
    }

    /**
     *
     * Get formatted value of user's meta field
     *
     * @param $usermeta string value of the field
     * @param $field array key of the field
     * @return array $result
     *
     **/

    // TODO: Review access modifiers. Add function's return type.
    private function get_formatted_field(string $usermeta, array $field): array
    {
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
     * @param $usermeta string value of the field
     * @param $field array list of available values
     * @return array $result
     *
     **/

    private function get_multiple_field(string $usermeta, array $field): array
    {
        $result = $field;
        $usermeta = unserialize($usermeta);
        foreach ($field as $key => $value) {
            if (array_key_exists( $key, $usermeta)) {
                $result[$key] = $usermeta[$key];
            }
        }
        return $result;
    }

    /**
     *
     * Get user's notifications
     *
     * @param $usermeta string value of the notification field
     * @param $flag bool turn on/turn off notifications
     * @return array $result
     *
     **/

    private function get_notifications(string $usermeta, bool $flag): array
    {
        $result = $this->notification_list;
        $result['disabled'] = $flag;
        foreach ($result as $key => $value) {
            if (array_key_exists( $key, unserialize($usermeta))) {
                foreach (unserialize($usermeta)[$key] as $item) {
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
     * @param $weight string|null JSON weight in both units of measurement
     * @param $height string JSON height in both units of measurement
     * @return array $result
     *
     **/

    private function get_vitals(string $units, string $weight = "", string $height = ""): array
    {
        $result = $this->vitals_list;
        $result['units'] = $units;
        if ($weight) {
            $result['weight'] = unserialize($weight);
        }
        if ($height) {
            $result['height'] = unserialize($height);
            $result['height']['imperial'] = healthos_get_measurement($result['height']['imperial']);
        }
        return $result;
    }

    /**
     *
     * Get user's bests
     *
     * @param $units string units of measurement
     * @param $bests string|null JSON bests from db
     * @return array $result
     *
     **/

    private function get_bests(string $units, string $bests = ""): array
    {
        $result = $this->bests_list;
        $result['units'] = $units;
        $bests = unserialize($bests);
        foreach ($bests as $group => $items) {
            foreach ($items as $key => $item) {
                $result[strtolower($group)][$key] = $item;
            }
        }
        return $result;
    }

    /**
     *
     * Get user's equipments
     *
     * @param $eq string|null JSON equipment from db
     * @return array $result
     *
     **/

    private function get_equipment(string $eq = ""): array
    {
        $result = $this->equipment_list;
        $equipment = unserialize($eq);
        foreach ($equipment as $group => $items) {
            foreach ($items as $item) {
                $result[$group][$item] = true;
            }
        }
        return $result;
    }
}