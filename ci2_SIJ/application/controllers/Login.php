<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Login extends CI_Controller
{
    public function index()
    {
        $this->load->view("auth_kevin/login");
    }

    public function register()
    {
        $this->load->view("auth_kevin/register");
    }
}
