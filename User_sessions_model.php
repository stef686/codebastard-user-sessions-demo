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

		// Is there a valid user?
		$this->db->select('id');
		$this->db->where('email', $username);
		$this->db->where('password', sha1($password));
		$query	= $this->db->get('users');
	    $user   = $query->row_array();

		if ( $user['id'] ) {

			// Is there an open session for this user?
			$this->db->select('id');
			$this->db->where('users_id', $user['id']);
			$this->db->where('inactive !=', 1);
			$query		= $this->db->get('user_sessions');
		    $session   	= $query->row_array();

			if ( $session['id'] ) {

				// Close the session
				$this->destroy_session($session['id']);

			}

			// Create the new session
			$newsession['users_id']	= $user['id'];
			$newsession['ip']		= $_SERVER['REMOTE_ADDR'];
			$this->create_session($newsession);

			return true;

		} else {

			return false;

		}

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

		// Set additional data
		$data['created_date']	= date('Y-m-d H:i:s');
		$data['last_active']	= date('Y-m-d H:i:s');
		$data['inactive']		= 0;
		$data['hash']			= sha1($data['users_id'].time());

		// Perform the insert
		$this->db->insert('user_sessions', $data);

		// Create cookie
		set_cookie('user_login', $data['hash'], 0, 'your-domain');

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

		// Is there a session for this hash?
		$this->db->where('hash', $hash);
		$this->db->where('inactive !=', 1);
		$query		= $this->db->get('user_sessions');
	    $session   	= $query->row_array();

		if ( $session['id'] ) {

			// Check the session isn't more than 30 days old and the ips match
			if( ( strtotime($session['last_active']) < strtotime('-30 days') ) || $session['ip'] != $_SERVER['REMOTE_ADDR'] ) {

				$this->destroy_session($session['id']);
				return false;

			} else {

				// Update the last active date to now
				$this->db->set('last_active', date('Y-m-d H:i:s'));
				$this->db->where('id', $session['id']);
				$this->db->update('user_sessions');

				// Track the URI
				$trackdata['sessions_id']	= $session['id'];
				$trackdata['uri']			= $this->uri->uri_string();
				$this->track_uri($trackdata);

				// Return the session
				return $session;

			}

		} else {

			return false;

		}

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

		// Mark as inactive in database
		$this->db->set('inactive', 1);
		$this->db->where('id', $session['id']);
		$this->db->update('user_sessions');

		// Destroy the cookie
		delete_cookie('user_login', 'your-domain');

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

		// Set additional data
		$data['last_visited']	= date('Y-m-d H:i:s');

		// Perform the insert
		$this->db->insert('sessions_track', $data);

	}


}
