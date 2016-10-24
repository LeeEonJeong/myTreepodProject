 <script>
$(
		function(){ 
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
									url:'/project2/index.php/naslist/addNicToVirtualMachine/'+zonecipid+'/'+vmid,
									dataType: 'json',
									success : function(data){
										jobid = data.jobid;  
										showLoadingModal();
										 $.ajax({
		 									 type:'GET',
		 									 url:'/project2/index.php/asyncProcess/queryAsyncJobResult/'+jobid,
		 									 dataType:'json',
		 									 success : function(data){ 
		 										 //location.href='/project2/index.php/naslist';
		 											 
		 									 },
		 									 error:function(){ 
		 										 alert('실행실패');
		 									 },
		 									 complete:function(){
		 										$('#loadingModal').modal('hide');
		 									 }
		 								 });
										 setModalMsg('모두 연결이 완료되었습니다.').modal(); 
									},
									error : function( ){ 
										alert('실행실패');
									}
								}); //ajax
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
<div class="modal fade" id='connectCPIModal'role="dialog">
  <div class="modal-dialog">
	  <div class="modal-content">
	      <div class="modal-header"> 
	        <h4 class="modal-title">NAS CIP 연결</h4>
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