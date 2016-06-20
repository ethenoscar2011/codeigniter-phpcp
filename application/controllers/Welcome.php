<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('func');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function test(){

		$this->benchmark->mark('noasyn');
		$this->db->query('select * from sr_gravida');
		$this->db->query('select * from sr_gravida');
		$this->db->query('select * from sr_doctor');
		$this->benchmark->mark('noasynend');

		$this->benchmark->mark('asyn');
		$this->db->get_asyn_db()->query('select * from sr_gravida limit 1',function($obj,$stmt){
			
		});
		$this->benchmark->mark('asynend');

		echo 'no asyn time spend : '.$this->benchmark->elapsed_time('noasyn', 'noasynend')."\n";
		echo '   asyn time spend : '.$this->benchmark->elapsed_time('asyn', 'asynend')."\n";

	}


	public function __destruct(){
		if(isset($this->db) && !empty($this->db)){
			$this->db->close();
		}
	}


}
