<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example_controller extends CI_Controller {

	/**
      * Login controller function
      *
      * In this example, we handle a login form submission
      *
      * @author Stefan Ashwell <codebastard.co.uk>
      */
	public function login() {

		// Is a user already logged in?
		$users_data = $this->users_model->session_check($this->input->cookie('user_login'));

		if ( $users_data['id'] ) {
			redirect('members');
		}

		// Login form submission
		if ( $this->input->post('frm_submit') ) {

			$users_id = $this->users_model->login($this->input->post('email'), $this->input->post('password'));

			if ( $users_id ) {

				redirect('dashboard');

			} else {

				$data['error_message'] = 'Login failed, please check your username and password are correct and try again';

			}

		}

		// Load view
		$this->load->view('login', $data);

	}

	/**
      * Members only page controller function
      *
      * In this example, we show how we can check if a user is logged in, and if
	  * not redirect back to the login page
      *
      * @author Stefan Ashwell <codebastard.co.uk>
      */
	public function members_only_page() {

		// Is a user already logged in?
		$users_data = $this->users_model->session_check($this->input->cookie('user_login'));

		if ( !$users_data['id'] ) {
			redirect('login');
		}

		// User is logged in and valid - continue with members only content

		// Load view
		$this->load->view('members_only_page', $data);

	}

	/**
      * Logout controller function
      *
      * In this example, we log a user out and redirect back to the login page
      *
      * @author Stefan Ashwell <codebastard.co.uk>
      */
	public function logout() {

		// Is a user already logged in?
		$users_data = $this->users_model->session_check($this->input->cookie('user_login');

		if ( $users_data['id'] ) {
			$this->users_model->destroy_session($users_data['id']);
		}

		redirect('login');

	}

}
