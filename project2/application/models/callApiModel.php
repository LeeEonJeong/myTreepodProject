<?php
class CallApiModel extends CI_Model {
	const URI = 'https://api.ucloudbiz.olleh.com/server/v1/client/api?';
	const NASURI = 'https://api.ucloudbiz.olleh.com/nas/v1/client/api?';
	
	function objectsIntoArray($arrObjData, $arrSkipIndices = array()) {
		if (is_object ( $arrObjData )) {
			$arrObjData = get_object_vars ( $arrObjData );
		}
		if (is_array ( $arrObjData )) {
			foreach ( $arrObjData as $index => $value ) {
				if (is_object ( $value ) || is_array ( $value )) {
					$value = $this->objectsIntoArray ( $value, $arrSkipIndices );
				}
				if (in_array ( $index, $arrSkipIndices )) {
					continue;
				}
				
				$arrData [$index] = $value;
			}
		}
		return $arrData;
	}
	
	function callCommandReponseJson($URL, $cmdArr, $SECRET) {
	//	$cmdArr ['response'] = 'json';
		
		$xmlUrl = $this->makeXmlUrl2 ( $URL, $cmdArr, $SECRET );
		
		echo $xmlUrl;
		
// 		$orig_error_reporting = error_reporting ();
// 		error_reporting ( 0 );
	
// 		$jsonResult = file_get_contents ( $xmlUrl ); // json파일을 stirng으로 가져옴
		
// 		var_dump($jsonResult);
		
// 		return $jsonResult;
	}
	
	function callCommandReponseXML($URL, $cmdArr, $SECRET) {
		$cmdArr ['response'] = 'xml'; //없어도 됨 (디폴트 : xml)
	
		$xmlUrl = $this->makeXmlUrl ( $URL, $cmdArr, $SECRET );
		
		$orig_error_reporting = error_reporting ();
		error_reporting ( 0 );
		$xmlResult = file_get_contents ( $xmlUrl ); // json파일을 stirng으로 가져옴
		error_reporting ( $orig_error_reporting );
	
		return $xmlResult;
	}
	
	
	
	function callCommand($URL, $cmdArr, $SECRET) {
		$xmlUrl = $this->makeXmlUrl ( $URL, $cmdArr, $SECRET );
		
// 		echo $xmlUrl;
// 		echo '<br>';
		
		$orig_error_reporting = error_reporting ();
		error_reporting ( 0 );
		
		$arrXml = $this->objectsIntoArray ( simplexml_load_string ( file_get_contents ( $xmlUrl ) ) );
		error_reporting ( $orig_error_reporting ); 
		
		return $arrXml;
	}
	
	function makeXmlUrl($URL, $cmdArr, $SECRET) {
		$fArray = array_keys ( $cmdArr );
		$vArray = array_values ( $cmdArr );
	
		$f = array ();
		$v = array ();
		$cmd = array ();
		$cmd1 = array ();
	
		for($i = 0; $i < count ( $cmdArr ); $i ++) {
			$vArray [$i] = strtok ( $vArray [$i], "&" );
			$f [$i] = strtolower ( urlencode ( $fArray [$i] ) );
			$v [$i] = strtolower ( urlencode ( $vArray [$i] ) );
			array_push ( $cmd, $f [$i] . "=" . $v [$i] );
		}
	
		sort ( $cmd );
	
		for($i = 0; $i < count ( $cmdArr ); $i ++)
			array_push ( $cmd1, $fArray [$i] . "=" . $vArray [$i] );
	
			sort ( $cmd1 );
	
			$cmdStr = "";
	
			for($i = 0; $i < count ( $cmd ); $i ++) {
				if ($i == count ( $cmd ) - 1)
					$cmdStr = $cmdStr . $cmd [$i];
					else
						$cmdStr = $cmdStr . $cmd [$i] . "&";
			}
	
			$signature = urlencode ( base64_encode ( hash_hmac ( "sha1", $cmdStr, $SECRET, true ) ) );
	
			$url = $URL;
	
			for($i = 0; $i < count ( $cmd1 ); $i ++)
				$url = $url . $cmd1 [$i] . "&";
	
				$xmlUrl = $url . "signature=" . $signature;
	
				return $xmlUrl;
	}
	 
	function makeXmlUrl2($URL, $cmdArr, $SECRET) {
		$fArray = array_keys ( $cmdArr );
		$vArray = array_values ( $cmdArr );
		
		$f = array ();
		$v = array ();
		$cmd = array (); //cmd는 signature만들때 사용
		$cmd1 = array (); //cmd1은 signature제외 query 만들 떄 사용
		
		for($i = 0; $i < count ( $cmdArr ); $i ++) {
			$vArray [$i] = strtok ( $vArray [$i], "&" );
			$f [$i] = strtolower ( urlencode ( $fArray [$i] ) );
			
			$v [$i] = strtolower (urlencode($vArray [$i]));
			if($fArray[$i] == 'productcode'){// urlencode안함 (productid, 스페이스 -> + 로 변환됨)
				$v [$i] = str_replace('+', ' ', $v [$i]);
			}
			array_push ( $cmd, $f [$i] . "=" . $v [$i] );
		}
		
		sort ( $cmd );
		
		for($i = 0; $i < count ( $cmdArr ); $i ++)
			array_push ( $cmd1, $fArray [$i] . "=" . $vArray [$i] );
		
		sort ( $cmd1 );
		
		$cmdStr = "";
		
		for($i = 0; $i < count ( $cmd ); $i ++) {
			if ($i == count ( $cmd ) - 1)
				$cmdStr = $cmdStr . $cmd [$i];
			else
				$cmdStr = $cmdStr . $cmd [$i] . "&";
		}
		
		echo $cmdStr;
// 		$cmdStr = 'apikey=dujrkdjr3cxhofx7i0l2xjcxzggc2grvecudtophpwg8kgcvljz9-5p0k60gj5fsdhroon9r9pdytd3gkkwraw&command=deployvirtualmachine&productcode=std_wind+2008ent+32bit+ko_1x1&zoneid=eceb5d65-6571-4696-875f-5a17949f3317bkiNhddS1TBDVtcKWYqTTAITIGk%3D';

		$ktsignature = 'bkiNhddS1TBDVtcKWYqTTAITIGk%3D';
		echo $ktsignature.'<hr>';
		$urldecoderesult = urldecode($ktsignature);
		echo $urldecoderesult;
		echo '<hr>';
		echo base64_decode($urldecoderesult);
		echo '<hr>';
		
		$signature = urlencode ( base64_encode ( hash_hmac ( "sha1", $cmdStr, $SECRET, true ) ) );
	
		$url = $URL;
		
		for($i = 0; $i < count ( $cmd1 ); $i ++){
			$url = $url . $cmd1 [$i] . "&";
		}
		
		$xmlUrl = $url . "signature=" . $signature;
		
		return $xmlUrl;
	} 
}