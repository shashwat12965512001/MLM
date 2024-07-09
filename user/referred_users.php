<?php

require "./header.php";

$sql = "SELECT * FROM `users` WHERE sponser_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$referred_users = $result->fetch_all(MYSQLI_ASSOC);
$result->free();
$stmt->close();

?>

<!-- Header -->
<div class="nk-block-head nk-block-head-sm">
	<div class="nk-block-between g-3">
		<div class="nk-block-head-content">
			<h3 class="nk-block-title page-title">Referred Users</h3>
		</div>
	</div>
</div>

<div class="card card-bordered card-preview">
	<div class="card-inner">
		<table class="datatable-init nowrap table">
			<thead>
				<tr>
					<th>#</th>
					<th>User ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Role</th>
					<th>Balance</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (is_array($referred_users) && !empty($referred_users)) {
					$i = 1;
					foreach ($referred_users as $single) {
						?>
						<tr>
							<td><?= $i ?></td>
							<td><?= $single['id'] ?></td>
							<td><?= $single['name'] ?></td>
							<td><?= $single['email'] ?></td>
							<td><?= $single['phone'] ?></td>
							<td><?= $single['role'] ?></td>
							<td><?= $single['balance'] ?></td>
						</tr>
						<?php
						$i++;
					}
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<?php

require "./footer.php";
