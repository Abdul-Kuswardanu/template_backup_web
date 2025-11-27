<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');

        if ($this->ion_auth->logged_in() == FALSE) {
            redirect('auth/login');
        }
    }
    public function index()
    {
        $users = $this->ion_auth->users()->result();

        $data['title'] = 'Daftar Pengguna';
        $data['result'] = $users;

        $this->load->view('inc/header');
        $this->load->view('inc/sidebar');
        $this->load->view("users/users", $data);
        $this->load->view('inc/footer');
    }

    public function users_tambah()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('level_users', 'Level Pengguna', 'required');

        if ($this->form_validation->run() === TRUE) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $email = $this->input->post('email');

            $additional_data = array(
                'username' => $this->input->post('username'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'level_users' => $this->input->post('level_users'),
                'ip_address' => '127.0.0.1',
                'active' => $this->input->post('active') ? 1 : 0
            );

            $group = array($this->input->post('level_users'));

            $user_id = $this->ion_auth->create_user($username, $password, $email, $additional_data, $group);

            if ($user_id == TRUE) {
                $this->session->set_flashdata('action_status', '<div class="alert alert-success">User Berhasil ditambahkan</div>');
                redirect('users');
            }
        } else {
            $this->load->view('inc/header');
            $this->load->view('inc/sidebar');
            $this->load->view("users/users_tambah");
            $this->load->view('inc/footer');
        }
    }

    public function users_edit($id)
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('level_users', 'Level Pengguna', 'required');
        $this->form_validation->set_rules('active', 'Status Aktif', 'required');

        if ($this->form_validation->run() === FALSE) {
            $query = $this->db->get_where('users', ['id' => $id]);
            if ($query->num_rows() == 1) {
                $data['user'] = $query->row();
            } else {
                $this->session->set_flashdata('action_status', '<div class="alert alert-warning">User Tidak ada</div>');
                redirect('users');
            }

            $this->load->view('inc/header');
            $this->load->view('inc/sidebar');
            $this->load->view("users/users_edit", $data);
            $this->load->view('inc/footer');
        } else {
            $config['upload_path']   = 'uploads/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']      = 2000000;

            $this->load->library('upload', $config);

            $foto = null;

            // Proses upload file
            if (!$this->upload->do_upload('foto')) {
                $this->session->set_flashdata('action_status', '<div class="alert alert-warning">' . $this->upload->display_errors() . '</div>');
            } else {
                $data_upload = $this->upload->data();
                $foto = $data_upload['file_name'];
            }

            $additional_data = array(
                'username' => $this->input->post('username'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'level_users' => $this->input->post('level_users'),
                'active' => $this->input->post('active'),
            );

            if ($foto !== null) {
                $additional_data['foto'] = $foto;
            }

            $update_data = $this->ion_auth->update_user($id, $additional_data);

            if ($update_data) {
                $this->session->set_flashdata('action_status', '<div class="alert alert-success">User Berhasil diupdate</div>');
                redirect('users');
            } else {
                $this->session->set_flashdata('action_status', '<div class="alert alert-danger">Gagal mengupdate user</div>');
                redirect('users');
            }
        }
    }


    public function users_hapus($id)
    {
        if ($this->ion_auth->delete_user($id)) {
            $this->session->set_flashdata('action_status', '<div class="alert alert-success">User Berhasil dihapus</div>');
        } else {
            $this->session->set_flashdata('action_status', '<div class="alert alert-danger">User Gagal dihapus</div>');
        }
        redirect('users');
    }
}
