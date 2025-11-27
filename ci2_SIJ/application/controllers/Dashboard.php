<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Ci_Controller
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
        $this->load->view('inc/header');
        $this->load->view('inc/sidebar');
        $this->load->view("dashboard");
        $this->load->view('inc/footer');
    }
}
