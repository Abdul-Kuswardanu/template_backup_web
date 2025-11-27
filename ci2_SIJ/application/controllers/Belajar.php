<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Belajar extends CI_Controller
{
    public function __construct()
    {
        parrent::__construct();
        $this->load->helper("url");
        $this->load->library('ion_auth');

        if ($this->ion_auth->logged_in() == FALSE) {
            redirect('auth/login');
        }
    }
    public function index()
    {

        //belajar memanggil dan menggunakan helper
        // memanggil base_url yang ada diconfig.php +/index.php
        echo site_url();
        echo "<br>";
        echo base_url();
    }
}
