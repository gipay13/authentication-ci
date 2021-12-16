<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('AuthModel');
		// $this->load->helper('cookie');
	}

	public function index()
	{
		if($this->session->userdata('email')) {
			redirect('dashboard','refresh');
		} else {
			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', 
												[
													'required' 		=> 'Please fill out email field.', 
													'valid_email' 	=> 'Email field must contain a valid email.',
												]);
			$this->form_validation->set_rules('password', 'Password', 'required|trim', 
												[
													'required' 		=> 'Please fill out password field'
												]);
			

			if($this->form_validation->run() == false) {

				$data = [
					'title' => 'Sign In',
				];
		
				$this->template->load('layouts/layouts-auth', 'auth/signin-page', $data);
			} else {
				$this->_login();
			}
		}
	}

	private function _login()
	{
		$login = $this->input->post(null, true);

		// print_r($login);

		$user = $this->AuthModel->find_user($login['email']);

		if ($user) {
			if($user->active == 1) {
				if (password_verify($login['password'], $user->password)) {
					$data = [
						'email' 	=> $user->email,
					];
					$this->session->set_userdata($data);
				
					$this->session->set_flashdata(
						'message',
						'<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<span class="text-sm text-white">Welcome, Start creating your amazing application!</span>
						</div>'
					);
					$this->session->sess_regenerate(FALSE);
					redirect('dashboard');
				} else {
					$this->session->set_flashdata(
						'message',
						'<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<span class="text-sm text-white">Invalid Email or Password</span>
						</div>'
					);
					redirect('');
				}
			} else {
				$this->session->set_flashdata(
					'message',
					'<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<span class="text-sm text-white">Please Verify Your Account</span>
					</div>'
				);
				redirect('');
			}
		} else {
			$this->session->set_flashdata(
				'message',
				'<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<span class="text-sm text-white">Invalid Email or Password</span>
				</div>'
			);
			redirect('');
		}
	}

    public function register()
	{
		if($this->session->userdata('email')) {
			redirect('dashboard','refresh');
		} else {
			$this->form_validation->set_rules('name', 'Name', 'required|trim', ['required' => 'Please fill out name field']);
			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', 
												[
													'required' 		=> 'Please fill out email field.', 
													'valid_email' 	=> 'Email field must contain a valid email.',
													'is_unique' 	=> 'Email already in use.'
												]);
			$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[8]|alpha_numeric_spaces', 
												[
													'required' 				=> 'Please fill out password field.',
													'min_length' 			=> 'Password must have 8 characters.',
													'alpha_numeric_spaces'	=> 'Password may only contain a-z, 1-9&0'
												]);
			$this->form_validation->set_rules('repassword', 'Password', 'required|trim|matches[password]', 
												[
													'required' 				=> 'Please fill out password field',
													'matches' 				=> 'Password does not match.'
												]);

			if($this->form_validation->run() == false) {

				$data = [
					'title' => 'Sign Up',
				];
		
				$this->template->load('layouts/layouts-auth', 'auth/signup-page', $data);
			} else {
				$register = $this->input->post(NULL, true);
				$token = generate_token(50);
				
				$this->db->trans_start();
				$this->AuthModel->register_email($register);
				$this->AuthModel->register_token($register['email'], $token, 'Verification');
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === FALSE) {
					$this->session->set_flashdata(
						'message',
						'<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<span class="text-sm text-white">Something Wrong, Please Try Again</span>
						</div>'
					);	
					redirect('');	
				} else {
					$token_url = [
						'verification'	=> base_url('').'verify?email='.$this->input->post('email').'&token='.$token
					];

					$this->_sendmail('verification', $token_url);
				
					$this->session->set_flashdata(
						'message',
						'<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<span class="text-sm text-white">Register Success Please Verify Your Account</span>
						</div>'
					);
					redirect('');
				}
			}
		}
		
	}

	public function verify() 
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->AuthModel->find_user($email);

		if($user) {
			$user_token = $this->AuthModel->get_token($token);

			if($user_token) {
				if($user_token->status == 0) {
					if(time() - $user_token->expire < (60*60)) {
						$this->db->trans_start();
						$this->AuthModel->update_user_verification($email);
						$this->AuthModel->update_token($email, $token);
						$this->db->trans_complete();
	
						if ($this->db->trans_status() === FALSE) {
							$this->session->set_flashdata(
								'message',
								'<div class="alert alert-warning alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<span class="text-sm text-white">Something Wrong, Please Try Verify Again</span>
								</div>'
							);
							redirect('');
						} else {
							$this->session->set_flashdata(
								'message',
								'<div class="alert alert-success alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<span class="text-sm text-white">Account Activated</span>
								</div>'
							);	
							redirect('');		
						}
					} else {
						$this->db->trans_start();
						$this->AuthModel->delete_user($email);
						$this->AuthModel->delete_token($email, $token);
						$this->db->trans_complete();
	
						if ($this->db->trans_status() === FALSE) {
							$this->session->set_flashdata(
								'message',
								'<div class="alert alert-warning alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<span class="text-sm text-white">Something Wrong, Please Try Verify Again</span>
								</div>'
							);
							redirect('');
						} else {
							if($this->db->affected_rows() > 0) {
								$this->session->set_flashdata(
									'message',
									'<div class="alert alert-danger alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<span class="text-sm text-white">Token Expired, Please Register Again</span>
									</div>'
								);	
								redirect('');
							}
						}
					}
				} else {
					$this->session->set_flashdata(
						'message',
						'<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<span class="text-sm text-white">Your Account Already Activated</span>
						</div>'
					);
					
					redirect('');
				}
			} else {
				$this->session->set_flashdata(
					'message',
					'<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<span class="text-sm text-white">Verification Failed</span>
					</div>'
				);
				
				redirect('');
			}
		} else {
			$this->session->set_flashdata(
				'message',
				'<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<span class="text-sm text-white">Verification Failed</span>
				</div>'
			);
			
			redirect('');
		}
	}

    public function passwordreset() 
	{
		if($this->session->userdata('email')) {
			redirect('dashboard','refresh');
		} else {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', 
											[
												'required' 		=> 'Please fill out email field.',
												'valid_email'	=> 'Email field must contain a valid email.'
											]
										);
		
		if($this->form_validation->run() == false) {
			$data = [
				'title' => 'Password Reset',
			];
	
			$this->template->load('layouts/layouts-auth', 'auth/passwordreset-page', $data);
		} else {
			$email = $this->input->post('email', true);

			$user =  $this->AuthModel->find_user($email, TRUE);

			if($user) {
				$token = generate_token(50);

				$this->AuthModel->register_token($email, $token, 'Reset');

				$token_url = [
					'reset' => base_url('').'passwordrecover?email='.$this->input->post('email').'&token='.$token,
				];

				$this->_sendmail('passwordreset', $token_url);

				$this->session->set_flashdata(
					'message',
					'<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<span class="text-sm text-white">Please Check Your Email</span>
					</div>'
				);
				redirect('passwordreset');	
			} else {
				$this->session->set_flashdata(
					'message',
					'<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<span class="text-sm text-white">Email not regitered or activated</span>
					</div>'
				);
				redirect('passwordreset');	
			}
		}
		}
    }

	public function passwordrecover() 
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->AuthModel->find_user($email);

		if($user) {
			$user_token = $this->AuthModel->get_token($token);

			if($user_token) {
				if($user_token->status == 0) {
					if(time() - $user_token->expire < (60*60)) {
						$this->session->set_userdata('password_reset', $email);
						$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[8]|alpha_numeric_spaces', 
													[
														'required' 				=> 'Please fill out password field.',
														'min_length' 			=> 'Password must have 8 characters.',
														'matches' 				=> 'Password does not match.',
														'alpha_numeric_spaces'	=> 'Password may only contain a-z, 1-9&0'
													]);
						$this->form_validation->set_rules('repassword', 'Confirm Password', 'required|trim|matches[password]', 
													[
														'required'				=> 'Please fill out confirm password field',
														'matches' 				=> 'Password does not match.'
													]);
	
						if($this->form_validation->run() == false) {
							$data = [
								'title' => 'Password Recover',
								'email'	=> $email,
								'token'	=> $token,
							];
					
							$this->template->load('layouts/layouts-auth', 'auth/passwordrecover-page', $data);
						} else {
							$email = $this->session->userdata('password_reset');
							$password = $this->input->post('password');

							if(password_verify($password, $user->password)) {
								$this->session->set_flashdata(
									'message',
									'<div class="alert alert-warning alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<span class="text-sm text-white">Please Use New Password, Dont Use Same Password as Before</span>
									</div>'
								);
								redirect('passwordrecover?email='.$email.'&token='.$token);	
							}
	
							$this->db->trans_start();
							$this->AuthModel->update_user_password($email, $password);
							$this->AuthModel->update_token($email, $token);
							$this->db->trans_complete();	

							if ($this->db->trans_status() === FALSE) {
								$this->session->set_flashdata(
									'message',
									'<div class="alert alert-danger alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<span class="text-sm text-white">Something Wrong, Please Try Again</span>
									</div>'
								);
								redirect('passwordrecover?email='.$email.'&token='.$token);	
							} else {
								$this->session->unset_userdata('password_reset');

								$this->session->set_flashdata(
									'message',
									'<div class="alert alert-success alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<span class="text-sm text-white">Password Reset Successful</span>
									</div>'
								);
								redirect('');
							}	
						}
					} else {
						$this->session->set_flashdata(
							'message',
							'<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<span class="text-sm text-white">Token Expired 1</span>
							</div>'
						);	
						redirect('passwordreset');
					}
				} else {
					$this->session->set_flashdata(
						'message',
						'<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<span class="text-sm text-white">Token Expired 2</span>
						</div>'
					);
					
					redirect('passwordreset');
				}
			} else {
				$this->session->set_flashdata(
					'message',
					'<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<span class="text-sm text-white">Password Reset Failed</span>
					</div>'
				);
				
				redirect('passwordreset');
			}
		} else {
			$this->session->set_flashdata(
				'message',
				'<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<span class="text-sm text-white">Password Reset Failed</span>
				</div>'
			);
			
			redirect('passwordreset');
		}
    }

	private function _sendmail($type, $token_url) 
	{
		// mailtrap
		// $config = [
		// 	'protocol' => 'smtp',
		// 	'smtp_host' => 'smtp.mailtrap.io',
		// 	'smtp_port' => 2525,
		// 	'smtp_user' => 'dbc6a89629661a',
		// 	'smtp_pass' => 'e9ba0a3c49f104',
		// 	'crlf' => "\r\n",
		// 	'newline' => "\r\n"
		// ];

		$config = [
			'protocol' 	=> 'smtp',
			'smtp_host'	=> 'ssl://smtp.googlemail.com',
			'smtp_user'	=> 'codeigniterauth@gmail.com',
			'smtp_pass'	=> 'Codeigniterauth13',
			'smtp_port'	=> 465,
			'mailtype'	=> 'html',
			'charset'	=> 'utf-8',
			'newline'	=> "\r\n",
		];
		
		$this->load->library('email', $config);

		// $this->email->initialize($config);

		$this->email->from('codeigniterauth@gmail.com', 'Codeigniter Auth');
		$this->email->to($this->input->post('email'));
		if($type == 'verification') {
			$this->email->subject('Account Verification');
			$this->email->message($this->load->view('layouts/layouts-verification-email', $token_url, TRUE));
		} else if($type == 'passwordreset') {
			$this->email->subject('Password Reset');
			$this->email->message($this->load->view('layouts/layouts-password-reset', $token_url, TRUE));
		}

		if($this->email->send()) {
			return true;
		} else {
			echo $this->email->print_debugger();
			die;
		}

	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('');
	}
}
