<link href="<?=base_url()?>css/chosen.css" rel="stylesheet"/>
<!-- Bread crumb -->
	<div class="row page-titles no-gap">
		<div class="col-md-5 align-self-center">
			<h3 class="text-primary">Case Details</h3> </div>
		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>admin/clients">Home</a></li>
				<li class="breadcrumb-item"><a href="<?=base_url()?>admin/clients">Clients</a></li>
				<li class="breadcrumb-item"><a href="<?=base_url()?>admin/clients/details/<?=$client_id?>">Details</a></li>
				<li class="breadcrumb-item active">Case Details</li>
			</ol>
		</div>
	</div>
	<!-- End Bread crumb -->
	<!-- Container fluid  -->
	<div class="container-fluid no-padding">
		<!-- Start Page Content -->
		<div class="user-details">
			<div class="details">
			<div class="col-md-6">
				<h2><?=$client_list['first_name']." ".$client_list['last_name']?></h2>
				<p><i class="fa fa-map-marker"></i><?=$client_list['city']?></p>
				<p><i class="fa fa-envelope-o"></i><?=$client_list['email']?></p>
				<p>Case No: <?=$case_list['case_number']?></p>
			</div>
			<div class="col-md-6">
				<p>Location: <?=$case_list['city']?>, <?=$case_list['state']?></p>
				<p>Description: <?=$case_list['case_details']?></p>
				<p>Status: <?=$case_list['status']?></p>
				<!--<p>Total Bid(s): <?//=$case_list['bid_count']?></p>-->
			</div>
			</div>
		</div>
		<div class="his_cases">
			<div class="block col-md-6 float-left">
				<span><?=count($bid_list)?></span>
				<h3>total bids</h3>
			</div>
			<?php if($case_list['status'] == 'PENDING'){?>
			<div class="block col-md-6 float-left">
				<a href="javascript:void(0);" class="btn btn-primary" style="background-color: #01406c;" data-toggle="modal" data-target="#assignLawyerModal" data-backdrop="static" keyboard="false">ASSIGN LAWYER</a>
			</div>
			<?php } ?>			
		</div>
		
		<div class="tab-area">
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
					<table id="" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Sl No.</th>
								<th>Lawyer Image</th>
								<th>Lawyer Name</th>
								<th>Lawyer Email & Phone</th>
								<th>Bid Amount</th>
								<th>Bid Text</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$i = 0;
								foreach($bid_list as $key => $list) {
									$request_date = date('m-d-Y', strtotime($list['created_at']));
									
									$prfImg = base_url()."images/no-image.png";
									if( $list['lawyer_profile_image'] != '' && (strpos($list['lawyer_profile_image'], 'http://') !== false || strpos($list['lawyer_profile_image'], 'https://') !== false) ){
										$prfImg = $list['lawyer_profile_image'];
									} else if ($list['lawyer_profile_image'] != '') {
										$prfImg = base_url().$list['lawyer_profile_image'];
									}
									$i ++;
                            ?>
							<tr style="<?=$list['status'] == 'ACCEPTED' ? 'background-color: #bde5bd;' : ''?>">
								<td><?=$i?></td>
								<td>
									<figure>
										<a class="example-image-link" href="<?=$prfImg?>" data-fancybox="lawyer-image-<?=$list['id']?>">
											<img src="<?=$prfImg?>" alt="lLawyer Image">
										</a>
									</figure>
								</td>
								<td><?=$list['lawyer_first_name']?> <?=$list['lawyer_last_name']?></td>
								<td>
									<p><?=$list['lawyer_email']?></p>
									<p><?=$list['lawyer_phone']?></p>
								</td>
								<td><?=$list['bid_amount']?></td>
								<td><?=$list['bid_text']?></td>
								<td><?=$list['status']?></td>
								<!--<td><p style="<?//=$list['status'] == 'CLOSED' ? 'color:red' : 'color:green'?>"><?//=$list['status'] == 'CLOSED' ? 'Closed' : 'Open'?></p></td>-->
							</tr>
								<?php
									}
								?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- End PAge Content -->
</div>
<!-- End Container fluid  -->

<div class="modal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="editClientModal">
	<div class="modal-dialog modal-lg" role="document">
	  <div class="modal-content">
			<form action="<?=base_url()?>admin/clients/updateClient" method="post" >
				<div class="modal-header">
				<h4 class="modal-title">Edit Client</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin:0; padding:0; font-size:25px;">
					<span aria-hidden="true">&times;</span>
				</button>
				</div>
				<div class="modal-body">
					<div class="col-md-12">
						<div class="form-group col-md-6 float-left">
							<label for="fname">
								First Name
							</label>
							<input type="hidden" class="form-control" name="id" id="cid"/>
							<input type="text"  data-val="true" data-val-required="this is Required Field" class="form-control" name="first_name" id="fname" required/>
							<span class="field-validation-valid text-danger" data-valmsg-for="fname" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							<label for="lname">
								Last Name
							</label>
							<input type="text" data-val="true" data-val-required="this is Required Field" class="form-control" name="last_name" id="lname" required/>
							<span class="field-validation-valid text-danger"  data-valmsg-for="lname" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							<label for="email">
								Email
							</label>
							<input type="text" data-val="true" data-val-required="this is Required Field" class="form-control" name="email" id="email" required/>
							<span class="field-validation-valid text-danger"  data-valmsg-for="email" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							<label for="phone">
								Phone
							</label>
							<input type="text" data-val="true" data-val-required="this is Required Field" class="form-control" name="phone" id="phone" required/>
							<span class="field-validation-valid text-danger"  data-valmsg-for="phone" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							<label for="country">
								Country
							</label>
							<select type="select" data-val="true" data-val-required="this is Required Field" class="form-control" name="country" id="country" required onChange="fetchStates();">
							<?php foreach($country_list as $country) { ?>
								<option value="<?=$country['country_name']?>"><?=$country['country_name']?></option>
							<?php } ?>
							</select>
							<span class="field-validation-valid text-danger"  data-valmsg-for="country" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							<label for="state">
								State
							</label>
							<select type="select" data-val="true" data-val-required="this is Required Field" class="form-control" name="state" id="state" required onChange="fetchCities();">
								
							</select>
							<span class="field-validation-valid text-danger"  data-valmsg-for="state" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							<label for="city">
								City
							</label>
							<select type="select" data-val="true" data-val-required="this is Required Field" class="form-control" name="city" id="city" required>
								
							</select>
							<span class="field-validation-valid text-danger"  data-valmsg-for="city" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							&nbsp;
						</div>
						
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</form>
	  </div>
	</div>
</div>

<div class="modal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="assignLawyerModal">
	<div class="modal-dialog modal-lg" role="document">
	  <div class="modal-content">
			<form action="<?=base_url()?>admin/clients/assignLawyer" method="post" >
				<div class="modal-header">
				<h4 class="modal-title">Assign Lawyer</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin:0; padding:0; font-size:25px;">
					<span aria-hidden="true">&times;</span>
				</button>
				</div>
				<div class="modal-body">
					<div class="col-md-12">
						<div class="form-group col-md-12 float-left">
							<label for="fname">
								CASE NO: <?=$case_list['case_number']?>
							</label>
							<input type="hidden" class="form-control" name="case_id" value="<?=$case_list['id']?>"/>
							<input type="hidden" class="form-control" name="client_id" value="<?=$client_list['id']?>"/>
						</div>
						
						<div class="form-group col-md-12 float-left">
							<label for="lawyer">
								Select Lawyer
							</label>
							<select type="select" data-val="true" data-val-required="this is Required Field" class="form-control chosen-select" name="lawyer_id">
							<?php foreach($lawyer_list as $lawyer) { ?>
								<option value="<?=$lawyer['id']?>"><?=$lawyer['first_name']?> <?=$lawyer['last_name']?></option>
							<?php } ?>
							</select>
							<span class="field-validation-valid text-danger"  data-valmsg-for="lawyer" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-12 float-left">
							<label for="lawyer">
								Bid Amount
							</label>
							<input type="text" name="bid_amount" class="form-control" placeholder="Enter bid Amount">
							<span class="field-validation-valid text-danger"  data-valmsg-for="bid_amount" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-12 float-left">
							<label for="lawyer">
								Bid Text
							</label>
							<textarea name="bid_text" class="form-control" placeholder="Enter bid text"></textarea>
							<span class="field-validation-valid text-danger"  data-valmsg-for="bid_text" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-12 float-left">
							&nbsp;
						</div>
						
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</form>
	  </div>
	</div>
</div>


<script type="text/javascript">

  var config = {
            '.chosen-select'           : {max_selected_options: 200},
            '.chosen-select-deselect'  : {allow_single_deselect:true},
            '.chosen-select-no-single' : {disable_search_threshold:10},
            '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
            '.chosen-select-width'     : {width:"95%"}
          }
          for (var selector in config) {
            $(selector).chosen(config[selector]);
          }
	var baseUrl = "<?=base_url()?>";
	function disableUser(id){
		if(!$('.dsbleUsr').is(':checked')){
			deleteClient(id);
		} else {
			enableClient(id);
		}
	}
	
	function fetchStates(){
		let cnt = $('#country').val();
		getStates(cnt, '');
	}
	
	function fetchCities(){
		let ste = $('#state').val();
		getCities(ste, '');
	}
	
	function deleteClient(id){
		if(confirm('Are you sure, you want to disable the user?')){
			$(".preloader").show();
			$.ajax({
				type: "POST",
				dataType: "json",
				url: baseUrl + "api/v1/user/deleteClient",
				data: {'id': id},
				success: function(resp) {
					$(".preloader").hide();
				},
				error : function(xhr, textStatus, errorThrown){
					console.log(xhr);
					$("#preloader").hide();
				}
			});
		} else {
			return false;
		}
	}
	
	function enableClient(id){
		$(".preloader").show();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: baseUrl + "api/v1/user/enableClient",
			data: {'id': id},
			success: function(resp) {
				$(".preloader").hide();
			},
			error : function(xhr, textStatus, errorThrown){
				console.log(xhr);
				$("#preloader").hide();
			}
		});
	}
	
	function editClient(id){
		$(".preloader").show();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: baseUrl + "api/v1/user/fetchClientDtls",
			data: {'id': id},
			success: function(resp) {
				if(resp.status){
					$('#cid').val(resp.response.id);
					$('#fname').val(resp.response.first_name);
					$('#lname').val(resp.response.last_name);
					$('#email').val(resp.response.email);
					$('#phone').val(resp.response.phone);
					//$('#city').val(resp.response.city);
					$('#country').val(resp.response.country);
					getStates(resp.response.country, resp.response.state);
					getCities(resp.response.state, resp.response.city);
				}
				$(".preloader").hide();
			},
			error : function(xhr, textStatus, errorThrown){
				console.log(xhr);
				$("#preloader").hide();
			}
		});
	}
	
	function getStates(country, slctState){
		$(".preloader").show();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: baseUrl + "api/v1/user/getStatesByCntry",
			data: {'country': country},
			success: function(resp) {
				if(resp.status){
					let stateHTML = '';
					for(let i=0; i<resp.response.length; i++){
						stateHTML += '<option value="'+resp.response[i].name+'">'+resp.response[i].name+'</option>';
					}
					//console.log(stateHTML);
					$('#state').html(stateHTML);
					$('#state').val(slctState);				
				}
				$(".preloader").hide();
			},
			error : function(xhr, textStatus, errorThrown){
				console.log(xhr);
				$("#preloader").hide();
			}
		});
	}
	
	function getCities(state, slctCity){
		$(".preloader").show();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: baseUrl + "api/v1/user/cities",
			data: {'state': state},
			success: function(resp) {
				if(resp.status){
					let cityHTML = '';
					for(let i=0; i<resp.response.length; i++){
						cityHTML += '<option value="'+resp.response[i].city+'">'+resp.response[i].city+'</option>';
					}
					//console.log(cityHTML);
					$('#city').html(cityHTML);
					if(slctCity != ''){
						$('#city').val(slctCity);
					}
				}
				$(".preloader").hide();
			},
			error : function(xhr, textStatus, errorThrown){
				console.log(xhr);
				$("#preloader").hide();
			}
		});
	}
</script>