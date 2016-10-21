<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Cloudlist extends MY_Controller{
		function __construct()
		{
			parent::__construct();
			$this->load->model('cloudsModel');
		}
		
		function index(){
			$this->_head();
			$returnURI = '/cloudlist'; // 일단임의로 나중에 returnURI 다시 처리하자
			$this->_require_login($returnURI);
			
			$vms = $this->cloudsModel->getlistVms();
		 	
			 
			$vmcount = $vms['count'];
			
			$cloudlistdata = array(
					'clouds' => $vms,
					'vmcount' => $vmcount
			); 
		
			$this->load->view('cloudlist', $cloudlistdata); 
		 	$this->load->view('cloudManageMenu');
		 	 
		 	if($vmcount==0){
		 		$vminfo = "생성된 서버가 없습니다.";
		 	}elseif($vmcount == 1){
		 		$vminfo = $this->cloudsModel->getVMs($vms, 'id',$vms['virtualmachine']['id']);
		 	}else{
		 		$vminfo = $this->cloudsModel->getVMs($vms, 'id',$vms['virtualmachine'][0]['id']); //일단 임의로
		 	}
		 	
		 	$serverinfo = array(
		 			'server' => $vminfo
		 	);
		 	
		 	$this->load->view('serverInfo',$serverinfo); 
			
			$this->_footer();
		}  
		 
		function startVM(){
			$result = $this->cloudsModel->startVM(); 
			print(json_encode($result));
		}
		
		function stopVM(){
			$result = $this->cloudsModel->stopVM(); 
			print(json_encode($result));
		}
		
		function rebootVM(){
			$result = $this->cloudsModel->stopVM();
			print(json_encode($result));
		}
		
		function getVMsByZoneId($zoneid){
			$vms = $this->cloudsModel->getVMsByCondition('zoneid',$zoneid);
			print(json_encode($vms));
		}
		
// 		function getVMsByZoneId($zoneid){ //publicip로 할수 없어서 일단은 zoneid로 서치
// 			$vms = $this->cloudsModel->getlistVMs();
			
// 			$result = $this->cloudsModel->getVMS($vms, 'zoneid', $zoneid );
			
// 			print(json_encode($result));
// 		}
		
		function searchVM($vmid){
			$result = $this->cloudsModel->searchVM($vmid);
			print(json_encode($result));
		}
		
		//----Stopped일 때 사용
		 
		function initializeOS($vmid){
			$result = $this->cloudsModel->initializeOS($vmid);
			print(json_encode($result));
		}
		
		function resetPwdVM($vmid){
			$result = $this->cloudsModel->resetPwdVM($vmid);
			print(json_encode($result));
		}
		
		function deleteVM($vmid){
			$result = $this->cloudsModel->deleteVM($vmid);
			print(json_encode($result));
		}
		
		function updateDisplayname($vmid){ //동기화아님
			$result = $this->cloudsModel->updateDisplayname($vmid, $newDisplayname);
			print(json_encode($result));
		} 
		
		function getlistVMs(){
			$result = $this->cloudsModel->getlistVMs();
			print(json_encode($result));
		} 
		 
		
		function getVMsForNAS(){
			$vms = $this->cloudsModel->getVMsForNAS();
			
			//존에 해당하는 cip id를 넣게위해서
			$this->load->model('networksModel');
// 			echo count($vms).'<hr>';
			
// 			echo var_dump($vms[3]);
			
// 			for($i=0; $i < count($vms); $i++){
// 				$zoneid = $vms[$i]['zoneid'];
// 				$networkinfo = $this->networksModel->getNASNetworkInfoByZoneid($zoneid);
// 				$zonecipid = $networkinfo['id']; //네트워크id(cipid)
// 				echo $zonecipid+'<br>';
// 				$vms['zonecipid'] = $zonecipid;
// 			}
			$index = 0;
			foreach($vms as $vm){
				$zoneid = $vm['zoneid'];
				$networkinfo = $this->networksModel->getNASNetworkInfoByZoneid($zoneid);
				$zonecipid = $networkinfo['id'];
				$vms[$index]['zonecipid'] = $zonecipid;
				$index++;
			}
			
			print(json_encode($vms));
		} 
		
		function getVMsByCondition($condition, $value){
			$result = $this->cloudsModel->getVMsByCondition($condition, $value);
			print(json_encode($result));
		}
	}