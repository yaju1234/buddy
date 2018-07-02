<!-- Bread crumb -->
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-primary">City Admin</h3> </div>
		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>admin/clients">Home</a></li>
				<li class="breadcrumb-item active">Lawyers</li>
			</ol>
		</div>
	</div>
	<!-- End Bread crumb -->
	<!-- Container fluid  -->
	<div class="container-fluid">
		<!-- Start Page Content -->
		<div class="row">
			<div class="col-12">
			<div class="card">
				<div class="card-body">
					<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#addadminmodal" data-backdrop="static" keyboard="false" >
												<i class="fa fa-plus" aria-hidden="true" title="View details"></i>ADD CITY ADMIN
											</button>
					<!--<h6 class="card-subtitle">Data table example</h6>-->
					<div class="table-responsive m-t-40">
						<table id="clientTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th style="display: none;">tab22_id</th>
									<th>SL No.</th>
									<th>Name</th>
									<th>State</th>
									<th>City</th>
									<th>Email</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							
<?php 
									
                                    foreach($city_admin as $key => $list) { ?>
							<tr>
									<td><?php echo $key+1 ; ?></td>
									<td><?php echo $list['display_name']; ?></td>
									<td><?php echo $list['state']; ?></td>
									<td><?php echo $list['city']; ?></td>
									<td><?php echo $list['email']; ?></td>
									<td><?php echo $list['is_active']=='1' ? 'Active' : 'Inactive'; ?></td>
									<td><a href="javascript:void(0)" title="edit" ><i class="fa fa-pencil" aria-hidden="true"></i>
									&nbsp; 
									&nbsp; 
									&nbsp;
									<a href="javascript:void(0)" title="delete" ><i class="fa fa-trash" aria-hidden="true"></i> 
									</td>

							</tr>
<?php } ?>
							
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End PAge Content -->
</div>
<!-- End Container fluid  -->

<div class="modal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="addadminmodal">
	<div class="modal-dialog modal-lg" role="document">
	  <div class="modal-content">
			<form action="<?=base_url()?>admin/cityadmin/addCityAdmin" method="post" >
				<div class="modal-header">
				<h4 class="modal-title">Edit Client</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin:0; padding:0; font-size:25px;">
					<span aria-hidden="true">&times;</span>
				</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-md-6">
							<label for="country">
								Country
							</label>
							<select type="select" data-val="true" data-val-required="this is Required Field" class="form-control" name="country" id="country" required >
							<?php foreach($country_list as $country) { ?>
								<option selected value="<?=$country['country_name']?>"><?=$country['country_name']?></option>
							<?php } ?>
							</select>
							<span class="field-validation-valid text-danger"  data-valmsg-for="country" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 ">
							<label for="state">
								State
							</label>
							<select type="select" data-val="true" data-val-required="this is Required Field" class="form-control" name="state" id="state" required onChange="fetchCities();">
							<option value="">--Select--</option>
								<?php foreach($state_list as $key=>$state) { ?>
								<option value="<?=$state['name']?>"><?=$state['name']?></option>
							<?php } ?>
							</select>
							<span class="field-validation-valid text-danger"  data-valmsg-for="state" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 ">
							<label for="city">
								City
							</label>
							<select type="select" data-val="true" data-val-required="this is Required Field" class="form-control" name="city" id="city" required>
								
							</select>
							<span class="field-validation-valid text-danger"  data-valmsg-for="city" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							<label for="display_name">
								 Name
							</label>
						
							<input type="text"  data-val="true" data-val-required="this is Required Field" class="form-control" name="display_name" 
							id="display_name" required/>
							<span class="field-validation-valid text-danger" data-valmsg-for="display_name" data-valmsg-replace="true"></span>
						</div>
						
						
						
						<div class="form-group col-md-6 ">
							<label for="email">
								Email
							</label>
							<input type="Email" data-val="true" data-val-required="this is Required Field" class="form-control" name="email" id="email" required/>
							<span class="field-validation-valid text-danger"  data-valmsg-for="email" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							<label for="phone">
								Password
							</label>
							<input type="text" data-val="true" minlength="6" data-val-required="this is Required Field" class="form-control" name="password" id="phone" required/>
							<span class="field-validation-valid text-danger"  data-valmsg-for="password" data-valmsg-replace="true"></span>
						</div>
						
						
						<div class="form-group col-md-6 float-left">
							&nbsp;
						</div>
						
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Add City Admin</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</form>
	  </div>
	</div>
</div>

<script>
	var baseUrl = "<?=base_url()?>";
	function deleteClient(id){
		if(confirm('Are you sure, you want to delete?')){
			$(".preloader").show();
			$.ajax({
				type: "POST",
				dataType: "json",
				url: baseUrl + "api/v1/user/deleteClient",
				data: {'id': id},
				success: function(resp) {
					if(resp.status){
						$("#myTable").dataTable().fnDestroy()
						$('#myTable').DataTable();
						$('#request_tr_'+id).remove();
					}
					$(".preloader").hide();
				},
				error : function(xhr, textStatus, errorThrown){
					console.log(xhr);
					$("#preloader").hide();
				}
			});
		}
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
					$('#city').val(resp.response.city);
					$('#state').val(resp.response.state);
					$('#country').val(resp.response.country);
				}
				$(".preloader").hide();
			},
			error : function(xhr, textStatus, errorThrown){
				console.log(xhr);
				$("#preloader").hide();
			}
		});
	}

	function fetchStates(){
		alert("aa")
		let cnt = $('#country').val();
		console.log(cnt);
		getStates(cnt, '');
	}
	
	function fetchCities(){
		let ste = $('#state').val();
		getCities(ste, '');
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