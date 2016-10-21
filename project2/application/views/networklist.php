
<span style="display:none" id="where" >network</span>

<!--  네트워크검색하기 -->
	<div class="container-fluid">
		<div class="row-fluid" >
			<div class="span7"  >
				<h5>네트워크 검색하기</h5>
				<form>
					<div id="custom-search-input">
						<select>
							<option>전체</option>
							<option>KOR-Central A</option>
							<option>KOR-Central B</option>
							<option>KOR-HA</option>
							<option>KOR-Seoul M</option>
							<option>JPN</option>
						</select> <input type="text" class="input-medium search-query"
							placeholder="검색할 서버명을  입력해 주세요." />
						<span class="input-group-btn">
							<button type="submit">
								<i class="icon-search fa-10x"></i> 검색
							</button>
						</span> 
					</div> 
				</form>
			</div>
			<div class="span5" >
			<div class="nav pull-right">
				<br><br> 
					<a class="btn btn-primary" href="/project2/index.php/orderCloud">IP 추가 신청</a> 
			</div> 
			</div>
		</div>
	</div>

<script>
$( 
//-----------------------------
		function (){
			$('#networkinfodiv').prev('span').text('네트워크를 선택해 주세요.');
			
			$('#networkinfodiv').hide();
// 			$('#firewallinfo').hide();
// 		 	$('#portforwardinginfo').hide();
// 		 	$('#publicipinfo').hide(); 
		 	
			$("#networklist_table tr").click(function(){
				 	//publicip = $(this).find('form').val();
				 	$('#networkinfodiv').show();
				 	$('#firewallinfo').hide();
				 	$('#portforwardinginfo').hide();
				 	$('#publicipinfo').show(); 
				 	
				 	$('#firwallMenu').removeClass('active');	
				 	$('#portforwardingMenu').removeClass('active');
				 	
				 	
				 	form = $(this).find('form');
				 	var formData = form.serialize(); 
				 	$.ajax({
						type : "POST",
						url: '/project2/index.php/networklist/getPublicIpInfo',
						data : formData,
						dataType:'json',
						success : function(publicip){
							$('#selectipaddress').html(publicip.ipaddress);
							$('#selectipaddressid').html(publicip.id);
							$('#zoneid').html(publicip.zoneid);
							$('#publicipinfo #ipaddress').html(publicip.ipaddress);
							$('#publicipinfo #id').html(publicip.id);
							$('#publicipinfo #allocated').html(publicip.allocated);
							$('#publicipinfo #state').html(publicip.state);
						},
						error : function( ){  
							alert('실행실패');
						}
				 	});
				});

			$('#firwallMenu').click(function(){
			 	$('#portforwardinginfo').hide();
			 	$('#publicipinfo').hide();
			 	$('#firewallinfo').show();
			 	$('#firewallinfo_table tbody').remove(); 
				$('#firwallMenu').addClass('active');
				$('#portforwardingMenu').removeClass('active');
			 	
				selectipaddressid = $('#selectipaddressid').text(); 
				
				$.ajax({
					type:"POST",
					url: 'http://localhost/project2/index.php/networklist/getlistFireWallInfoByIpAddress',
					data : {'ipaddressid' : selectipaddressid},
					dataType:'json',
					success : function(firewallrules){
// 						alert(firewallrules.count);
// 						alert(firewallrules.firewallrule[0].id);
  
						$('#firewallinfo_table').append('<tbody></tbody>');
							
						for(i=0; i<firewallrules.count; i++){
							firewallrule = firewallrules.firewallrule[i];
							$('#firewallinfo_table tbody').append(
									$('<tr></tr>').append(
										$('<td></td>').html(firewallrule.cidrlist)).append(
										$('<td></td>').html(firewallrule.protocol)).append(
										$('<td></td>').html(firewallrule.startport)).append(
										$('<td></td>').html(firewallrule.endport)).append(
										$('<td></td>').html("<a href=''>수정</a>  |  <a href='' style='color:red'>삭제</a>")
									)
							);
												
						}
					},
					error : function( ){  
						alert('실행실패');
					}
				});
			});
 

			$('#portforwardingMenu').click(function(){
					$('#firewallinfo').hide();
				 	$('#publicipinfo').hide();
				 	$('#portforwardinginfo').show();
				 	$('#portforwardinginfo_table tbody').remove(); 
				 	$('#serverlist').children().remove(); 
				 	$('#portforwardingMenu').addClass('active');
					$('#firwallMenu').removeClass('active');


					selectipaddressid = $('#selectipaddressid').text();
					zoneid = $('#zoneid').text();
					portforwardingruleslist=[]; //전역변수
					
					$.ajax({
						type:"POST",
						url: 'http://localhost/project2/index.php/networklist/getlistPortForwardingRulesByIpAdress',
						data :  { "ipaddressid": selectipaddressid},
						dataType:'json',
						success : function(portforwardingrules){
	 						//showObj(portforwardingrules); //없으면 그냥 <listportforwardingrulesresponse/>이렇게 결과 나오고 null이 날라오는듯
//	 						alert(portforwardingrules.firewallrule[0].id);
	 					 	 
 							if(portforwardingrules == null){
 	 							setModalMsg('포트포워딩 규칙이 없습니다.').modal();
 							}else{
 	 							 
								$('#portforwardinginfo_table').append('<tbody></tbody>');
								for(i=0; i<portforwardingrules.count; i++){
									portforwardingrule = portforwardingrules.portforwardingrule[i];
									 
									linktest = $('<span></span>').attr({id:'delete', style:'color:red'}).html('test'+i); 
									
									
									$('#portforwardinginfo_table tbody').append(
											$('<tr></tr>').append($('<td></td>').html(portforwardingrule.virtualmachinedisplayname))
														  .append($('<td></td>').html(portforwardingrule.publicport+' - '+portforwardingrule.publicendport))
														  .append($('<td></td>').html(portforwardingrule.privateport+' - '+portforwardingrule.privateendport))
														  .append($('<td></td>').html(portforwardingrule.protocol))
														  .append($('<td></td>').html(''))
														  .append($('<td></td>').html("<a class='modifyportforwardinglink' href='#'>수정</a>  |  <span class='test' onclick=deletePortforwarding("+portforwardingrule.id+") style='color:red'>삭제</span>"))
														  .append($('<span></span>').attr({id:'portforwardingid', style: 'display:none'}).html(portforwardingrule.id))
														  .append(linktest)
									);  
								}
 							}
						},
						error : function( ){  
							alert('실행실패'); 
						}
					});
 
// 					for(i=0; i<portforwardingruleslist; i++){
// 						alert(portforwardingruleslist[i]);
// 						$('#test'+i).click( 
// 								 function(){
// 									// showLoadingModal();
// 									 alert(portforwardingrule.id);
// 	//								 $.ajax({
// 	//										type:'GET',
// 	//										url:'/project2/index.php/networklist/deletePortForwardingRule/'+portforwardingrule.id,
// 	//										dataType: 'json',
// 	//										success : function(data){
// 	//											 jobid = data.jobid; 
// 	//											 $.ajax({
// 	//												 type:'GET',
// 	//												 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
// 	//												 dataType:'json',
// 	//												 success : function(data){ 
// 	//													 $('#loadingModal').modal('hide');  
// 	//													 window.location.href='/project2/index.php/networklist';
// 	//												 },
// 	//												 error:function(){
// 	//													 alert('실행실패');
// 	//												 }
// 	//											 }); 
												   
// 	//										},
// 	//										error : function( ){  
// 	//											alert('실행실패');
// 	//										}
// 	//								});//ajax
// 								 }
// 						);//testclick
// 					}
					 

					$.ajax({
						type:'GET',
						url:'/project2/index.php/cloudlist/getVMsByZoneId/'+zoneid,
						dataType: 'json',
						success : function(vms){
							for(i=0; i<vms.length; i++){
								vm=vms[i];
								$('#serverlist').append(
										$('<option></option>').attr({
											'value' : vm.displayname,
											'id' : vm.id
										}).html(vm.displayname)
								);
							}
						},
						error : function( ){  
							alert('실행실패');
						}
					});
				 	
			}) 
		

			var test = function (id){
				alert(id);
// 				$.ajax({
// 					type:'GET',
// 					url:'/project2/index.php/networklist/deletePortForwardingRule/'+id,
// 					dataType: 'json',
// 					success : function(data){
						
// // 						showObj(data);//아무거도 안날라옴
// 						 $('#loadingModal').modal('hide'); 
// 						 location.href="/project2/index.php/networklist"; //일단은
// // 						aync이긴 한데 빨리 되는듯
// // 						 jobid = data.jobid;
// // 						 $.ajax({
// // 							 type:'GET',
// // 							 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
// // 							 dataType:'json',
// // 							 success : function(data){
// // 								 $('#loadingModal').modal('hide'); 
// // 								 location.href="/project2/index.php/networklist"; //일단은
// // 							 },
// // 							 error:function(){
// // 								 alert('실행실패');
// // 							 }
// // 						 });
// 					},
// 					error : function( ){  
// 						alert('실행실패');
// 					}
// 				});
			}

			
		$('#eonjeongtest').html('eonjeongtest');
		$('#eonjeongtest').click(test('testid'));
		//$('#eonjeongtest').click(function(){alert('test')});
		
		$('#createportforwardingbtn').click(
			function(){ 
				selectipaddressid = {name : 'ipaddressid', value : $('#selectipaddressid').text()};
				virtualmachineid = {name : 'virtualmachineid', value : $('#serverlist option:selected').attr('id')};
				protocol = {name : 'protocol', value : $('#portforwardingprotocol option:selected').val() };
 
				form = $('#createportforwardingform');
			 	var formData = form.serializeArray(); 
	
			 	formData.push(selectipaddressid,virtualmachineid,protocol);
			 
// 				$.ajax({
// 					type:'POST',
// 					url: 'http://localhost/project2/index.php/networklist/createPortForwarding',
// 					data : formData,
// 					dataType:'json',
// 					success : function(publicip){
// 						alert('실행성공');
// 						$('#result').text(publicip);
// 					},
// 					error : function( ){  
// 						alert('실행실패');
// 					}
// 				}); 
				showLoadingModal();
				$.ajax({
					type:'POST',
					url:'/project2/index.php/networklist/createPortForwarding',
					data : formData,
					dataType: 'json',
					success : function(data){
// 						showObj(data);//아무거도 안날라옴
						 $('#loadingModal').modal('hide'); 
						 location.href="/project2/index.php/networklist"; //일단은
// 						aync이긴 한데 빨리 되는듯
// 						 jobid = data.jobid;
// 						 $.ajax({
// 							 type:'GET',
// 							 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
// 							 dataType:'json',
// 							 success : function(data){
// 								 $('#loadingModal').modal('hide'); 
// 								 location.href="/project2/index.php/networklist"; //일단은
// 							 },
// 							 error:function(){
// 								 alert('실행실패');
// 							 }
// 						 });
					},
					error : function( ){ 
						alert('실행실패');
					}
				}); //ajax
			}

			
		);

		$('#createfirewallbtn').click(
				function(){ 
					selectipaddressid = {name : 'ipaddressid', value : $('#selectipaddressid').text()};
					protocol = {name : 'protocol', value : $('#firewallprotocol option:selected').val() };
	 
					form = $('#createportforwardingform');
				 	var formData = form.serializeArray(); 
		
				 	formData.push(selectipaddressid, protocol);
				  
					showLoadingModal();
					$.ajax({
						type:'POST',
						url:'/project2/index.php/networklist/createFirewallRule',
						data : formData,
						dataType: 'json',
						success : function(data){
//	 						showObj(data);//아무거도 안날라옴
							 $('#loadingModal').modal('hide'); 
							 location.href="/project2/index.php/networklist"; //일단은
//	 						aync이긴 한데 빨리 되는듯
//	 						 jobid = data.jobid;
//	 						 $.ajax({
//	 							 type:'GET',
//	 							 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
//	 							 dataType:'json',
//	 							 success : function(data){
//	 								 $('#loadingModal').modal('hide'); 
//	 								 location.href="/project2/index.php/networklist"; //일단은
//	 							 },
//	 							 error:function(){
//	 								 alert('실행실패');
//	 							 }
//	 						 });
						},
						error : function( ){ 
							alert('실행실패');
						}
					}); //ajax
				}

				
			);
		
		function showObj(obj){
			var str="";
			for(key in obj){
				str  += key+"="+obj[key]+"\n";
			}
			alert('showObj\n'+str);
			return;
		}

		function setModalMsg(msg){ 
			 $('#msgModal #msg').empty();
			 $('#msgModal #msg').html(msg);
			 return $('#msgModal');
		}


		function showLoadingModal(){
			$('#loadingModal').modal({backdrop:"static", keyboard:false}); 
		}
}
//-----------------------------			
);  
</script>
<div id='resulttest'><a id='eonjeongtest' >test</a></div>
<!-- 네트워크목록--> 
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12" > 
				<h5>네트워크목록 (총 <?= $publicIpCount?>건) </h5>
				<table class="table table-hover" id="networklist_table">
					<thead>
						<tr>
							<td>번호</td>
							<td>공인IP</td>
							<td>위치</td>
							<td>설명</td>
						</tr>
					</thead>
					<tbody>
		<?php
	 
		for($i = 0; $i < $publicIpCount; $i ++) {
			if ($publicIpCount == 1) {
				$publicIp = $publicIps['publicipaddress'];
			} else {
				$publicIp = $publicIps['publicipaddress'][$i];
			}
			 
			echo "<tr><form><td>";
			echo "<input type='hidden' name='publicip' value='" . $publicIp ['id'] . "'/>";
			echo $i + 1;
			echo "</td><td>";
			echo $publicIp ['ipaddress'];
			echo "</td> <td>";
			echo $publicIp ['zonename']; 
			echo "</td><td></td></form></tr>";
			 
		}
		?>
		</tbody>
				</table>
			</div>
		</div>
</div>

<!-- 서버관리메뉴 와 서브정보 -->
<div class="container-fluid">
	 <div class="row-fluid"> 