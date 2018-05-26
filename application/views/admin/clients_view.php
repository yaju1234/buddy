<!-- Bread crumb -->
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-primary">Clients</h3> </div>
		<div class="col-md-7 align-self-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/">Home</a></li>
				<li class="breadcrumb-item active">Clients</li>
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
					<h4 class="card-title">Clients List</h4>
					<!--<h6 class="card-subtitle">Data table example</h6>-->
					<div class="table-responsive m-t-40">
						<table id="myTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th style="display: none;">tab22_id</th>
									<th>Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Address</th>
									<th>Verification Status</th>
									<!--<th>Platform</th>-->
									<th>License</th>
									<th>Joined</th>
									<th style="width:150px;">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
                                    foreach($client_list as $key => $list) {
                                        //$request_date = date('l jS F Y', strtotime($list['created']));
                                        $request_date = date('m-d-Y', strtotime($list['created']));
										
										$prfImg = base_url()."images/no-image.png";
										if( $list['profile_image'] != '' && (strpos($list['profile_image'], 'http://') !== false || strpos($list['profile_image'], 'https://') !== false) ){
											$prfImg = $list['profile_image'];
										} else if ($list['profile_image'] != '') {
											$prfImg = base_url()."uploadImage/client_profile_image/".$list['profile_image'];
										}
										
										$licnseImg = base_url()."images/no-image.png";
										if ($list['license_image'] != '') {
											$prfImg = base_url()."uploadImage/client_license_image/".$list['license_image'];
										}
										
                                ?>
                                    <tr class="" id="request_tr_<?=$list['id']?>">
                                    <td class="center">
										<div class="avatar">
                                            <a class="example-image-link" href="<?=$prfImg?>" data-lightbox="client-image-<?=$list['id']?>">
												<img src="<?=$prfImg?>" style="height: 85px; border-radius: 50%; width: 85px;">
											</a>
                                        </div>
										<p><?=($list['first_name'])?$list['first_name'].' '.$list['last_name']:''?></p>
									</td>
                                    <td class="center"> <?=($list['email'])?$list['email']:''?> </td>
                                    <td class="center"> <?=($list['phone'])?$list['phone']:''?> </td>
                                    <td class="center">
										<p>City: <?=($list['city'])?$list['city']:''?></p>
										<p>State: <?=($list['state'])?$list['state']:''?></p>
										<p>Country; <?=($list['country'])?$list['country']:''?></p>
									</td>
                                    <td class="center">
										<p>Email Verified: <?=$list['is_email_verified'] == '1' ? 'Yes' : 'No'?></p>
										<p>Phone Verified: <?=$list['is_phone_verified'] == '1' ? 'Yes' : 'No'?></p>
									</td>
                                    <!--<td class="center"> <?//=$list['register_from']?> </td>-->
                                    <td class="center">
										<div class="avatar">
											<a class="example-image-link" href="<?=$licnseImg?>" data-lightbox="client-license-<?=$list['id']?>">
												<img src="<?=$licnseImg?>" style="height: 85px; border-radius: 50%; width: 85px;">
											</a>
                                        </div>
									</td>
                                    <td class="center"> <?=$request_date?> </td>
                                    <td class="center">
										<div class="button-list1">
											<button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#editClientModal">Edit</button>
											<button type="button" class="btn btn-danger btn-flat">Delete</button>
										</button>
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

<div class="modal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="editClientModal">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
			<form action="javascript:;" novalidate="novalidate">
				<div class="modal-header">
				<h4 class="modal-title">Edit Client</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin:0; padding:0; font-size:25px;">
					<span aria-hidden="true">&times;</span>
				</button>
				</div>
				<div class="modal-body">
					<div class="">
						<div class="form-group">
							<label for="fname">
								First Name
							</label>
							<input type="text"  data-val="true" data-val-required="this is Required Field" class="form-control" name="fname" id="fname"/>
							<span class="field-validation-valid text-danger" data-valmsg-for="fname" data-valmsg-replace="true"></span>
						</div>
						<div class="form-group">
							<label for="newPass">
								Last Name
							</label>
							<input type="text" data-val="true" data-val-required="this is Required Field" class="form-control" name="lname" id="lname"/>
							<span class="field-validation-valid text-danger"  data-valmsg-for="lname" data-valmsg-replace="true"></span>
							
						</div>
					</div>
				</div>
				<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</form>
	  </div>
	</div>
</div>