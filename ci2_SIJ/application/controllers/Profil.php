<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('ion_auth');

        if ($this->ion_auth->logged_in() == FALSE) {
            redirect('auth/login');
        }

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data['user'] = $user;

        $this->load->view('inc/header');
        $this->load->view('inc/sidebar');
        $this->load->view("update_profil/profil", $data);
        $this->load->view('inc/footer');
    }

    public function update_profil()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->ion_auth->user($user_id)->row();

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['user'] = $user;

            $this->load->view('inc/header');
            $this->load->view('inc/sidebar');
            $this->load->view("update_profil/update_profil", $data);
            $this->load->view('inc/footer');
        } else {
            $user_id = $this->input->post('user_id');
            $username = $this->input->post('username');
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $email = $this->input->post('email');
            $phone = $this->input->post('phone');

            $data = array(
                'username' => $username,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone
            );

            if ($this->ion_auth->update($user_id, $data)) {
                $this->session->set_flashdata('success', 'Profil berhasil diperbarui');
                redirect('profil');
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('profil/update_profil');
            }
        }
    }

    public function ganti_password()
    {

        $user_id = $this->session->userdata('user_id');
        $user = $this->ion_auth->user($user_id)->row();

        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('inc/header');
            $this->load->view('inc/sidebar');
            $this->load->view("ganti_password/ganti_password");
            $this->load->view('inc/footer');
        } else {
            $new_password = $this->input->post('password');

            if ($this->ion_auth->update($user->id, array('password' => $new_password))) {
                $this->ion_auth->logout();

                $this->session->set_flashdata('success', 'Password berhasil diperbarui. Silakan login kembali.');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'Password gagal diperbarui');
                redirect('profil/ganti_password');
            }
        }
    }
}
