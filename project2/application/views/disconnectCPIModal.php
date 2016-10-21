 <script>
$(
		function(){ 
			$('#disconnectciporderbtn').click(
					function(){
						selectdisconnectvm = $("input:checkbox[name='selectdisconnectvm']:checked");
// 						alert(selectdisconnectvm.length);
						
						if(selectdisconnectvm.length == 0){
							$(this).parents().find('.modal').modal('hide');
							return;
						} 
						$(this).parents().find('.modal').modal('hide');
						setModalMsg('연결해지 되는 데 약간의 시간이 걸립니다.').modal();	
						
						selectdisconnectvm.each(function(){ 
							vmidandnicid = this.value;
							temp = vmidandnicid.split('/');
							vmid = temp[0];
							nicid = temp[1]; 
	 						alert(vmid);
	 						alert(nicid);
	 						
// 							$.ajax({
// 								type:'GET',
// 								url:'/project2/index.php/naslist/removeNicFromVirtualMachine/'+nicid+'/'+vmid,
// 								dataType: 'json',
// 								success : function(data){
// 									showLoadingModal();
// 									jobid = data.jobid; 
// 									 $.ajax({
// 	 									 type:'GET',
// 	 									 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
// 	 									 dataType:'json',
// 	 									 success : function(data){  
// 	 										 //location.href='/project2/index.php/naslist';
// 	 									 },
// 	 									 error:function(){ 
// 	 										 alert('실행실패');
// 	 									 },
// 	 									 complete:function(){
// 	 										$('#loadingModal').modal('hide');
// 	 									 }
// 	 								 });
// 								},
// 								error : function( ){ 
// 									alert('실행실패');
// 								}
// 							}); //ajax
						}); //체크된 것들 모두 disconnect 실행 
			});
 

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

<!-- disconnect cip modal  -->
<div class="modal fade" id='disconnectCPIModal'role="dialog">
  <div class="modal-dialog">
	  <div class="modal-content">
	      <div class="modal-header"> 
	        <h4 class="modal-title">NAS CIP 연결 해제</h4>
	      </div>
	      <div class="modal-body"> 
	        <table id="disconnectcipserverlist_table"> 
			   		<thead>
				   		<tr>
				   			<th>선택</th>
				   			<th>Zone</th>
				   			<th>서버명</th>
				   			<th>운영체제</th>
				   			<th>스펙</th>
				   			<th>상태</th>
				   			<th>그룹</th>
				   		</tr>
			   		</thead>
			   		<tbody>
			   		</tbody>
			 	</table>  
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	         <input type="button" class="btn btn-primary" id="disconnectciporderbtn"class="btn" value="확인"/>
	      </div>
	    </div> 
	</div>
</div>