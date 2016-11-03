 <script>
$(
		function(){
			function setModalMsg(msg){ 
				 $('#msgModal #msg').empty();
				 $('#msgModal #msg').html(msg);
				 return $('#msgModal');
			} 
			
			function showLoadingModal(){
				$('#loadingModal').modal({backdrop:"static", keyboard:false}); 
			}

			function async(jobid, processname){
				 showLoadingModal();
				 $.ajax({
					 type:'GET',
					 url:'/asyncProcess/queryAsyncJobResult/'+jobid,
					 dataType:'json',
					 success : function(data){  
						 if(data === 'error'){ 
							 setModalMsg(processname + '실행이 실패하였습니다..(aync)').modal();
						 }
					 },
					 error:function(){ 
						 setModalMsg(processname + '실행이 실패하였습니다..(aync)').modal(); 
					 },
					 complete:function(){
						 $('#loadingModal').modal('hide');
						 window.location.reload();
					 }
				 }); 
			} 
			
			$('#disconnectcpiorderbtn').click(
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
// 	 						alert(vmid);
// 	 						alert(nicid);
	 						
							$.ajax({
								type:'GET',
								url:'/naslist/removeNicFromVirtualMachine/'+nicid+'/'+vmid,
								dataType: 'json',
								success : function(data){
									 jobid = data.jobid; 
									 async(jobid,'DISCONNECT CIP'); 
								},
								error : function( ){  
									 setModalMsg('실행실패!').modal(); 
								}
							}); 
						}); //체크된 것들 모두 disconnect 실행 
					}
			); 
});  
</script>

<!-- disconnect cpi modal  -->
<div class="modal fade" id='disconnectcipModal'role="dialog">
  <div class="modal-dialog">
	  <div class="modal-content">
	      <div class="modal-header"> 
	        <h4 class="modal-title">NAS cpi 연결 해제</h4>
	      </div>
	      <div class="modal-body"> 
	        <table id="disconnectcpiserverlist_table"> 
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
	         <input type="button" class="btn btn-primary" id="disconnectcpiorderbtn"class="btn" value="확인"/>
	      </div>
	    </div> 
	</div>
</div>