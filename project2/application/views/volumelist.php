
<span style="display:none" id="where" >diskvolume</span>
<?php 
	include 'orderVolumeModal.php';
?>
<!-- 디스크 신청 모달 -->

<!--  네트워크검색하기 -->
	<div class="container-fluid">
		<div class="row-fluid" >
			<div class="span7"  >
				<h5>디스크 검색하기</h5>
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
							placeholder="검색할 디스크명을  입력해 주세요." />
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
					<a class="btn btn-primary" data-toggle="modal" data-target="#ordereVolumeModal">Disk 추가 신청</a> 
			</div> 
			</div>
		</div>
	</div>

<script>
$( 
//-----------------------------
		function (){ 
			$('#volumeactiondiv').hide();
			$('#volumeinfodiv').hide();
			$('#volumeactiondiv').prev('span').text('디스크를 선택해 주세요.');
 
			$('#volumelist_table tr').click(
					function(){
						$('#volumeactiondiv').prev('span').hide();
						$('#volumeactiondiv').show();
						$('#volumeinfodiv').show();
						$('#volumeinfodiv').prev('span').remove();
						$('#volumeinfoMenu').addClass('active');
						$('#selectvolume').html($(this).find('#volumename').text());

						volumeid = $(this).find('span:first').text(); 
						$('#selectvolumeid').html(volumeid);

						 
						$.ajax({
							type:"GET",
	 	 					url:"/project2/index.php/volumelist/searchVolume/"+volumeid,
	 	 					dataType:'json',
	 	 					success:function(data){
	 	 	 					volume = data.volume;
								type = volume.type;
								 
								if(type == 'ROOT'){ 
									$('#volumeactiondiv #isAdditional').html('false');
								}else{
									type = volume.diskofferingdisplaytext;
									if(type.indexOf('Additional') >= 0) //0이곘징
										$('#volumeactiondiv #isAdditional').html('true');
									else
										$('#volumeactiondiv #isAdditional').html('false'); 
								} 
	 	 	 					
	 	 	 					$('#name').html(volume.name);
	 	 	 					$('#volumeinfo_table #volumeid').html(volume.id);
	 	 	 					$('#disksize').html(volume.size / 1073741824 + ' GB');
	 	 	 					$('#created').html(volume.created);
	 	 	 					$('#volumeinfo_table #zoneid').html(volume.zoneid);
	 	 	 					if(typeof(volume.vmname) === 'undefined'){
	 	 	 						$('#state').html('분리');
	 	 	 						$('#vmdisplayname').html('-');
	 	 	 						$('#connectserverbtn').button('reset');
	 	 	 						$('#detachserverbtn').button('loading').val('서버연결해제');
	 	 	 						$('#deletevolumebtn').button('reset');
	 	 	 					}else{
	 	 	 						$('#state').html('연결');
	 	 	 						$('#vmdisplayname').html(volume.vmdisplayname);
	 	 	 						
	 	 	 						$('#connectserverbtn').button('loading').val("서버연결");
	 	 	 						$('#detachserverbtn').button('reset');
	 	 	 						$('#deletevolumebtn').button('loading').val('삭제');
	 	 	 					} 
		 	 	 					
	 	 					},
	 	 					error:function(){
	 	 	 					alert('수행실패');
	 	 					}
						});
					}
			);
			
			//모달띄우기
			$('#connectserverbtn').click(
					function(){
						volumename = $('#selectvolume').html();
						volumeid = $('#selectvolumeid').html();
						zoneid = $('#volumeinfo_table #zoneid').html();

						$('#connectServerModal #volumename').html('<strong>'+volumename+'</strong>');
						$('#connectServerModal #volumeid').html(volumeid);
 
						//asdf
						//같은존에 있는 VM가져오기
						$.ajax({
							type:'GET',
							url:'/project2/index.php/cloudlist/getVMsByZoneId/'+zoneid,
							dataType: 'json',
							success : function(data){
								//connectServerModal 보여주기
								//먼저 서버리스트에 있는 거 지우고
								selectserverlist = $('#connectServerModal #serverlist');
								selectserverlist.empty();

								count = data.count;
								vms = data.virtualmachine;
								 
								for(i=0; i<count; i++){
									if(count==1){
										vm=vms;
									}else{
										vm=vms[i];
									}
									selectserverlist.append(
											$('<option>/option').attr({
												'value' : vm.displayname,
												'id' : vm.id
											}).html(vm.displayname)
									);
								}

								$('#connectServerModal').modal();
							},
							error : function( ){  
								alert('실행실패');
							}
						});

					}
			);

			$('#connectServerModalBtn').click( 
				function(){  
					volumeid = $('#connectServerModal #volumeid').html();
					vmid = $('#connectServerModal #serverlist option:selected').attr('id'); 

					if(typeof(volumeid) === 'undefined' || typeof(vmid) === 'undefined'){
						setModalMsg('디스크를 선택하지 않으셨습니다.').modal();
					}					
					else{
						$('#connectServerModal').modal('hide');  
						//서버연결하기
						showLoadingModal();
						
						$.ajax({
							type:'GET',
							url:'/project2/index.php/volumelist/attachVolume/'+volumeid+'/'+vmid,
							dataType: 'json',
							success : function(data){
								 jobid = data.jobid;
								 
								 $.ajax({
									 type:'GET',
									 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
									 dataType:'json',
									 success : function(data){ 
										 $('#loadingModal').modal('hide');  
										 //if(data.jobstatus==2){ 
										 window.location.href='/project2/index.php/volumelist';
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
								alert('실행실패');
							}
						});
					}
					 
				}		
			);

			$('#detachserverbtn').click(
					function(){
						volumeid = $('#selectvolumeid').html();
						//$('#loadingModal').modal({backdrop:"static", keyboard:false});
						
						//기본제공 디스크는 삭제 안됨
						isAddtional = $('#volumeactiondiv #isAdditional').html(); 
						
						if(isAddtional == 'false'){
							setModalMsg('기본제공되는 디스크는 서버연결해제 및 삭제가 불가합니다.').modal();
						}else{
							//서버연결해제하기
							//서버정지 안되어 있으면 안된다고 알려라 
							$.ajax({
								type:'GET',
								url:'/project2/index.php/volumelist/searchVolume/'+volumeid,
								dataType: 'json',
								success : function(data){
											 state = data.volume.vmstate;
											 if(state.trim() == 'Running'){  
												 setModalMsg('서버를 멈추고 실행하세요.').modal(); 
											 }else if(state == 'Stopped'){ 
												//서버가 정지해 있으면 가능
												 	showLoadingModal();
													$.ajax({
														type:'GET',
														url:'/project2/index.php/volumelist/detachVolume/'+volumeid,
														dataType: 'json',
														success : function(data){
															 jobid = data.jobid;
															 $.ajax({
																 type:'GET',
																 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
																 dataType:'json',
																 success : function(data){
																	 $('#loadingModal').modal('hide'); 
																	 //alert(data.jobstatus); 
																	 //if(data.jobstatus==2){
																	
																	 window.location.href='/project2/index.php/volumelist';
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
															alert('실행실패');
														}
													}); //ajax
											 }//else if
									},//success 
								error : function( ){  
									alert('실행실패');
								}
							});//ajax 
						}//else
					}); //click

			$('#deletevolumebtn').click(
					function(){
						volumeid = $('#selectvolumeid').html();
						isAddtional = $('#volumeactiondiv #isAdditional').html(); 
						if(isAddtional == 'false'){
							setModalMsg('기본제공되는 디스크는 서버연결해제 및 삭제가 불가합니다.').modal();
						}else{
							$.ajax({
								type:'GET',
								url:'/project2/index.php/volumelist/deleteVolume/'+volumeid,
								dataType: 'json',
								success : function(data){
									
									 window.location.href='/project2/index.php/volumelist'; 
								},
								error : function( ){ 
									alert('실행실패');
								}
							}); //ajax
						}
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
<div id='result'></div>


  <!-- 서버 연결 Modal -->
  <div class="modal fade" id="connectServerModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">서버 연결</h4>
        </div>
        <div class="modal-body"> 
        	<p>
      	  		<span id='volumename'></span> 
	          	<span id='volumeid' style='display: none'></span> 
          		스토리지에 연결할 클라우드 서버를 선택해 주세요.
       		</p>
       		<br>
          	<p> 서버명 :  <select id = 'serverlist' ></select>
	        <p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
          <button type='button' id ='connectServerModalBtn' class='btn btn-primary'>연결하기</button>
        </div>
      </div>
      
    </div>
  </div>


 <!-- 볼륨목록-->
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12" >
				<h5>디스크목록 (총 <?= $volumeCount?>건)</h5>
				<table class="table table-hover" id="volumelist_table">
					<thead>
						<tr>
							<td></td>
							<td>Disk명</td>
							<td>용량</td>
							<td>위치</td>
							<td>상태</td>
							<td>적용서버</td>
							<td>타입</td>
						</tr>
					</thead>
					<tbody>
						<?php
					 
						for($i = 0; $i < $volumeCount; $i ++) {
							if ($volumeCount == 1) {
								$nasvolume = $volumes['volume'];
							} else {
								$nasvolume = $volumes['volume'][$i];
							}
							
							$disksize = $nasvolume['size'] / 1073741824;
					 
							echo "<tr><td>";
							echo "<span style='display:none' id='volumeid'>" . $nasvolume ['id'] . "</span>";
							echo $i + 1;
							echo "</td><td id='volumename'>";
							echo $nasvolume ['name'];
							echo "</td> <td>";
							echo $disksize.'GB';
							echo "</td> <td>";
							echo $nasvolume ['zonename'];
							echo "<span style='display:none' id='zoneid'>" . $nasvolume ['zoneid'] . "</span>";
							echo "</td> <td>";
							if(isset($nasvolume['vmname'])){
								echo '연결';
							}else{
								echo '분리';
							}
							echo "</td> <td>";		 
							if(isset($nasvolume['vmname'])){ 				
								echo $nasvolume ['vmdisplayname']; 
							}else{
								 
							}
							echo "</td> <td id='volumetype".$i."'>";
							$typetext = $nasvolume['type']; 
							echo $typetext;
							
							if($typetext == 'DATADISK'){ 
								if(strpos($nasvolume['diskofferingdisplaytext'], 'Additional') !== false) {
									echo "(추가)";
								} 
							} 
							echo "</td></tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<!-- 볼륨상세 정보 -->
<div class="container-fluid">
	 <div class="row-fluid"> 