<?php
/**
 *
 * Class to handle session related user
 *
 **/
class HOS_Session {

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
            throw new Exception(__('You must provide a $user_id to initiate a HOS_Session object.', 'healthos'));
        }
        parent::__construct($user_id);

    }

}