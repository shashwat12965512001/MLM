<?php
require "./header.php";

$sql = "SELECT * FROM `users`";
$result = $conn->query($sql);

?>

<!-- Add New User Modal -->
<div class="modal fade" id="mlm_add_new_user_modal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">User Info</h5>
				<a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
					<em class="icon ni ni-cross"></em>
				</a>
			</div>
			<div class="modal-body">
				<form id="mlm_add_new_user_form" name="mlm_add_new_user_form" class="form-validate is-alter" novalidate="novalidate">
					<div class="form-group">
						<label class="form-label" for="mlm_add_new_user_name">Full Name</label>
						<div class="form-control-wrap">
							<input type="text" class="form-control" id="mlm_add_new_user_name" required/>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="mlm_add_new_user_email">Email address</label>
						<div class="form-control-wrap">
							<div class="form-icon form-icon-right"><em class="icon ni ni-mail"></em></div>
							<input type="email" class="form-control" id="mlm_add_new_user_email" required/>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="mlm_add_new_user_phone">Phone No</label>
						<div class="form-control-wrap">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text" id="fv-phone">+91</span></div>
								<input type="text" pattern="\d*" maxlength="10" class="form-control" id="mlm_add_new_user_phone" required/>
							</div>
						</div>
					</div>
					<div class="form-group">
						<button id="mlm_add_new_user_submit" name="mlm_add_new_user_submit" type="submit" class="btn btn-lg btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Header -->
<div class="nk-block-head nk-block-head-sm">
	<div class="nk-block-between g-3">
		<div class="nk-block-head-content">
			<h3 class="nk-block-title page-title">Users</h3>
		</div>
		<div class="nk-block-head-content">
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mlm_add_new_user_modal">Add New</button>
		</div>
	</div>
</div>

<div class="card card-bordered card-preview">
	<div class="card-inner">
		<table class="datatable-init nowrap table">
			<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Role</th>
						<th>Sponser ID</th>
						<th>Wallet Balance</th>
					</tr>
			</thead>
			<tbody>
				<?php
				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						?>
						<tr>
							<td><?= $row['id'] ?></td>
							<td><?= $row['name'] ?></td>
							<td><?= $row['email'] ?></td>
							<td><?= $row['phone'] == "0" ? "N/A" : $row['phone'] ?></td>
							<td><?= $row['role'] ?></td>
							<td><?= $row['sponser_id'] == "0" ? "N/A" : $row['sponser_id'] ?></td>
							<td><?= $row['balance'] ?></td>
						</tr>
						<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php
require "./footer.php";