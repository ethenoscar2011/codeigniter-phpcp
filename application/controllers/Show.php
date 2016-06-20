<?php 

defined('BASEPATH') OR exit('No direct script access allowed');


class Show extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('mtest');
		$this->load->helper('func');
	}

	public function add_test(){
		$data = array(
			'username' => 'tet',
			'mobile' => '1453859333'
		);
		dump($this->mtest->add_test($data));
	}

	public function show_all(){
		$res = $this->mtest->query_all();
		dump($res);
	}

}