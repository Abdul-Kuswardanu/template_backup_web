<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	public function login()
	{
		// Jika sudah login, redirect ke home
		if ($this->session->userdata('user_id')) {
			redirect('home');
		}

		$data['title'] = 'Login - Data Backup';
		$this->load->view('auth/login', $data);
	}

	public function process_login()
	{
		// TODO: Akan dihubungkan dengan database nanti
		// Untuk sementara, hanya tampilkan pesan
		$user_id = $this->input->post('user_id');
		$user_passwd = $this->input->post('user_passwd');

		// Validasi sederhana
		if (empty($user_id) || empty($user_passwd)) {
			$this->session->set_flashdata('error', 'User ID dan Password harus diisi');
			redirect('auth/login');
		}

		// TODO: Validasi dengan database akan ditambahkan nanti
		// Untuk sementara, set session dummy
		$this->session->set_userdata('user_id', $user_id);
		redirect('home');
	}

	public function logout()
	{
		$this->session->unset_userdata('user_id');
		$this->session->sess_destroy();
		redirect('auth/login');
	}

	public function change_password()
	{
		// Cek apakah sudah login
		if (!$this->session->userdata('user_id')) {
			redirect('auth/login');
		}

		$data['title'] = 'Ubah Password - Data Backup';
		$data['user_id'] = $this->session->userdata('user_id');
		$this->load->view('auth/change_password', $data);
	}

	public function process_change_password()
	{
		// Cek apakah sudah login
		if (!$this->session->userdata('user_id')) {
			redirect('auth/login');
		}

		$current_password = $this->input->post('current_password');
		$new_password = $this->input->post('new_password');
		$confirm_password = $this->input->post('confirm_password');

		// Validasi
		if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
			$this->session->set_flashdata('error', 'Semua field harus diisi');
			redirect('auth/change_password');
		}

		// Validasi panjang password
		if (strlen($new_password) < 8 || strlen($new_password) > 16) {
			$this->session->set_flashdata('error', 'Password harus antara 8-16 karakter');
			redirect('auth/change_password');
		}

		// Validasi password baru dan konfirmasi
		if ($new_password !== $confirm_password) {
			$this->session->set_flashdata('error', 'Password baru dan konfirmasi password tidak cocok');
			redirect('auth/change_password');
		}

		// TODO: Validasi dengan database akan ditambahkan nanti
		$this->session->set_flashdata('success', 'Password berhasil diubah');
		redirect('home');
	}

	public function reset_password()
	{
		$data['title'] = 'Reset Password - Data Backup';
		$this->load->view('auth/reset_password', $data);
	}

	public function process_reset_password()
	{
		$user_id = $this->input->post('user_id');

		if (empty($user_id)) {
			$this->session->set_flashdata('error', 'User ID harus diisi');
			redirect('auth/reset_password');
		}

		// TODO: Proses reset password dengan database akan ditambahkan nanti
		$this->session->set_flashdata('success', 'Password baru telah dikirim ke email Anda');
		redirect('auth/login');
	}
}

