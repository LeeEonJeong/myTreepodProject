<span style="display:none" id="where" >cloudlist</span>
 
<!-- <form class="form-search"> -->
<!--   <input type="text" class="input-medium search-query" placeholder="검색할 서버명"> -->
<!--   <button type="submit"><i class="icon-search"></i></button> -->
<!-- </form> -->
<script>
 
</script>
<!-- 서버검색하기 -->
	<div class="container-fluid">
		<div class="row-fluid" >
			<div class="span7"  >
				<h5>서버검색하기</h5>
				<form>
					<div id="custom-search-input">
						<select>
							<option>전체test</option>
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
					<a class="btn btn-primary" href="/project2/index.php/orderCloud">서버 생성하기</a> 
			</div> 
			</div>
		</div>
	</div>

<script>
$(
		function(){
			$('#serveractiondiv').hide();
			$('#serverinfodiv').hide();
			$('#servervolumeinfodiv').hide();
			$('#serveractiondiv').prev('span').text('서버를 선택해 주세요.');	

			$('#serverlist_table tbody tr').each(
				function(index){ 
					vmstate = $('#state'+index).text();
				 
					switch(vmstate){
						case 'Running': 
							$('#'+index+'start').prop('disabled',true);
							$('#'+index+'stop').prop('disabled',false);
							$('#'+index+'reboot').prop('disabled',false);
							break;
						case 'Stopped':
							$('#'+index+'start').prop('disabled',false);
							$('#'+index+'stop').prop('disabled',true);
							$('#'+index+'reboot').prop('disabled',true);
							break;
						default : //Starting , Stopping
							$('#'+index+'start').prop('disabled',true);
							$('#'+index+'stop').prop('disabled',true);
							$('#'+index+'reboot').prop('disabled',true);
							break;
					}
				}
			);
			
			
			$(":button").click(	
				function(){
				    name = $(this)[0].id;
				    formindex = name.replace(/[^0-9]/g,"");
					action = name.substring(1);
					var formData = $("#form"+formindex).serialize();
					//alert(formData);
					if(action=='start'){
						showLoadingModal();
						$.ajax({
								type : "POST",
								url: '/project2/index.php/cloudlist/startVM',
								dataType: 'json',
								data : formData,
								success : function(data){
// 									alert(action+'실행성공');
// 									$('#state'+formindex).html('Running');
									 jobid = data.jobid;
									 alert(jobid); 
									 
									 $.ajax({
										 type:'GET',
										 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
										 dataType:'json',
										 success : function(data){ 
											 $('#loadingModal').modal('hide');  
											 //if(data.jobstatus==2){ 
											 window.location.href='/project2/index.php/cloudlist';
											// }else{
											//	 alert('서버연결해제 실패');
											// }
										 },
										 error:function(){
											 alert('실행실패');
										 }
									 }); 

								},
								error : function( ){  
									alert(action+'실행실패');
									$('#result').html('통신실패');
								}
						}); 
					}else if(action == 'stop'){
						showLoadingModal();
					 
						$.ajax({
							type : "POST",
							url: '/project2/index.php/cloudlist/stopVM',
							data : formData,
							dataType: 'json',
							success : function(data){
//									alert(action+'실행성공');
//									$('#state'+formindex).html('Running');
								 jobid = data.jobid; 
								 $.ajax({
									 type:'GET',
									 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
									 dataType:'json',
									 success : function(data){ 
										 $('#loadingModal').modal('hide');  
										 //if(data.jobstatus==2){ 
										 window.location.href='/project2/index.php/cloudlist';
										// }else{
										//	 alert('서버연결해제 실패');
										// }
									 },
									 error:function(){
										 alert('실행실패');
									 }
								 }); 

							},
							error : function( ){  
								alert(action+'실행실패');
								$('#result').html('통신실패');
							}
						}); 
					}else if(action == 'reboot'){
						showLoadingModal();

						$.ajax({
							type : "POST",
							url: '/project2/index.php/cloudlist/stopVM',
							data : formData,
							dataType: 'json',
							success : function(data){
//									alert(action+'실행성공');
//									$('#state'+formindex).html('Running');
								 jobid = data.jobid; 
								 $.ajax({
									 type:'GET',
									 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
									 dataType:'json',
									 success : function(data){ 
										 $('#loadingModal').modal('hide');  
										 //if(data.jobstatus==2){ 
										 window.location.href='/project2/index.php/cloudlist';
										// }else{
										//	 alert('서버연결해제 실패');
										// }
									 },
									 error:function(){
										 alert('실행실패');
									 }
								 }); 

							},
							error : function( ){  
								alert(action+'실행실패');
								$('#result').html('통신실패');
							}
					});
						
				}  
		});

		$('#osinitializebtn').click(
				function(){
					vmid = $('#serverlist_table #vmid').text(); 
					showLoadingModal();
					$.ajax({
						type:'GET',
						url:'/project2/index.php/cloudlist/initializeOS/'+vmid,
						dataType: 'json',
						success : function(data){
							 jobid = data.jobid;
							 $.ajax({
								 type:'GET',
								 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
								 dataType:'json',
								 success : function(data){
									 $('#loadingModal').modal('hide'); 
									 setModalMsg('OS초기화가 완료되었습니다.').modal(); 
								 },
								 error:function(){
									 alert('실행실패');
								 }
							 });
						},
						error : function( ){ 
							alert('실행실패');
						}
					}); //ajax
				}
		);

		$('#resetpwdbtn').click(
				function(){
					vmid = $('#serverlist_table #vmid').text(); 
					showLoadingModal();
					$.ajax({
						type:'GET',
						url:'/project2/index.php/cloudlist/resetPwdVM/'+vmid,
						dataType: 'json',
						success : function(data){
							 jobid = data.jobid;
							 $.ajax({
								 type:'GET',
								 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
								 dataType:'json',
								 success : function(data){
									 $('#loadingModal').modal('hide'); 
									 setModalMsg('비밀번호초기화가 완료되었습니다.\n 비밀번호 : '+data.jobresult.password).modal();
								 },
								 error:function(){
									 alert('실행실패');
								 }
							 });
						},
						error : function( ){ 
							alert('실행실패');
						}
					}); //ajax
				}
		); 

		$('#deletevmbtn').click(
				function(){
					vmid = $('#serverlist_table #vmid').text(); 
					showLoadingModal();
					$.ajax({
						type:'GET',
						url:'/project2/index.php/cloudlist/deleteVM/'+vmid,
						dataType: 'json',
						success : function(data){
							 jobid = data.jobid;
							 $.ajax({
								 type:'GET',
								 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
								 dataType:'json',
								 success : function(data){
									 $('#loadingModal').modal('hide'); 
									 location.href='/project2/index.php/cloudlist';
								 },
								 error:function(){
									 alert('실행실패');
								 }
							 });
						},
						error : function( ){ 
							alert('실행실패');
						}
					}); //ajax
				}
		);
		

		$('#serverlist_table tr').click(
				function(){
					$('#serveractiondiv').show();
					$('#serverinfodiv').show();
					$('#servervolumeinfodiv').hide();
					$('#serveractiondiv').prev('span').empty();
					
					$('#serverinfoMenu').addClass('active');
					$('#diskinfoMenu').removeClass('active');
					
				    formindex =$(this).index();
					formdata = $("#form"+formindex).serializeArray()[0]; //어차피하나(vmid)
					vmid = formdata['value']; 
					state = $('#serverlist_table #state'+formindex).text();


					if(state=='Stopped'){
						$('#osinitializebtn').prop('disabled',false);
						$('#resetpwdbtn').prop('disabled',false);
						$('#deletevmbtn').prop('disabled',false);
						//asdfasdf
					}else{
						$('#osinitializebtn').prop('disabled',true);
						$('#resetpwdbtn').prop('disabled',true);
						$('#deletevmbtn').prop('disabled',true);
					}
					
 					$.ajax({
 	 					type:"GET",
 	 					url:"/project2/index.php/cloudlist/searchVM/"+vmid,
 	 					dataType:'json',
 	 					success:function(data){
 	 	 					vm = data.virtualmachine;
 	 	 					 
 	 	 					$('#serveractiondiv').prev('span').html("<h5>선택된 서버 : <span id='selectvmid' style='display:none'>"+vm.id+"</span>" + vm.displayname + "</h5>");
 	 						
 	 	 					$('#displayname').html(vm.displayname);
 	 	 					$('#hostname').html(vm.name);
 	 	 					$('#vmid').html(vm.id);
 	 	 					
							alert(vm.nic.length);

// 							var count = vm.nic.length;
							
//  	 	 					if(count == 1){
//  	 	 						//$('#nic_ipaddress').html(vm.nic.ipaddress);
 	 	 						
//  	 	 					}else if(vm.nic.length > 1){ 
//  	 	 	 					for(i=0; i< count; i++){
// 									alert(vm.nic[i]);	
//  	 	 	 	 				}
//  	 	 					}
 	 	 					
 	 	 					//$('#nic_ipaddress').html(vm.nic.ipaddress);
 	 	 					//$('#nic_netmask').html(vm.nic.netmask);
 	 	 					$('#templatename').html(vm.templatename);
 	 	 					$('#zonename').html(vm.zonename);
 	 	 					$('#serviceofferingname').html(vm.serviceofferingname);
 	 	 					$('#created').html(vm.created);
 	 	 					$('#state').html(vm.state);
 	 					},
 	 					error:function(){
 	 	 					alert('수행실패');
 	 					}
					});
				}
		);

		$('#serverinfoMenu').click(
				function(){
					$('#serverinfoMenu').addClass('active');
					$('#diskinfoMenu').removeClass('active');
					$('#serveractiondiv').show();
					$('#serverinfodiv').show();
					$('#servervolumeinfodiv').hide();
					//selectserver = $('#serverinfodiv').prev('span').find('#selectvmid').text();
				}
		);
			 

		$('#diskinfoMenu').click(
			function(){
				$('#serverinfoMenu').removeClass('active');
				$('#diskinfoMenu').addClass('active');
				$('#serveractiondiv').hide();
				$('#serverinfodiv').hide();
				$('#servervolumeinfodiv').show();
				
				selectvmid = $('#serveractiondiv').prev('span').find('#selectvmid').text();
 
				$.ajax({
	 					type:"GET",
	 					url:"/project2/index.php/volumelist/getVolumes/"+selectvmid,
	 					dataType:'json',
	 					success:function(data){
		 					$('#servervolumeinfo_table tbody').empty();
	 	 					count = data.count;

	 	 					volumes = data.volume;
// 	 	 					$('#result').html(data);
	 	 					
	 	 					for(i=0; i<count;i++){
								volume = volumes[i];

		 	 					if(count==1){
									volume = volumes;
			 	 				}
								 
								datadisk = parseFloat(volume.size) / 1073741824 ;
							 
	 	 						$('#servervolumeinfo_table tbody').append($('<tr></tr>'));
	 	 						lasttr = $('#servervolumeinfo_table tbody tr:last');
	 	 						lasttr.append($('<td></td>').html(i+1));
	 	 						lasttr.append($('<td></td>').html(volume.name));
	 	 						lasttr.append($('<td></td>').html(volume.type));
	 	 						lasttr.append($('<td></td>').html(datadisk+' GB'));
	 	 						lasttr.append($('<td></td>').html(volume.created));
	 	 						 
	 	 					} 
 
	 					},
	 					error:function(){
	 	 					alert('수행실패');
	 					}
				});
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
}); 

 
</script>
<div id='result'></div>
<!-- 서버목록 -->
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12"> 
				<h5>서버목록 (총 <?= $vmcount?>건)</h5>
				<table id="serverlist_table" class="table table-hover" >
					<thead>
						<tr>
							<td>번호</td>
							<td>계정명(displayname)</td>
							<td>운영체제(templatedisplaytext)</td>
							<td>데이터센터(zonename)</td>
							<td>생성시간(created)</td>
							<td>상태(state)</td>
							<td>제어</td>
						</tr>
					</thead>
					<tbody>
		<?php
	 
		for($i = 0; $i < $vmcount; $i ++) {
			if ($vmcount == 1) {
				$vm = $clouds['virtualmachine'];
			} else {
				$vm = $clouds['virtualmachine'][$i];
			}
			 
			echo "<tr><form id='form".$i."' method='post'><td>";
			echo "<input type='hidden' id='vmid' name='vmid' value='" . $vm ['id'] . "'/>";
			echo $i + 1;
			echo "</td><td>";
			echo $vm ['displayname'];
			echo "</td> <td>";
			echo $vm ['templatedisplaytext'];
			echo "</td> <td>";
			echo $vm ['zonename'];
			echo "</td> <td>";
			echo $vm ['created'];
			echo "</td> <td id='state".$i."'>";
			echo $vm ['state'];
			echo "</td> <td>";
			echo "<input class = 'btn' id='".$i."start' type='button' value='시작'/>";
			echo "<input class = 'btn' id='".$i."stop'  type='button' value='정지'/>";
			echo "<input class = 'btn' id='".$i."reboot' type='button' value='재부팅'/>";
			echo "</td></form></tr>";
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