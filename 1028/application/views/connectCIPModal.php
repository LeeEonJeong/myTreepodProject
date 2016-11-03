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

			
			$('#connectciporderbtn').click(
					function(){
						selectconnectvm = $("input:checkbox[name='selectconnectvm']:checked");
						 
						if(selectconnectvm.length == 0){
							$(this).parents().find('.modal').modal('hide');
							return;
						}
							$(this).parents().find('.modal').modal('hide');
							
							selectconnectvm.each(function(){ //선택된거 모두 연결(각각)
								showLoadingModal();
								vmidandzonecipid = this.value;
								temp = vmidandzonecipid.split('/');
								vmid = temp[0];
								zonecipid = temp[1]; 

// 								alert(vmid);
// 								alert(zonecipid);
								
								$.ajax({
									type:'GET',
									url:'/naslist/addNicToVirtualMachine/'+zonecipid+'/'+vmid,
									dataType: 'json',
									success : function(data){
										 jobid = data.jobid; 
										 async(jobid,'SERVER START'); 
									},
									error : function( ){  
										 setModalMsg('실행실패!').modal(); 
									}
								});
							}); //each
							//$('#loadingModal').modal('hide');
							$(this).parents().find('.modal').modal('hide');
							
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
<div class="modal fade" id='connectcipModal'role="dialog">
  <div class="modal-dialog">
	  <div class="modal-content">
	      <div class="modal-header"> 
	        <h4 class="modal-title">NAS cip 연결</h4>
	      </div>
	      <div class="modal-body"> 
	        <table id="connectcipserverlist_table"> 
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
	         <input type="button" class="btn btn-primary" id="connectciporderbtn"class="btn" value="확인"/>
	      </div>
	    </div> 
	</div>
</div>