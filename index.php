<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Welcome to Migration Wizard</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.2.1/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<section class="container pt-4">
	<h2 class="text-center">Database migration from VTiger 5.0.4 to 7.1.0</h2>
	<h4 class="text-center pt-4">All preparations done. Click 'Start' to start migration.</h4>
	<div class="row">
		<div class="col-md-12">
			<button class="btn btn-lg btn-primary" style="margin-top: 30px; font-size: 32px;" id="start">
				<i class="fa fa-play"></i> Start
			</button>
			<h4 class="text-center pt-4" style="display: none;" id="display">Migration started. Please wait...</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="col-md-3 control-label" for="name">Start from</label>
				<div class="col-md-9">
					<select class="form-control" style="max-width: 250px;">
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="row pt-3">
		<div class="col-md-12">
			<span>Processing table: </span><span style="font-style: italic; color: blue;" id="active"></span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="progress">
				<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="sub">0%</div>
			</div>
		</div>
	</div>
	<div class="row pt-2">
		<div class="col-md-12">
			<span>Total progress: </span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="progress">
				<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="total">0%</div>
			</div>
		</div>
	</div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.2.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
	var dbnames = new Array("crmentity", "account", "accountbillads", "accountscf", "accountshipads", "accounttype", "contactaddress", "contactdetails", "contactscf", "contactsubdetails", "productcategory", "productcf", "products", "notes", "cvadvfilter", "cvcolumnlist", "cvstdfilter", "customview", "cvcolumnlist", "user2role", "users");
	var selected, completed, lastprogress;

	$(document).ready(function() {
		var w1 = parseInt($("section").css("width")) - 50;
		var w2 = parseInt($("#start").css("width"));
		$("#start").css("margin-left", (w1 - w2) / 2 + "px");
		for (var i = 0; i < dbnames.length; i++)
			$("select").append("<option value='" + i + "'>vtiger_" + dbnames[i] + "</option>");
	});

	$("#start").click(function() {
		$("#start").fadeOut("fast", function() {
			$("#display").fadeIn("fast");
			selected = parseInt($("select").val());
			completed = 0;
			process(selected);
		});
	});

	function process(index) {
		$("#active").text("vtiger_" + dbnames[index]);
		var formData = new FormData();
		formData.append("name", dbnames[index]);

		lastprogress = index / (dbnames.length - 1);

		if (selected == index)
			getProgress();

		$.ajax({
			async: true,
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			type: "POST",
			url: "migration.php",
			xhr: function(){
				var xhr = $.ajaxSettings.xhr() ;

				xhr.upload.onprogress = function(event) {

				};

				xhr.upload.onload = function(){

				};

				return xhr ;
			}, success: function(data) {
				var nindex = index + 1;
				if (nindex < dbnames.length)
					process(nindex);
				else {
					completed = 1;
					$("#display").text("Migration completed!");
				}
			}, error: function(error) {
				alert("Error");
			}
		});
	}

	//Start receiving progress
    function getProgress(){
        $.ajax({
            url: 'progress.php',
            async: true,
            cache: false,
			contentType: false,
			processData: false,
            success: function(data) {
            	data = $.parseJSON(data);
            	var position = parseInt(data.progress);
            	var total = parseInt(data.total);

                var p_sub = 0, p_total = 0;

				p_sub = Math.floor(position * 100 / total);
				p_total = ((position / (total * (dbnames.length - 1)) + lastprogress) * 100).toFixed(2);

				$("#sub").css("width", p_sub + "%");
				$("#sub").text(p_sub + "%");
				$("#total").css("width", p_total + "%");
				$("#total").text(p_total + "%");

				if (completed == 0)
					getProgress();
            }
        });
    }
</script>
</body>
</html>