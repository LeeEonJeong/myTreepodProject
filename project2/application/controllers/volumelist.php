<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Volumelist extends MY_Controller{
		function __construct()
		{
			parent::__construct();
			$this->load->model('volumesModel');
		}
		
		function index(){
			$this->_head();
			$returnURI = '/networklist'; // 일단임의로 나중에 returnURI 다시 처리하자
			$this->_require_login($returnURI);
			
			$volumes = $this->volumesModel->getlistVolumes();
		  
			$volumeCount = $volumes['count'];
			 
			$volumelistdata = array(
					'volumes' => $volumes,
					'volumeCount' => $volumeCount
			); 
		
			$this->load->view('volumelist', $volumelistdata);
			$this->load->view('volumeManageMenu');
		    $this->load->view('volumeInfo');
			$this->_footer();
		}
		
		function getlistVolumes(){
			$result =  $this->volumesModel->getlistVolumes();
			print( json_encode($result));
		}
		
		function searchVolume($volumeid){
			$result = $this->volumesModel->searchVolume($volumeid);
			
			print(json_encode($result));
		}
		
		function getVolumes($virtualmachineid){
			$result = $this->volumesModel->getVolumes($virtualmachineid); 
			print(json_encode($result));
		}
		
		function attachVolume($id, $virtualmachineid){
			$result = $this->volumesModel->attachVolume($id, $virtualmachineid); 
			print(json_encode($result));
		}
		
		function detachVolume($id){
			$result = $this->volumesModel->detachVolume($id); 
			print(json_encode($result));
		}
		
		function deleteVolume($id){
			$result = $this->volumesModel->deleteVolume($id); 
			print(json_encode($result));
		}
	}