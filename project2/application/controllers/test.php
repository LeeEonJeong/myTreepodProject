<?php
class Test extends MY_Controller{
	function __construct(){
		parent::__construct();
	}
	
// 	function test(){
// 		if(!$this->session->userdata('is_login')){
// 			echo site_url('/test/test');
// 			redirect('/auth/login?returnURL='.rawurlencode(site_url('/test/test')));
// 			//redirect('/auth/login?returnURL='.rawurlencode('http://localhost/project1/index.php/test/test'));
// 		}
// 		echo 'test';
// 		$this->load->view('test2');
// 	}
	
	function stopVM(){
		$this->load->model('cloudsModel');
		
		//$data  = array( 'vmid' => $_POST('vmid'));
		$this->load->view('stopVM'); 
	}
	
	function test(){
		$this->load->model('callApiModel');
		$cmdArr = array (
				"command" => "addVolume",
				"apikey" => $_SESSION ['apikey'] 
		);
		
		$vms = $this->callApiModel->callCommandReponseJson( CallApiModel::NASURI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
		
		//var_dump($vms);
	}
}