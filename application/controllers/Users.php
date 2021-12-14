<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('AuthModel');
		if(!$this->session->userdata('email'))
			redirect('');
	}

	public function index()
	{
		$data = [
			'user' => $this->AuthModel->find_user($this->session->userdata('email')),
		];

		$this->template->load('layouts/layouts-admin', 'user/dashboard-page', $data);
	}

	public function profile() 
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim', ['required' => 'Please fill out name field']);
		$this->form_validation->set_rules('gender', 'Gender', 'required|trim', ['required' => 'Please select gender']);
		$this->form_validation->set_rules('address', 'Address', 'required|trim', ['required' => 'Please fill out address field']);

		if($this->form_validation->run() == false) {
			$data = [
				'user' => $this->AuthModel->find_user($this->session->userdata('email')),
			];
			
			$this->template->load('layouts/layouts-admin', 'user/edit-profile-page', $data);
		} else {
			$update = $this->input->post(null, TRUE);

			// echo '<pre>';
			// print_r($update);
			// echo '</pre>';

			$config = [
				'upload_path' 	=> './uploads/image_profile',
				'allowed_types'	=> 'jpeg|jpg|png',
				'max_size'		=> 2048,
				'file_name'		=> 'profile-'.date('ymd').'-'.substr(md5(rand()), 0, 10)
			];
			$this->load->library('upload', $config);

			if(@$_FILES['image']['name'] != null) {
				if($this->upload->do_upload('image')) {
					$old_image = $this->AuthModel->find_user($this->session->userdata('email'));
					if($old_image->image != 'blank-profile-picture.png') {
						unlink(FCPATH.'/uploads/image_profile/'.$old_image->image);
					}

					$update['image'] = $this->upload->data('file_name');

					$this->AuthModel->update_user_detail($update);

					if($this->db->affected_rows() > 0) {
						$this->session->set_flashdata(
							'message',
							'<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<span>Update Profile Success</span>
							</div>'
						);
						redirect('dashboard');
					}
				} else {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata(
						'message',
						'<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<span>'.$error.'</span>
						</div>'
					);
					redirect('dashboard');
				}
			} else {
				$update['image'] = null;

				$this->AuthModel->update_user_detail($update);

				if($this->db->affected_rows() > 0) {
					$this->session->set_flashdata(
						'message',
						'<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<span>Update Profile Success</span>
						</div>'
					);	
					redirect('dashboard');
				}
			}
		}
	}
}
