<?php include 'connectCPIModal.php'?>
<?php include 'disconnectCPIModal.php'?>

<span style="display:none" id="where" >nasvolume</span>

<!--  NAS볼륨 검색하기 -->
	<div class="container-fluid">
		<div class="row-fluid" >
			<div class="span7"  >
				<h5>NAS볼륨 검색하기</h5>
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
							placeholder="검색할  NAS볼륨명을  입력해 주세요." />
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
					<a class="btn btn-primary" href="naslist/orderNas">NAS생성</a> 
			</div> 
			</div>
		</div>
	</div>

<script>
$( 
//-----------------------------
		function (){ 
			$('#nasdiv').hide();
			$('#connectservermanagediv').hide();
			$('#nasdiv').prev('span').text('NAS볼륨을 선택해 주세요.');
 
			$('#nasvolumelist_table tr').click(
					function(){
						$('#nasdiv').prev('span').hide();
						$('#nasdiv').show();
						$('#connectservermanagediv').hide();
						$('#nasvolumeinfodiv').prev('span').remove();
						$('#nasvolumeinfoMenu').addClass('active');
						$('#connectserverManageMenu').removeClass('active');
						 
						
						$('#selectnasvolume').html($(this).find('#nasvolumename').text());

						nasvolumeid = $(this).find('span:first').text();

						$('#nasselectvolumeid').html(nasvolumeid);
 
						$.ajax({
							type:"GET",
	 	 					url:"/project2/index.php/naslist/searchNas/"+nasvolumeid,
	 	 					dataType:'json',
	 	 					success:function(data){
	 	 	 					nas = data.response;
	 	 	 					
	 	 	 					$('#name').html(nas.name);
	 	 	 					$('#nasvolumeinfo_table #nasId').html(nas.id);
	 	 	 					$('#nasvolumeinfo_table #name').html(nas.name);
	 	 	 					$('#nasvolumeinfo_table #volumetype').html(nas.volumetype);
	 	 	 					$('#nasvolumeinfo_table #totalsize').html(nas.totalsize / 1073741824);
	 	 	 					$('#nasvolumeinfo_table #created').html(nas.created);
	 	 	 					$('#nasvolumeinfo_table #usageplantype').html(nas.usageplantype);
	 	 	 					$('#nasvolumeinfo_table #filesused').html(nas.filesused);
	 	 	 					$('#nasvolumeinfo_table #filestotal').html(nas.filestotal);
	 	 	 					$('#nasvolumeinfo_table #ipslashpath').html(nas.ip+"/"+nas.path);
	 	 	 					  
	 	 					},
	 	 					error:function(){
	 	 	 					alert('수행실패');
	 	 					}
						});
					}
			);
			 

			$('#deletenasvolumebtn').click(
					function(){
						nasvolumeid = $('#nasId').html();

						$.ajax({
							type:'GET',
							url:'/project2/index.php/naslist/deleteVolume/'+nasvolumeid,
							dataType: 'json',
							success : function(data){ 
								 window.location.href='/project2/index.php/naslist'; 
							},
							error : function( ){ 
								alert('실행실패');
							}
						}); //ajax
					}
			);

			$('#nasvolumeinfoMenu').click(
					function(){
						$('#connectserverManageMenu').removeClass('active');
						$(this).addClass('active');
						$('#connectservermanagediv').hide();
						$('#nasdiv').show();
					 
					}
			);

			$('#connectserverManageMenu').click(
					function(){
						$('#nasdiv').prev('span').hide();
						
						$('#nasvolumeinfoMenu').removeClass('active');
						$(this).addClass('active');
						$('#nasdiv').hide();
						$('#connectservermanagediv').show();

						$('#connectserverinfo_table tbody').empty();
						$.ajax({
							type:'GET',
							url:'/project2/index.php/cloudlist/getVMsForNAS',
							dataType: 'json',
							success : function(data){ 
								
								for(i=0; i<data.length; i++){
								 vm = data[i];
								 if(vm.useNas){
									 use = '사용중'
								 }else{
									 use = '-'
								 }
								  
								 $('#connectserverinfo_table tbody').append($('<tr></tr>'))
								 	.append(
											 $('<td></td>').html(use),
											 $('<td></td>').html(vm.zonename),
											 $('<td></td>').html(vm.displayname),
											 $('<td></td>').html(vm.templatedisplaytext),
											 $('<td></td>').html(vm.serviceofferingname),
											 $('<td></td>').html(vm.state)
									);
								}
							},
							error : function( ){ 
								alert('실행실패');
							}
						}); //ajax
					 
					}
			);

			$('#connectcipbtn').click(
					function(){
						$.ajax({
							type:'GET',
							url:'/project2/index.php/cloudlist/getVMsForNAS',
							dataType: 'json',
							success : function(data){ 
								$('#connectCPIModal #connectcipserverlist_table tbody').empty();
								$count = 0; 
								for(i=0; i<data.length; i++){
									 vm = data[i]; 
									 
									 if( vm.state == 'Running' && vm.useNas == false){
										$count++;
									 	$('#connectCPIModal #connectcipserverlist_table tbody').append($('<tr></tr>'))
									 	.append(
												 $('<td></td>').html("<input type='checkbox' name='selectconnectvm' value= '"+ vm.vmid + "/" + vm.zonecipid + "'/>"), 
												 $('<td></td>').html(vm.zonename),
												 $('<td></td>').html(vm.displayname),
												 $('<td></td>').html(vm.templatedisplaytext),
												 $('<td></td>').html(vm.serviceofferingname),
												 $('<td></td>').html(vm.state),
												 $('<td></td>').html('')
										);
									}
								} 
								if($count == 0){
									setModalMsg('현재 CIP에 연결할 서버가 없습니다.').modal();
								}else{ 
									$('#connectCPIModal').modal();
								}
							},
							error : function( ){ 
								alert('실행실패');
							}
						}); //ajax
					}
			);

			$('#disconnectcipbtn').click(
					function(){
						$.ajax({
							type:'GET',
							url:'/project2/index.php/cloudlist/getVMsForNAS',
							dataType: 'json',
							success : function(data){
								//alert(data.length);
								$('#disconnectCPIModal #disconnectcipserverlist_table tbody').empty();

								$useNasCount = 0;
								for(i=0; i<data.length; i++){
									 vm = data[i]; 
									 if(vm.useNas == true && vm.state=='Running'){
										 $useNasCount++;
									 	$('#disconnectCPIModal #disconnectcipserverlist_table tbody').append($('<tr></tr>'))
									 	.append(
												 $('<td></td>').html("<input type='checkbox' name='selectdisconnectvm' value= '"+ vm.vmid + "/" + vm.nicid + "'/>"), 
												 $('<td></td>').html(vm.zonename),
												 $('<td></td>').html(vm.displayname),
												 $('<td></td>').html(vm.templatedisplaytext),
												 $('<td></td>').html(vm.serviceofferingname),
												 $('<td></td>').html(vm.state),
												 $('<td></td>').html('')
										);
									}
								}
								if($useNasCount == 0){
									setModalMsg('현재 CIP에 연결된 서버가 없습니다.').modal();
								}else{
									$('#disconnectCPIModal').modal();
								}
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
				<h5>NAS볼륨 목록 (총 <?= $nasvolumeCount?>건)</h5>
				<table class="table table-hover" id="nasvolumelist_table">
					<thead>
						<tr>
							<td></td>
							<td>zone</td>
							<td>볼륨명</td>
							<td>신청용량(GB)</td>
							<td>현재 사용량(GB)</td>
							<td>프로토콜</td>
						</tr>
					</thead>
					<tbody>
						<?php 
						for($i = 0; $i < $nasvolumeCount; $i ++) {
							if ($nasvolumeCount == 1) {
								$nasvolume = $nasVolumes;
							} else {
								$nasvolume = $nasVolumes[$i];
							}
							
							$totalsize = $nasvolume['totalsize'] / 1073741824;
							$usedsize = $nasvolume['usedsize'] / 1073741824;
					 
							echo "<tr><td>";
							echo "<span style='display:none' id='nasvolumeid'>" . $nasvolume ['id'] . "</span>";
							echo $i + 1;
							echo "</td><td id='zonename'>";
							echo "<span style='display:none' id='zoneid'>" . $nasvolume ['zonename'] . "</span>";
							echo $nasvolume ['zonename']; //zonename
							echo "</td><td id='nasvolumename'>";
							echo $nasvolume ['name'];
							echo "</td> <td>";
							echo $totalsize;
							echo "</td> <td>";
							echo $usedsize ; 
							echo "</td> <td>";
							echo $nasvolume['volumetype'] ; 
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