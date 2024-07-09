<?php

require "./header.php";

$sql = "SELECT * FROM `withdraw_requests` ORDER BY `id` DESC;";
$result = $conn->query($sql);

?>

<!-- Header -->
<div class="nk-block-head nk-block-head-sm">
	<div class="nk-block-between g-3">
		<div class="nk-block-head-content">
			<h3 class="nk-block-title page-title">Withdraw Requests</h3>
		</div>
	</div>
</div>

<div class="card card-bordered card-preview">
	<div class="card-inner">
		<table class="datatable-init nowrap table">
			<thead>
				<tr>
					<th>#</th>
					<th>Transaction ID</th>
					<th>User ID</th>
					<th>Amount</th>
					<th>Date</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ($result->num_rows > 0) {
					$i = 1;
					while($row = $result->fetch_assoc()) {
						$class = "";
						$btn = '<button class="btn btn-success mlm_admin_withdraw_request" data-status="approved">Approve</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-danger mlm_admin_withdraw_request" data-status="rejected">Reject</button>';
						switch ($row["status"]) {
							case 'pending':
								$class = "warning";
								break;
							case 'approved':
								$class = "success";
								$btn = '<span class="btn">Approved</span>';
								break;
							case 'rejected':
								$class = "danger";
								$btn = '<span class="btn">Rejected</span>';
								break;
						}
						?>
						<tr>
							<td><?= $i ?></td>
							<td class="id"><?= $row['id'] ?></td>
							<td class="user_id"><?= $row['user_id'] ?></td>
							<td class="amount">₹<?= $row['amount'] ?></td>
							<td class="status"> <span class="tb-tnx-status"><span class="badge badge-dot bg-<?= $class ?>"><?= $row['status'] ?></span></span></td>
							<td class="date"><?= $row['date_time'] ?></td>
							<td><?= $btn ?></td>
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
