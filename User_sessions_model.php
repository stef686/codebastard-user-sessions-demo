<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_sessions_model extends CI_Model {

    /**
      * Login function
      *
      * This function checks for a valid user. If one is found it logs them in
      *
      * @author Stefan Ashwell <codebastard.co.uk>
      *
      * @param string   $username   Username entered
      * @param string   $password   Password entered
      */
    public function login($username, $password) {



    }

    /**
      * Create session function
      *
      * This function creates a new user session in the database
      *
      * @author Stefan Ashwell <codebastard.co.uk>
      *
      * @param array   $data   Session data to insert into the database
      */
    public function create_session($data) {



    }

    /**
      * Session check function
      *
      * This function checks for a valid user session in the database
      *
      * @author Stefan Ashwell <codebastard.co.uk>
      *
      * @param string   $hash   The session hash to look for
      */
    public function session_check($hash) {



    }

    /**
      * Destroy session function
      *
      * This function destroys a session and the cookie associated with it
      *
      * @author Stefan Ashwell <codebastard.co.uk>
      *
      * @param int   $session_id   The session id to destroy
      */
    public function destroy_session($session_id) {



    }

    /**
      * Track uri function
      *
      * This function inserts data of a uri view in the database
      *
      * @author Stefan Ashwell <codebastard.co.uk>
      *
      * @param array   $data   The data to insert into the database
      */
    public function track_uri($data) {



    }


}
