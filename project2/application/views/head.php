<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/static/lib/bootstrap/css/bootstrap.min.css"	rel="stylesheet" media="screen">
<link href="/static/lib/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
<link href="/static/css/style.css"	rel="stylesheet"> 
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<script src='/static/js/common.js'></script> 
</head>

<body>

<?php if($this->session->flashdata('message')) { 
	echo "message : ".$this->session->flashdata('message');?>
		<script>
			alert("<?= $this->session->flashdata('message')?>");
		</script>
<?php }?>
 <div class="navbar">
		<div class="navbar-inner">
			<div class="container">
				<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
				
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <!-- response navbar -->
        			<span class="icon-bar"></span>
        			<span class="icon-bar"></span>
        			<span class="icon-bar"></span>
     			</a>
				 
				<!-- Be sure to leave the brand out there if you want it shown --> 
				
      			<a class="brand" href="/">Cloud Console</a>
      			
      			<!-- Everything you want hidden at 940px or less, place within here -->
      			 
      			<div class="nav-collapse collapse">
		        	<?php
		        	if($this->session->userdata('is_login')){
		        	?>
		        	 <div class="dropdown pull-right">
		        		<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						    <?=$_SESSION['nickname']?><span class="glyphicons glyphicons-leaf"></span>님
						  <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						    <li><a href="/auth/logout">로그아웃</a></li>
						    <li><a href="#">Another action</a></li>
						    <li><a href="#">Something else here</a></li>
						    <li role="separator" class="divider"></li>
						    <li><a href="#">Separated link</a></li>
						  </ul>
					</div>
		        	<?php
		        	} else {
		        	?>
			        	<ul class='nav pull-right'>
			        		<li><a href="/auth/login"> <span class="glyphicon glyphicon-star" aria-hidden="true"></span>로그인</a></li>
			        		<li><a href="/auth/register"><span class="glyphicon glyphicon-log-in"></span>회원가입</a></li>
			        	</ul>
		        	<?php
		        	}
		        	?>    
			</div>
		</div>
	</div>
</div>

	<script>
	$(
		function(){
			where = $('#where').text();
			if(where=='cloudlist'){ 
				$('.nav-tabs #cloudlist').css('background-color',' #f4f4f4');
			}else if(where=='network'){
				$('.nav-tabs #network').css('background-color',' #f4f4f4');
			}else if(where=='diskvolume'){
				$('.nav-tabs #diskvolume').css('background-color',' #f4f4f4');
			}else if(where=='nasvolume'){
				$('.nav-tabs #nasvolume').css('background-color',' #f4f4f4');
			}  
		} 
 	);
	</script> 
	 
		<ul class="nav nav-tabs" id='menuTab'  role="tablist" >
		  <li id='cloudlist'> <a href="/cloudlist">클라우드리스트</a></li>
		  <li id="diskvolume" ><a href="/volumelist">디스크볼륨</a></li>
		  <li id='network'  ><a href="/networklist">네트워크</a></li>
		  <li id="nasvolume"><a href="/naslist">NAS 볼륨</a></li>
   		</ul> 
   		
 <!-- 로딩중 띄워주는 모달 -->
<div class="modal fade" id='loadingModal'role="dialog">
    <div class="modal-dialog">  
        <div class="modal-body">
        	<div class='modal-center'>
        	 <img src='/images/circleloading.gif' alt='잠시만..' />
        	 <br><br>
        	 	<h5>잠시만 기다려 주세요</h5>
        	 	<span id='result'></span>
        	</div>
        </div> 
    </div>  
</div>

 <!-- 경고 띄워주는 모달 -->
<div class="modal fade" id='msgModal' role="dialog">
    <div class="modal-dialog">   
        <div class="modal-body">
       		<button type="button" class="close" data-dismiss="modal">&times;</button> 
        	<h3 class='modal-center' id="msg">
        	</h3>
        </div> 
    </div>  
</div> 

   <div class="container-fluid">
	 <div class="row-fluid"> 
	 <span class="glyphicon glyphicon-star" aria-hidden="true">