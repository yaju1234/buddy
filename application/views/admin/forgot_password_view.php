<div class="email-verify">
	<div class="block_verify">
		<div class="form-signin-heading"><img src="<?=base_url()?>/images/logo_white.png"/></div>
		<div class="containt-wrap">
	 <p style="color: green;text-align: center"> <?php echo $this->session->flashdata('sucess');?></p>
                   <p style="color: red;text-align: center"><?php echo $this->session->flashdata('error');?></p>
			<form id="updatePass" action="<?=base_url()?>admin/cityadmin/updatePassword" method="post" >
			<div class="forget-password">
				<input type="password" id="password" name="password" placeholder="Enter Password">
				<input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password">
				<input type="hidden" id="token" name="token" value="<?php echo $token ?>">
				<input type="button" id="submitBtn" class="btn1" name="" value="Submit">
			</div>
			</form>
		</div>
	</div>
</div>
<style>
	* {
		padding: 0px;
		margin: 0px;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		-ms-box-sizing: border-box;
		-o-box-sizing: border-box;
		box-sizing: border-box;
	}
	body {
		padding: 0px;
		margin: 0px;
	}
	.email-verify {
		background-color: #fff;
		width: 100%;
		height: 100%;
		position: relative;
	}
	.block_verify {
		background-color: #fff;
		padding: 0px 0px 30px;
		position: relative;
	}
	.block_verify .form-signin-heading {
		padding: 15px;
		text-align: center;
		color: #fff;
		font-size: 18px;
		text-transform: uppercase;
		font-weight: 300;
		font-family: 'Open Sans', sans-serif;
		background: -moz-linear-gradient(346deg, rgba(1, 64, 108, 1) 0%, rgba(16, 151, 241, 1) 100%);
		background: -webkit-gradient(linear, left top, right top, color-stop(0%, rgba(1, 64, 108, 1)), color-stop(100%, rgba(16, 151, 241, 1)));
		background: -webkit-linear-gradient(346deg, rgba(1, 64, 108, 1) 0%, rgba(16, 151, 241, 1) 100%);
		background: -o-linear-gradient(346deg, rgba(1, 64, 108, 1) 0%, rgba(16, 151, 241, 1) 100%);
		background: -ms-linear-gradient(346deg, rgba(1, 64, 108, 1) 0%, rgba(16, 151, 241, 1) 100%);
		background: linear-gradient(104deg, rgba(1, 64, 108, 1) 0%, rgba(16, 151, 241, 1) 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#01406c', endColorstr='#1097f1', GradientType=1);
	}
	.block_verify .form-signin-heading img {
		max-width: 80px;
	}
	.containt-wrap {
		padding: 40px;
		position: relative;
		max-width: 90%;
		margin: 0px auto;
		background-color: #fff;
		text-align: center;	}
		.containt-wrap h2 {
			font-size: 36px;
			line-height: 43px;
			color: #0f8fe5;
			text-transform: uppercase;
			font-weight: 700;
		}
		.containt-wrap p {
			font-size: 20px;
			line-height: 26px;
			color: #333;
		}

		.forget-password {
			max-width: 500px;
			padding: 30px;
			margin: 30px auto 0px;
			-webkit-box-shadow: 0px 0px 12px 2px rgba(0, 0, 4, 0.1);
			-moz-box-shadow: 0px 0px 12px 2px rgba(0, 0, 4, 0.1);
			-ms-box-shadow: 0px 0px 12px 2px rgba(0, 0, 4, 0.1);
			-o-box-shadow: 0px 0px 12px 2px rgba(0, 0, 4, 0.1);
			box-shadow: 0px 0px 12px 2px rgba(0, 0, 4, 0.1);
		}
		.forget-password input[type="password"] {
			width: 100%;
			height: 44px;
			border: 1px solid #ddd;
			font-size: 14px;
			line-height: 38px;
			padding: 0 10px;
			margin: 0px 0px 15px;
		}

		.forget-password .btn1 {
			width: 100%;
			height: 44px;
			border: none;
			font-size: 16px;
			line-height: 40px;
			padding: 0 10px;
			background-color: rgba(16, 151, 241, 1);
			text-transform: uppercase;
			color: #fff;
		}
	</style>
<script src="<?=base_url()?>js/lib/jquery/jquery.min.js"></script>
<link href="<?=base_url()?>assets/toastr/toastr.min.css" rel="stylesheet" />
 <script src="<?=base_url()?>assets/toastr/toastr.min.js" type="text/javascript"></script>
	<script>

		$( "#submitBtn" ).click(function() {
          
			var pass = $("#password").val().trim();
			var conpass = $("#confirmpassword").val().trim();
			var pass_token = $("#token").val().trim();

			if(pass==""){
				$("#password").focus();
				toastr.error('Please Put password!');
				return false;
			}
			if(conpass==""){
				$("#confirmpassword").focus();
				toastr.error('Please Put Confirm password!');
				return false;
			}
				if(pass_token==""){
				
				toastr.error('Please Try Again!');
				return false;
			}

			if(pass!=conpass){
				toastr.error('Password and Confirm password does not match');
				return false;
			}else{
				$( "#updatePass" ).submit();
			}


});
	</script>
