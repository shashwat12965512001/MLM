<?php

require "./header.php";

$sql = "SELECT * FROM transactions WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$transactions = $result->fetch_all(MYSQLI_ASSOC);
$result->free();
$stmt->close();

?>

<!-- Header -->
<div class="nk-block-head nk-block-head-sm">
	<div class="nk-block-between g-3">
		<div class="nk-block-head-content">
			<h3 class="nk-block-title page-title">Transactions</h3>
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
					<th>Date</th>
					<th>Type</th>
					<th>Description</th>
					<th>Amount</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (is_array($transactions) && !empty($transactions)) {
					$i = 1;
					foreach ($transactions as $single) {
						$class = $single['type'] == "referral" ? "success" : ($single["description"] == "Withdraw Request Submitted!" ? "warning" : "danger");
						$symbol = $single['type'] == "referral" ? "+" : ($single["description"] == "Withdraw Request Submitted!" ? "*" : "-");
						?>
						<tr>
							<td><?= $i ?></td>
							<td><?= $single['id'] ?></td>
							<td><?= $single['date'] ?></td>
							<td><?= $single['type'] ?></td>
							<td><?= $single['description'] ?></td>
							<td> <span class="text-<?= $class ?>"><?= $symbol . $single['amount'] ?></span></td>
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
