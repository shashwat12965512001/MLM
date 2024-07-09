<?php

require "./header.php";

$sql = "SELECT * FROM `transactions` WHERE type = 'referral'";
$result = $conn->query($sql);

?>

<!-- Header -->
<div class="nk-block-head nk-block-head-sm">
  <div class="nk-block-between g-3">
    <div class="nk-block-head-content">
      <h3 class="nk-block-title page-title">Referral Transactions</h3>
    </div>
  </div>
</div>

<div class="nk-block">
	<div class="card card-bordered card-preview">
		<div class="card-inner">
			<table class="datatable-init nowrap table">
				<thead>
						<tr>
							<th>#</th>
							<th>Transaction ID</th>
							<th>Date</th>
							<th>Description</th>
							<th>Amount</th>
							<th>User ID</th>
						</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							?>
							<tr>
								<td><?= $i ?></td>
								<td><?= $row['id'] ?></td>
								<td><?= $row['date'] ?></td>
								<td><?= $row['description'] ?></td>
								<td>₹<?= $row['amount'] ?></td>
								<td><?= $row['user_id'] ?></td>
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
</div>

<?php

require "./footer.php";
