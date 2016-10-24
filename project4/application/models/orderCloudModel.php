<?php
// db에서 사용자의 클라우드 가져오기
/*
 * 해당 유저의 서버 정보 가져오기
 * 서칭기능
 * 볼륨이 붙여진 클라우드 가져오기
 *
 */
class OrderCloudModel extends CI_Model {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'callApiModel' ); 
	}
	
	function checkVirtualMachineName($checkname){
		$cmdArr = array (
				"command" => "checkVirtualMachineName",
				"display_name" => $checkname,
				"apikey" => $_SESSION ['apikey']
		);
			
		$result = $this->callApiModel->callCommand( CallApiModel::URI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
		return $result;
	}
	
	function orderVM(){
// 		$cmdArr = array (
// 				"command" => "deployVirtualMachine",
// 				"displayname" => $_POST['displayname'],
// 				"name" => $_POST['name'],
// 				"zoneid" => $_POST['zoneid'],
// 				"productcode" => $_POST['productcode'],
// 				"usageplantype" => $_POST['usageplantype'],
// 				"apikey" => $_SESSION ['apikey']
// 		);
		
		$cmdArr = array(
				"command" => "deployVirtualMachine",
				"zoneid" => "eceb5d65-6571-4696-875f-5a17949f3317",
				"productcode" => "std_wind 2008ent 32bit ko_1x1",
				"apikey" => $_SESSION['apikey']
		);
		
// 		$cmdArr = array(
// 				"command" => "deployVirtualMachine",
// 				"zoneid" => "eceb5d65-6571-4696-875f-5a17949f3317",
// 				"diskofferingid" => "29d33bf5-39e0-456c-b70c-cc42f0560d33",
// 				"serviceofferingid" => "c504e367-20d6-47c6-a82c-183b12d357f2",
// 				"templateid" => "a57d10ec-9588-436f-bbb5-8a68eadf2254",
// 				"apikey" => $_SESSION['apikey']
// 		);
		
		
		$result = $this->callApiModel->callCommandReponseJson(CallApiModel::URI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
		
		//echo var_dump($result);
		//return $result;  
	}

	//리팩토링용
/* 	function getlistAvailableProductTypes() { 
		//try{
		$content = file_get_contents('listAvailableProductTypes.json');//CI error handling 문제 나중 처리
		//}catch(Exception $e){
			//echo $e->getMessage();
		//}
		if($content===FALSE){
			//$zoneid='eceb5d65-6571-4696-875f-5a17949f3317';
			$cmdArr = array (
					"command" => "listAvailableProductTypes",
					"zoneid" => $_POST['zoneid'],
					"apikey" => $_SESSION ['apikey']
			);
			
			$listAvailableProductTypes = $this->callApiModel->callCommandReponseJson( CallApiModel::URI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
			
			file_put_contents('listAvailableProductTypes.json',$listAvailableProductTypes); //프로젝트폴더 파로 아래에 있음
			return $listAvailableProductTypes;
		}else{
			return $content;
		}
	} */
	
	
	function getlistAvailableProductTypesByZoneid(){
		$cmdArr = array (
				"command" => "listAvailableProductTypes",
				"zoneid" => $_POST['zoneid'],
				//"zoneid" => 'eceb5d65-6571-4696-875f-5a17949f3317',
				"apikey" => $_SESSION ['apikey']
		);
			
		$listAvailableProductTypes = $this->callApiModel->callCommand( CallApiModel::URI, $cmdArr, $this->session->userdata ( 'secretkey' ) );
		
		return $listAvailableProductTypes;
	}

	function getPackagesByZoneid(){
		$availableProductTypes = $this->getlistAvailableProductTypesByZoneid();
		$count = $availableProductTypes['count'];
		$productTypes = $availableProductTypes['producttypes'];
		$packages = array();
		$index=0;
		
		if($count==0){
			return $packages;
		}
		
		$old='';
		for($i=0; $i<$count; $i++){
			$new = $productTypes[$i]['product'];
			if($new != $old && $new !=null){
				$packages[$index++] = $new;
				$old = $new;
			}
		}
		return $packages;			
	}
	
	
	function getProductIds($productsArray){
		$count = count($productsArray);
		$productids = array();
		$index = 0;
		for($i=0; $i<$count; $i++){
			$product = $productsArray[$i];
			$productid = explode('_', $product['productid']);//array
			if(count($productid) != 0)
				$productids[$index++] = $productid;
		}
		return $productids;
	}
	
	function getOSlist(){
		$productsArray = $this->getProductsByZoneidAndPackage();
		$productids = $this->getProductIds($productsArray);
		$productoslist = array();
		$index = 0;
		$old='';
// 		echo print_r($productids);
		for($i=0; $i<count($productids); $i++){
			$productid = $productids[$i];
			
			//index 0 producttype(std, high, ssd)
			//index 1 os
			//index 2 datadisk
			
			if(count($productid) > 1){ //중간에 없는 데이터있음.. 머냥....
				$productos = $productid[1];
			};
			 
			if($old != $productos){
				$productoslist[$index++] = $productos;
				$old = $productos;
			}
		}
		
		return $productoslist;
	}
	
	function getDatadisklist(){
		$productsArray = $this->getProductsByZoneidAndPackage();
		$productids = $this->getProductIds($productsArray);
		$datadisklist = array();
		$index = 0;
		$old='';
// 		echo print_r($productids);
		for($i=0; $i<count($productids); $i++){
			$productid = $productids[$i];
			
			//index 0 producttype(std, high, ssd)
			//index 1 os
			//index 2 datadisk
			
			if(count($productid) > 1 && $productid[1] == $_POST['os']){ //중간에 없는 데이터있음.. 머냥....
				
				$datadisk = $productid[2];
				if($old != $datadisk){
					$datadisklist[$index++] = $datadisk;
					$old = $datadisk;
				}
			}; 
		}
		
		return $datadisklist;
	}
	
	
	function getProductsByZoneidAndPackage(){
		$package = $_POST['package'];
		$zoneid = $_POST['zoneid']; 
 
		$productsByZoneid = $this->getlistAvailableProductTypesByZoneid();
		$productType = $this->getProductType($package);
		 
		$result = $this->getProducts($productsByZoneid['producttypes'], 'productid', $productType);
	 	
		return $result;
	}
	
	private function getProductType($package){
		switch($package){
			case 'Standard':
				$productType = 'std';
				break;
			case 'HighMemory':
				$productType = 'high';
				break;
			case 'SSD':
				$productType = 'ssd';
				break;
			default:
				$productType = 'std';
		}
		return $productType;
	}
	 
	
	private function getProducts($productTypes, $condition, $substring){
		$products = array();
		$count = count($productTypes);
		 
		$index=0;
		 
		for($i=0; $i<$count; $i++){
			$product = $productTypes[$i];
			//echo var_dump($product)."<br>";
			
			$ishas = strpos($product[$condition], $substring);
			
			if(isset($ishas)){
				$products[$index++] = $product;
				unset($ishas);
			}
		}
		return $products;
	}
	
	private function getAvailableProductTypes($condition, $value){
		$availableProductTypes = $this->getlistAvailableProductTypes();
		$count = $availableProductTypes['count'];
		 
		$productTypes = $availableProductTypes['producttypes'];
		$products = array();
		$index=0;
		
		for($i=0; $i<$count; $i++){
			$product = $productTypes[$i];
			 
			if($product[$condition] == $value){
				$products[$index++] = $product; 
			}
		}
		 
		return $products;
	}
	 
	  
}