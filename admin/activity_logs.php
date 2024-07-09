<?php

require "./header.php";

$activity_logs  = [];
$path = "../config/user_logs/";
$files = array_diff(scandir($path), array('.', '..'));
if (is_array($files) && !empty($files)) {
	foreach ($files as $log) {
		$activity_logs = array_merge($activity_logs, json_decode(file_get_contents($path . $log), true));
	}
}

?>

<!-- Header -->
<div class="nk-block-head nk-block-head-sm">
	<div class="nk-block-between g-3">
		<div class="nk-block-head-content">
			<h3 class="nk-block-title page-title">Activity Logs</h3>
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
						<th>Activity</th>
						<th>Date</th>
						<th>Time</th>
					</tr>
			</thead>
			<tbody>
				<?php
				if (is_array($activity_logs) && !empty($activity_logs)) {
					$i = 1;
					foreach ($activity_logs as $single) {
						?>
						<tr>
							<td><?= $i ?></td>
							<td><?= fetchSingleRow("SELECT `name` from `users` where id = ?", [$single['id']], "i")['name'] ?></td>
							<td><?= $single['activity'] ?></td>
							<td><?= date("d/m/Y", $single['time']) ?></td>
							<td><?= date("H:i:s", $single['time']) ?></td>
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
