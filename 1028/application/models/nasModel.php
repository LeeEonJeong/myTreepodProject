<?php
 
class NasModel extends CI_Model {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'callApiModel' ); 
	}
	 
	function addVolume() { 
		$cmdArr = array (
						"command" => "addVolume",
						"name" => $_POST['name'],
						"usageplantype" =>$_POST['usageplantype'],
						"path" =>$_POST['path'],
						"totalsize" => $_POST['totalsize'],
						"volumetype" => $_POST['volumetype'],
						"zoneid" => $_POST['zoneid'],
						"apikey" => $_SESSION ['apikey']
				);//autoresize=false&usageplantype=monthly
		
// 		$cmdArr = array (
// 				"command" => "addVolume",
// 				"name" => 'test',
// 				"usageplantype" =>'test',
// 				"path" =>'test',
// 				"totalsize" => 'test',
// 				"volumetype" => 'test',
// 				"zoneid" => 'test',
// 				"apikey" => $_SESSION ['apikey']
// 		);//autoresize=false&usageplantype=monthly
		
		$result = $this->callApiModel->callCommand( CallApiModel::NASURI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
		 
		return $result;
	}
	
	
	function deleteVolume($volumeid) {
		$cmdArr = array (
				"command" => "deleteVolume",
				"id" => $volumeid,
				"apikey" => $_SESSION ['apikey']
		);
	
		$result = $this->callApiModel->callCommand( CallApiModel::NASURI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
	
		return $result;
	}
	
	function addNicToVirtualMachine($networkid,$virtualmachineid ){ //비동기
		$cmdArr = array (
				"command" => "addNicToVirtualMachine",
				"networkid" => $networkid,
				"virtualmachineid" => $virtualmachineid,
				"apikey" => $_SESSION ['apikey']
		);
		
		$jobid = $this->callApiModel->callCommand( CallApiModel::URI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
		
		return $jobid;
	}
	
	function removeNicFromVirtualMachine($nicid,$virtualmachineid ){ //비동기
		$cmdArr = array (
				"command" => "removeNicFromVirtualMachine",
				"nicid" => $nicid,
				"virtualmachineid" => $virtualmachineid,
				"apikey" => $_SESSION ['apikey']
		);
		
		$jobid = $this->callApiModel->callCommand( CallApiModel::URI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
		
		return $jobid;
	} 
 	
	function getlistVolumes(){
		$cmdArr = array (
				"command" => "listVolumes", 
				"apikey" => $_SESSION ['apikey']
		);
		
		$result = $this->callApiModel->callCommand( CallApiModel::NASURI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
		return $result;
	}
	
	function getVolumes($condition, $value){
		$cmdArr = array (
				"command" => "listVolumes",
				$condition => $value,
				"apikey" => $_SESSION ['apikey']
		); 
		$result = $this->callApiModel->callCommand( CallApiModel::NASURI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
		return $result;
	}
	
	function getRunningVolumeZones(){
		$runningVolumes = $this->getVolumes('status', 'online')['response'];
		$zoneids = array();
		
		foreach($runningVolumes as $volume){
			array_push($zoneids, $volume['zoneid']);
		}
		return $zoneids;
	}
	
	function addCifsAccount(){
		$cmdArr = array (
			"command" => "addCifsAccount",
			'cifsId' => cifsId,
			'cifsPw'=>cifsPw,
			"apikey" => $_SESSION ['apikey']
		);
		$result = $this->callApiModel->callCommand( CallApiModel::NASURI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
		return $result;
	}
}