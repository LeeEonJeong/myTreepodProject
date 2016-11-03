<?php
class CifsAccountModel extends CI_Model {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'callApiModel' ); 
	} 
	 
	function addAccountForNas(){ //NAS 서비스 이용을 위한 내부 계정을 관리서버에 등록한다.
		$result = $this->cifsAccountModel->addAccountForNas();
			
	} 
	
	function addCifsAccount() {//CIFS 볼륨 접근에 필요한 ID를 생성한다. (최대 10개까지 생성 가능함)
		$cmdArr = array(
				"command" => "addCifsAccount",
				"cifsId" => $_POST['cifsid'],
				"cifsPw" => $_POST['cifspwd'],
// 				"cifsId" => 'test213',
// 				"cifsPw" => 'test2$2sdfs',
				"apikey" => $_SESSION ['apikey']
		);
	
		$result = $this->callApiModel->callCommand(CallApiModel::NASURI, $cmdArr, $_SESSION['secretkey']);
		return $result;
	}
	
	function deleteCifsAccount($cifsId){//CIFS 아이디를 삭제한다.
		$cmdArr = array(
				"command" => "deleteCifsAccount",
				"cifsId" => $cifsId, 
				"apikey" => $_SESSION ['apikey']
		);
	
		$result = $this->callApiModel->callCommand(CallApiModel::NASURI, $cmdArr, $_SESSION['secretkey']);
		return $result;	
	}
	
	function listAccountForNas(){//NAS 서비스 이용을 위한 내부 계정의정보를 조회한다.
		$cmdArr = array(
				"command" => "listAccountForNas",  //accountid필요한데 안넣어도 나옴
				"apikey" => $_SESSION ['apikey']
		);
	
		$result = $this->callApiModel->callCommand(CallApiModel::NASURI, $cmdArr, $_SESSION['secretkey']);
		return $result;	 
	} 
	
	function listCifsAccounts() {//CIFS 아이디의 목록을 조회한다.
		$cmdArr = array(
				"command" => "listCifsAccounts",
				"apikey" => $_SESSION ['apikey']
		);
	
		$result = $this->callApiModel->callCommandResponseJSON(CallApiModel::NASURI, $cmdArr, $_SESSION['secretkey']);
		return $result;
	}
		
	function updateAccountForNas($cifsworkgroup){ //NAS 서비스 이용을 위한 내부 계정의 cifs 인증정보를 갱신한다. //????
		$cmdArr = array(
				"command" => "updateAccountForNas",
				//"accountid" => $accountid,
				//"cifsid" => $cifsId,
				//"cifspassword" => $cifspassword, //쓸 일 없을듯
				"cifsworkgroup" => $cifsworkgroup,
				"apikey" => $_SESSION ['apikey']
		);
	
		$result = $this->callApiModel->callCommand(CallApiModel::NASURI, $cmdArr, $_SESSION['secretkey']);
		return $result;
	}
	
	function updateCifsAccount(){//CIFS 아이디의 비밀번호를 변경한다. 
			$cmdArr = array(
				"command" => "updateCifsAccount", 
				"cifsId" => $_POST['cifsid'],
				"cifsPw" => $_POST['cifspwd'],  
				"apikey" => $_SESSION ['apikey']
		);
	
		$result = $this->callApiModel->callCommand(CallApiModel::NASURI, $cmdArr, $_SESSION['secretkey']);
		return $result;
	
	}
	
}