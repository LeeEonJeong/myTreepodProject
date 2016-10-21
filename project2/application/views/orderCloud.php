 <script>
$(
	//존위치에 따라 패키지 선택지 가져오기
	function(){ 
		$("#zonename").change( 
			function(){ 
			    	zoneid = $('#zonename option:selected').val();
			    	$('#package').empty(); //내용비움
			    	
				    $.ajax({
						type : "POST",
						url: '/project2/index.php/orderCloud/getPackagesByZoneid',
						data : {'zoneid' : zoneid},
						datatype : 'json',
						success : function(data)
						{ 
							var obj = jQuery.parseJSON(data);
//								alert(obj[1]);
//								alert(obj.length);
							
							for(var i=0; i<obj.length; i++){ 
								$('#package ').append(
									$('<input/>').attr({
												type:'button',
												id:obj[i],
												value:obj[i],
												name:'packagebtn',
												class:'btn',			 
									})
								); 
								 
								$('#'+obj[i]).click(
									function(){
										$('#os').empty();
										$('#datadisk').empty();
 
										producttype = getProductType($(this).val());
										$('#producttype').text(producttype);
										
										$.ajax({
											type : "POST",
											url: '/project2/index.php/orderCloud/getOSlist',
											data : {'package' : $(this).val(), 'zoneid' : zoneid},
											datatype : 'json',
											success : function(oslist)
											{
												var obj = jQuery.parseJSON(oslist);
												
												$('#os').append("<option>운영체제를 선택하세요. </option>");
												for(i=0; i<obj.length; i++){
													$('#os').append("<option value='"+obj[i]+"'>"+obj[i]+"</option>");
												}
											},
											error : function( ){  
												alert('실행실패'); 
											}
										}); 				 
								});
							}//for문
						},
						error : function( ){  
							alert('실행실패');
							$('#package').empty(); //내용비움
						}
					});
			});//zonename change될때
				
		$("#os").change(
				function(){
					$.ajax({
						type : "POST",
						url: '/project2/index.php/orderCloud/getDatadisklist',
						data : {'package' : $(this).val(), 'zoneid' : zoneid, 'os' : $('#os option:selected').val()},
						datatype : 'json',
						success : function(disklist)
						{
							$('#datadisk').empty();
							 
							var obj = jQuery.parseJSON(disklist);
							$('#datadisk').append("<option>서버사양 선택하세요. </option>");
							for(i=0; i<obj.length; i++){
								$('#datadisk').append("<option value='"+obj[i]+"'>"+obj[i]+"</option>");
							}
						},
						error : function( ){  
							alert('실행실패'); 
						}
					});
				}
		);

			
	$('#servername :button').click(function(){
		checkname = $('#servername td input:text').val(); 
		$.ajax({
			type : 'GET',
			url : "/project2/index.php/orderCloud/checkVirtualMachineName/"+ checkname,
			success : function(data){ 
				if(data.toLowerCase().trim() === 'true')
					result = true;
				else
					result = false; 
				
				resulttext = $('#servername td:last');
				if(result){
					resulttext.text('사용할 수 있는 서버명입니다.');
					resulttext.css('color','green');
				}else{
					resulttext.text('사용할 수 없는 서버명입니다.');
					resulttext.css('color','red');
				} 
			},
			error : function(){
				alert('실행실패');
			}
		});
	});

	$('#orderbtn').click(function(){
		servername = $('#servername td input:text').val();
		hostname = $('#hostname td input:text').val();
		zoneid = $('#zonename option:selected').val();
		producttype = $('#producttype').text();
		os = $('#os option:selected').val();
		datadisk = $('#datadisk option:selected').val();
	 	rootonly = $(':radio[name="rootonly"]:checked').val();
	 	usageplantype = $(':radio[name="usageplantype"]:checked').val();
 	
	 	if(rootonly.toLowerCase().trim() === 'true')
	 		rootonly = true;
		else
			rootonly = false;
 
		if(rootonly){
			productcode = [producttype, os, datadisk, "rootonly"].join("_");
		}else{
			productcode = [producttype, os, datadisk].join("_");
		}

// 		alert(productcode);
// 		alert(producttype);
// 	 	alert(servername);
// 	 	alert(hostname);
// 	 	alert(zoneid);
// 	 	alert(os);
// 	 	alert(datadisk);
// 	 	alert(servername);
// 	 	alert(rootonly);
// 	 	alert(usageplantype);

		orderdata = 
		{
			'displayname' : servername,
			'name' : hostname,
			'zoneid' : zoneid,
			'productcode' : productcode,
			'usageplantype' : 	usageplantype
		}

		showObj(orderdata);
			
	 	$.ajax({
			type : "POST",
			url: '/project2/index.php/orderCloud/orderVM',
			data : orderdata,
			//datatype : 'json',
			success : function(data)
			{
				$('#result').html(data);
			 	alert('실행성공');
			},
			error : function( ){  
				alert('실행실패'); 
			}
		}); 				 
	 	
	});
		
	function showObj(obj){
		var str="";
		for(key in obj){
			str  += key+"="+obj[key]+"\n";
		}
		alert('showObj\n'+str);
		return;
	}

	 function getProductType(name){
			switch(name){
				case 'Standard':
					reducename= 'std';
					break;
				case 'HighMemory':
					reducename = 'high';
					break;
				case 'SSD':
					reducename = 'ssd';
					break;
				default:
					reducename = 'std';
			}
			return reducename;
	}
});  
</script>
<div id='result'class="span12"></div>
			<h3>서버생성</h3>
			<hr>
			 
			   	<table>
			   		<tr id="servername">
			   			<td>서버명</td>
			   			<td colspan="2"><input type="text" id="servername" /></td>
			   			<td><input type="button" class='btn' value="중복확인"/></td>
			   			<td></td>
			   		</tr>
			   		<tr id="hostname">
			   			<td>호스트명</td>
			   			<td colspan="2"><input type="text"/></td>
			   			<td></td>
			   			<td></td>
			   		</tr>
			   		<tr>
			   			<td>위치</td>
			   			<td>
			   				<select id='zonename'>
			   					<option>존을 선택하세요.</option>
								<option value="eceb5d65-6571-4696-875f-5a17949f3317">KOR-Central A</option>
								<option value="9845bd17-d438-4bde-816d-1b12f37d5080">KOR-Central B</option>
								<option value="dfd6f03d-dae5-458e-a2ea-cb6a55d0d994">KOR-HA</option>
								<option value="95e2f517-d64a-4866-8585-5177c256f7c7">KOR-Seoul M</option>
								<option value="3e8ce14a-09f1-416c-83b3-df95af9d6308">JPN</option>
								<option value="b7eb18c8-876d-4dc6-9215-3bd455bb05be">US-West</option>
							</select>
			   			</td>
			   			<td></td>
			   			<td></td>
			   		</tr>
			   		<tr>
			   			<td>패키지 선택(상품 종류)</td>
				   		<td id='package'></td>
				   		<td colspan="2"><span id='producttype' style='display:none'></span></td>
			   		</tr>
			   		<tr>
			   			<td>운영체제 선택</td>
				   		<td  colspan="3">
				   		 	<select id='os'> 
				   		 	</select>
				   		</td>
			   		</tr>
			   		<tr>
			   			<td>서버사양 선택</td>
				   		<td colspan="3">
				   		 	<select id='datadisk'> 
				   		 	</select>
				   		</td>
			   		</tr>
			   		<tr>
			   			<td>데이터디스크</td>
			   			<td>
				   			<label class="radio-inline">
				   				<input type="radio" name="rootonly" id="plusdatadisk" value="false" > 제공
				   			</label>
							<label class="radio-inline">
								<input type="radio" name="rootonly" id="rootonly"value="true">미제공
							</label>
				   		</td>
			   			<td>요금제 선택</td>
				   		<td>
					   		<label class="radio-inline">
					   			<input type="radio" name="usageplantype" id="hourly" value="hourly" >시간요금제
					   		</label>
							<label class="radio-inline">
								<input type="radio" name="usageplantype" id="montly"value="monthly">월요금제
							</label>
				   		</td>
			   		</tr>
			   		<tr>
			   			<td><a href="./cloudlist" class="btn">취소</a></td>
			   			<td><input type="button" id="orderbtn"class="btn" value="신청"/></td>
			   		</tr>
			 	</table>  
		</div>
	</div>
</div>