<style type="text/css">
    .modal-body .form-horizontal .col-sm-2,
    .modal-body .form-horizontal .col-sm-10 {
        width: 100%
    }
    .modal-body .form-horizontal .control-label {
        text-align: left;
    }
    .modal-body .form-horizontal .col-sm-offset-2 {
        margin-left: 15px;
    }
    .modal-content .modal-header{padding: 15px;}
    .btn-info, .btn-danger{border: 0!important; padding: 6px 12px!important;}
</style>
<section id="container" >
    <!--header start-->
	<header class="header fixed-top clearfix">
		<!--logo start-->
		<div class="brand">
			<a href="<?=base_url()?>admin/" class="logo">
				<h2 style="color:#fff; padding:0; margin:0;"><img src="<?=base_url()?>/images/blue_logo.png" style="width:30%;"/></h2>
			</a>
			<div class="sidebar-toggle-box">
				<div class="fa fa-bars"></div>
			</div>
		</div>
		<!--logo end-->
		<div class="top-nav clearfix">
			<!--search & user info start-->
			<ul class="nav pull-right top-menu">
				<!-- user login dropdown start-->
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<img alt="" src="<?=base_url()?>images/avatar1_small.jpg">
						<span class="username">Admin</span>
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu extended logout">
						<li><a href="#"><i class=" fa fa-suitcase"></i>Profile</a></li>
						<li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
						<li><a href="<?=base_url()?>admin/login/logout"><i class="fa fa-key"></i> Log Out</a></li>
					</ul>
				</li>
			</ul>
			<!--search & user info end-->
		</div>
	</header>
	<!--header end-->
    <!--sidebar start-->
	<aside>
		<div id="sidebar" class="nav-collapse">
				<!-- sidebar menu start-->
				<ul class="sidebar-menu" id="nav-accordion">
					<li>
						<a class="active" href="<?=base_url()?>admin/clients">
							<i class="fa fa-dashboard"></i>
							<span>Clients</span>
						</a>
					</li>
					<li class="sub-menu">
						<a href="javascript:;">
							<i class="fa fa-user"></i>
							<span>Lawyers </span>
						</a>
					   
					</li>
				</ul>
				<!-- sidebar menu end-->
		</div>
	</aside>
	<!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div id="preloader" style="display: none"></div>
            <!-- page start-->
            <?php if($this->session->flashdata('message_name')):  ?>
            <div class="alert alert-success">
            <p> <?=$this->session->flashdata('message_name')?></p>                                        
            </div>
            <?php endif; ?>
		
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        <strong><?=$title ?></strong>
                        <span class="tools pull-right">
                            <a href="javascript:void(0);" class="fa fa-chevron-down"></a>
                            <a href="javascript:void(0);" class="fa fa-cog"></a>
                            <a href="javascript:void(0);" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                        <div class="adv-table editable-table ">
                            <div class="clearfix">
                                <div class="btn-group pull-right">
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="#">Print</a></li>
                                        <li><a href="#">Save as PDF</a></li>
                                        <li><a href="#">Export to Excel</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="space15"></div>
                            <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                    <tr>
                                        <th style="display: none;">tab22_id</th>
                                        <th style="width: 15%">Email</th>
                                        <th style="width: 20%">Phone</th>
                                        <th style="width: 10%">Created</th>
                                    </tr>
                                </thead>
                                <tbody id="test_list">
                                
                                <?php 
                                    foreach($client_list as $key => $list) {
                                        $request_date = date('l jS F Y', strtotime($list['created']));
                                ?>
                                    <tr class="" id="request_tr_<?=$list['id']?>">
                                    <td style="display: none;"><?=sizeof($ask_doctor_list) - $key?></td>
                                    <td class="center"> <?=($list['email'])?$list['email']:''?> </td>
                                    <td class="center"> <?=($list['phone'])?$list['phone']:''?> </td>
                                    <td class="center"> <?=$request_date?> </td>
                                </tr>
                                <?php } ?>                                
                              </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
        </section>
    </section>
    <!--main content end-->
</section>
<div class="modal fade" id="replyToUserModal" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Reply to User
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                  <div class="form-group">
                    <label  class="col-sm-4 control-label" for="inputEmail3">Email Subject</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control emailSub" placeholder="Email subject" value="Health On Mobile" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="inputPassword3" >Email Body</label>
                    <div class="col-sm-8">
                        <textarea class="form-control emailBody" placeholder="Email body"></textarea>
                    </div>
                  </div>
                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
            <div class="col-sm-12">
                <button type="button" class="btn-danger" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn-info sndUsrId" onclick="sendEmailToUser();">
                    Send
                </button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.replyToUser').bind('click', replyToUserClickHandler);
    });

    function replyToUserClickHandler(){
        var relId = $(this).attr('rel');
        $('#replyToUserModal').modal({
            'keyboard' : 'static',
            'backdrop' : false
        });
        $('.sndUsrId').attr('rel', relId);
    }

    function sendEmailToUser(){
        var emailSub = $('.emailSub').val();
        var emailBody = $('.emailBody').val();
        if((emailSub != "") && (emailBody != "")){
            var sndUsrId = $('.sndUsrId').attr('rel');
            $("#preloader").show();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/ask_doctor/sendEmailToUser",
                data: {'id': sndUsrId,'emailSub': emailSub,'emailBody':emailBody},
                success: function(resp) {
                    if(resp.status){
                        $('#usr_'+resp.id).removeAttr('class').html('Replied');
                    }
                    $('#replyToUserModal').modal('hide');
                    $("#preloader").hide();
                },
                error : function(xhr, textStatus, errorThrown){
                    console.log(xhr);
                    $("#preloader").hide();
                }
            });
        }else{
            alert('please enter email subject and email body.');
        }
    }
</script>