<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthModel extends CI_Model {
    function find_user($email, $active = false) {
        $this->db->where('email', $email);
        if($active != false) {
            $this->db->where('active', 1);
        }
        $this->db->limit(1);
        $query = $this->db->get('users');
        return $query->row();   
    }

    function register_email($register) {
        $data = [
            'name'          => htmlspecialchars($register['name']),
            'gender'        => null,
            'address'       => null,
            'email'         => htmlspecialchars($register['email']),
            'password'      => password_hash($register['password'], PASSWORD_DEFAULT),
            'image'         => 'blank-profile-picture.png',
            'active'        => 0,
            'created_at'    => date('Y-m-d'),
            'updated_at'    => date('Y-m-d'),
        ];

        $this->db->insert('users', $data);
    }

    function update_user_password($email, $password) {
        $data = ['password' => password_hash($password, PASSWORD_DEFAULT)];
        $this->db->where('email', $email);
        $this->db->update('users', $data);
    }

    function update_user_verification($email) {
        $data = ['active' => 1];

        $this->db->where('email', $email);
        $this->db->update('users', $data);
    }

    function update_user_detail($update) {
        $data = [
            'name'          => htmlspecialchars($update['name']),
            'gender'        => htmlspecialchars($update['gender']),
            'address'       => htmlspecialchars($update['address']),
            'updated_at'    => date('Y-m-d'),
        ];

        if ($update['image'] != null) {
			$data['image'] = $update['image'];
		}

        $this->db->where('email', $update['email']);
        $this->db->update('users', $data);
    }

    function delete_user($email) {
        $this->db->where('email', $email);
        $this->db->delete('users');
    }

    function register_token($email, $token, $type) {
        $data = [
            'email'			=> $email,
            'token'			=> $token,
            'type'			=> $type,
            'expire'		=> time(),
            'status'		=> 0,
            'created_at'	=> date('Y-m-d'),
            'updated_at'	=> date('Y-m-d')
        ];

        $this->db->insert('users_token', $data);
    }

    function get_token($token) {
        $this->db->where('token', $token);
        $this->db->limit(1);
        $query = $this->db->get('users_token');
        return $query->row();  
    }

    function update_token($email, $token) {
        $data = [
            'status'        => 1,
            'updated_at'    => date('Y-m-d')
        ];

        $this->db->where('email', $email);
        $this->db->where('token', $token);
        $this->db->update('users_token', $data);
    }

    function delete_token($email, $token) {
        $this->db->where('email', $email);
        $this->db->where('token', $token);
        $this->db->delete('users_token');
    }
}
