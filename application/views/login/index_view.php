<script type="text/javascript" src="<?=base_url()?>/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).on("click","#signin",function(event){
        event.preventDefault();		
        var form_id = $(this).parents('form:first').attr('id');
        var form = document.getElementById(form_id);
        var sEmail = $('#email').val();
        var sPassword = $('#password').val();
        if( $.trim(sEmail).length == 0 && $.trim(sPassword).length == 0 ){
            toastr.error('Please login to proceed!');
            $(".target").effect( "shake", {times:4}, 1000 );
        }		
        else{	
            if( form.email.value == "" ) {
                alert( "Please enter valid email address");
                form.email.focus() ;
                return false;
            }

            if (validateEmail(sEmail) == false) {
                alert('Invalid Email Address');
                form.email.focus() ;
                return false;
            }

            if( form.password.value == "" ) {
                alert( "Please enter password");
                form.password.focus() ;
                return false;
            }
		
            $('#signin').text('Please wait ...').attr('disabled','disabled');
            
            $.ajax({
                url:'login/dologin',
                type:'POST',
                data:$("#"+form_id).serialize(),
                beforeSend: function() {
                    //$("#preloader").show();
                },
                success:function(result){
                    if(JSON.parse(result).status){
                        window.location.href = "clients/";
                    }else{
                        toastr.error('Please enter correct eamil and password.');
                    }
					$('#signin').text('SIGN IN').removeAttr('disabled');
                }
            });	
        }  
    });

    function validateForgetPassword(){

        var form_id = $('#resetpassword').attr('id');
        var form = document.getElementById(form_id);
  
        if( form.forget_email.value == "" ){
            alert( "Please enter valid email address" );
            form.forget_email.focus() ;
            return false;
        }		
    }	 
    function validateEmail(sEmail) {

        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return filter.test(sEmail)?true:false;   
    }		 
</script>
<div class="container">
    <div class = "target">
        <form class="form-signin" id="loginFrm" action="" method="post">
            <input type="hidden" id="number" value="0"/>
            <h2 class="form-signin-heading"><img src="<?=base_url()?>/images/blue_logo.png" style="height:50; width:30%;"/></h2>
            <?php $this->session->flashdata('message_name'); ?>
            <div class="login-wrap">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" id="email"  placeholder="Email" style="color: #000;">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password"  placeholder="Password" style="color: #000;">
                </div>
                <div class="clearfix"></div>
                <div class="form-group bs-component">
                    <button class="btn btn-raised btn-info btn-block" type="submit" id ="signin">SIGN IN</button>
                </div>
            </div>
        </form>
    </div>
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
        <div class="modal-dialog">
            <form name="resetpassword" id="resetpassword" action="/login/resetPassword" method="POST" onsubmit="return validateForgetPassword()" >
                <div class="modal-content">
                    <div class="modal-header" style="background:#02a6d8; color:#fff; padding:15px 20px;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Forgot Password ?</h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="modal-body">
                        <p>Enter your e-mail address below to reset your password.</p>
                        <div class="form-group">
                            <input type="text" class="form-control" name="forget_email" id="forget_email" autocomplete="off" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-raised btn-info pull-right" type="submit">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- modal -->
</div>