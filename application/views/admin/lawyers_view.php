<!-- Bread crumb -->
	<div class="row page-titles">
		<div class="col-md-5 align-self-center">
			<h3 class="text-primary">Lawyers</h3> </div>
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
					<h4 class="card-title">Lawyers List</h4>
					<!--<h6 class="card-subtitle">Data table example</h6>-->
					<div class="table-responsive m-t-40">
						<table id="myTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th style="display: none;">tab22_id</th>
									<th>Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Country</th>
									<th>State</th>
									<th>City</th>
									<th>Degree</th>
									<th>Created</th>
								</tr>
							</thead>
							<tbody>
								<?php 
                                    foreach($lawyers_list as $key => $list) {
                                        $request_date = date('l jS F Y', strtotime($list['created']));
                                ?>
                                    <tr class="" id="request_tr_<?=$list['id']?>">
                                    <td style="display: none;"><?=sizeof($ask_doctor_list) - $key?></td>
                                    <td class="center"> <?=($list['first_name'])?$list['first_name'].' '.$list['last_name']:''?> </td>
                                    <td class="center"> <?=($list['email'])?$list['email']:''?> </td>
                                    <td class="center"> <?=($list['phone'])?$list['phone']:''?> </td>
                                    <td class="center"> <?=($list['country'])?$list['country']:''?> </td>
                                    <td class="center"> <?=($list['state'])?$list['state']:''?> </td>
                                    <td class="center"> <?=($list['city'])?$list['city']:''?> </td>
                                    <td class="center"> <?=($list['degree'])?$list['degree']:''?> </td>
                                    <td class="center"> <?=$request_date?> </td>
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