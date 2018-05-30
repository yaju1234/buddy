<!-- Bread crumb -->
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-primary">Clients</h3> </div>
		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>admin/clients">Home</a></li>
				<li class="breadcrumb-item active">Clients</li>
			</ol>
		</div>
	</div>
	<!-- End Bread crumb -->
	<!-- Container fluid  -->
	<div class="container-fluid">
		<!-- Start Page Content -->
		<div class="user-details">
			<figure>
				<img src="<?=base_url()?>images/users/1.jpg" alt="">
			</figure>
			<div class="details">
				<h2>jhon doe</h2>
				<p><i class="fa fa-map-marker"></i>Toronto</p>
				<p><i class="fa fa-envelope-o"></i>jhondoe@gmail.com</p>
			</div>
			<label class="switch">
				<input type="checkbox">
				<span class="slider round"></span>
			</label>
		</div>
		<div class="his_cases">
			<div class="block">
				<span>5</span>
				<h3>total cases</h3>
			</div>
			<div class="block">
				<span>5</span>
				<h3>open cases</h3>
			</div>
		</div>
		<div class="tab-area">
			<ul class="nav nav-tabs details-nav" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true"><i class="fa fa-user"></i>profile</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="open-cases-tab" data-toggle="tab" href="#open-cases" role="tab" aria-controls="open-cases" aria-selected="false"><i class="fa fa-folder-open"></i>open cases</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="all-cases-tab" data-toggle="tab" href="#all-cases" role="tab" aria-controls="all-cases" aria-selected="false"><i class="fa fa-reply-all"></i>all cases</a>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
					<table id="" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>email ID</th>
								<th>phone no</th>
								<th>gender</th>
								<th>location</th>
								<th>edit</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>John Doe</td>
								<td>jhon.doe@gmail.com</td>
								<td>+01 9898776545</td>
								<td>Male</td>
								<td>Toronto, Ontario, Canada</td>
								<td>
									<button type="button" class="btn btn-info"><i class="fa fa-pencil"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="open-cases" role="tabpanel" aria-labelledby="open-cases-tab">
					<table id="myTableone" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Sl No.</th>
								<th>case no</th>
								<th>location</th>
								<th>description</th>
								<th>driving licence</th>
								<th>front image</th>
								<th>rare image</th>
								<th>status</th>
								<th>details</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>#908765</td>
								<td>Toronto, Ontario, Canada</td>
								<td>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece..</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>Not Acceptde</td>
								<td><button type="button" class="btn btn-info"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td>1</td>
								<td>#908765</td>
								<td>Toronto, Ontario, Canada</td>
								<td>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece..</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>Not Acceptde</td>
								<td><button type="button" class="btn btn-info"><i class="fa fa-eye"></i></button></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="all-cases" role="tabpanel" aria-labelledby="all-cases-tab">
					<table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Sl No.</th>
								<th>case no</th>
								<th>location</th>
								<th>description</th>
								<th>driving licence</th>
								<th>front image</th>
								<th>rare image</th>
								<th>status</th>
								<th>details</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>#908765</td>
								<td>Toronto, Ontario, Canada</td>
								<td>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece..</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>Not Acceptde</td>
								<td><button type="button" class="btn btn-info"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td>1</td>
								<td>#908765</td>
								<td>Toronto, Ontario, Canada</td>
								<td>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece..</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>
									<figure><img src="<?=base_url()?>images/no-image.png" alt=""></figure>
								</td>
								<td>Not Acceptde</td>
								<td><button type="button" class="btn btn-info"><i class="fa fa-eye"></i></button></td>
							</tr>
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
							<label for="city">
								City
							</label>
							<input type="text" data-val="true" data-val-required="this is Required Field" class="form-control" name="city" id="city" required/>
							<span class="field-validation-valid text-danger"  data-valmsg-for="city" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							<label for="state">
								State
							</label>
							<input type="text" data-val="true" data-val-required="this is Required Field" class="form-control" name="state" id="state" required/>
							<span class="field-validation-valid text-danger"  data-valmsg-for="state" data-valmsg-replace="true"></span>
						</div>
						
						<div class="form-group col-md-6 float-left">
							<label for="country">
								Country
							</label>
							<input type="text" data-val="true" data-val-required="this is Required Field" class="form-control" name="country" id="country" required/>
							<span class="field-validation-valid text-danger"  data-valmsg-for="country" data-valmsg-replace="true"></span>
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
</script>