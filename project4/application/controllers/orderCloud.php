 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class OrderCloud extends MY_Controller{
		function __construct()
		{
			parent::__construct();
			$this->load->model('orderCloudModel');
		}
	 
		
		function index(){
			$this->_head();
			$returnURI = '/cloudlist'; // 일단임의로 나중에 returnURI 다시 처리하자
			$this->_require_login($returnURI);
			 
			$this->load->view ('orderCloud');
			$this->_footer ();
		} 
		
		function checkVirtualMachineName($checkname){
			$result = $this->orderCloudModel->checkVirtualMachineName($checkname);
			
			echo $result['Success'];
// 			if($result['Success'] == 'true'){
// 				echo "사용할 수 있는 서버명입니다.";
// 			}else if($result['Success'] == 'false'){
// 				echo "사용할 수 없는 서버명입니다.";
// 			}
		}
		
		function getlistAvailableProductTypesByZoneid(){
			$listAvailableProductTypes = $this->orderCloudModel->getlistAvailableProductTypesByZoneid();
			
			echo json_encode($listAvailableProductTypes);
		}
		
		function getPackagesByZoneid(){
			$packages =  $this->orderCloudModel->getPackagesByZoneid();
			
			echo json_encode($packages);
		}
		
		function getOSlist(){
			$productoslist = $this->orderCloudModel->getOSlist();
			
			echo json_encode($productoslist);
		}
		
		function getDatadisklist(){
			$productdisklist = $this->orderCloudModel->getDatadisklist();
			
			echo json_encode($productdisklist);
		} 
		 
		function orderVM(){ 
			$this->orderCloudModel->orderVM();
			//$result = $this->orderCloudModel->orderVM();
			//echo json_encode($result);
		}
	}