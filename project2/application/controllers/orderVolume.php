<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class orderVolume extends MY_Controller{
		function __construct()
		{
			parent::__construct();
			$this->load->model('orderVolumeModel');
		}
	 
		
		function index(){
			$this->_head();
			$returnURI = '/volumelist'; // 일단임의로 나중에 returnURI 다시 처리하자
			$this->_require_login($returnURI);
			 
			$this->load->view ('orderVolume');
			$this->_footer ();
		} 
		
		
		function orderVolume(){
			$result = $this->orderVolumeModel->orderVolume();
			echo json_encode($result);
		}
		
	}