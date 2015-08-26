<?php
require_once ('../login/StartSession.php');
if (! isset($_SESSION['user_id'])) {
    header("refresh:1;url=../login/login.php");
    exit();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8" />
<title>Location</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
            $(document).ready(function(){
                function loading_show(){
                    document.getElementById('load').style.visibility='visible';
                    //$('#loading').html("<img src='../images/loading.gif'/>").fadeIn('fast');
                }
                function loading_hide(){
                    document.getElementById('load').style.visibility='hidden';
                    //$('#loading').fadeOut('fast');
                }                
                function loadData(page){
                    loading_show();                    
                    var posting = $.post( "load.php", { "page": page } );
			posting.done(function(msg) {
					loading_hide();
					$("#container").html(msg);
			});
                }
                loadData(1);  // For first time page load default results
                $('#container').on('click','li.active',function(){
                    var page = $(this).attr('p');
                    loadData(page);
                    
                });           
                $('#container').on('click','.go_button',function(){
                    var page = parseInt($('.goto').val());
                    var no_of_pages = parseInt($('.total').attr('a'));
                    if(page != 0 && page <= no_of_pages){
                        loadData(page);
                    }else{
                        alert('Enter a PAGE between 1 and '+no_of_pages);
                        $('.goto').val("").focus();
                        return false;
                    }
                    
                });
            });
        </script>

<style type="text/css">
body {
	margin: 0;
	padding: 0;
}

#container {
	width: 600px;
	margin: 0 auto 30px;
}

#container .pagination ul li.inactive, #container .pagination ul li.inactive:hover
	{
	background-color: #ededed;
	color: #bababa;
	border: 1px solid #bababa;
	cursor: default;
}

#container .data ul li {
	list-style: none;
	margin: 5px 0 5px 0;
	color: #000;
	font-size: 13px;
}

#container .pagination {
	width: 600px;
	height: 25px;
}

#container .pagination ul li {
	list-style: none;
	float: left;
	border: 1px solid #006699;
	padding: 2px 6px 2px 6px;
	margin: 0 3px 0 3px;
	font-family: arial;
	font-size: 14px;
	color: #006699;
	font-weight: bold;
	background-color: #f2f2f2;
}

#container .pagination ul li:hover {
	color: #fff;
	background-color: #006699;
	cursor: pointer;
}

.go_button {
	background-color: #f2f2f2;
	border: 1px solid #006699;
	color: #cc0000;
	padding: 2px 6px 2px 6px;
	cursor: pointer;
	position: absolute;
	margin-top: -1px;
}

.total {
	float: right;
	font-family: arial;
	color: #999;
}

.tag {
	bgcolor: yellow;
}

.detail {
	bgcolor: pink;
}
</style>

</head>
<body>
	<div style="height: 20px;"></div>
	<div id="container">
		<div class="data"></div>
		<div class="pagination"></div>
	</div>
	<div id="loading"
		style="position: relative; top: -60px; left: 180px; z-index: -1;">
		<div id="load" style="visibility: hidden;"></div>
	</div>

</body>
<?php include_once("../analyticstracking.php")?>
</html>
