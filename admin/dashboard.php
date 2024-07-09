<?php

require "./header.php";

// Initialize variables
$admin_balance = 0;
$admin_balance_last_week = 0;
$admin_balance_last_month = 0;
$total_income = 0;
$total_income_last_month = 0;
$total_income_last_week = 0;
$total_withdraw_amount = 0;
$total_withdraw_last_month = 0;
$total_withdraw_last_week = 0;

// Define date ranges
$one_month_ago = date('Y-m-d', strtotime('-1 month'));
$one_week_ago = date('Y-m-d', strtotime('-1 week'));

// Single query to fetch all data
$sql = "SELECT
        u.balance AS admin_balance,
        SUM(CASE WHEN t.type = 'referral' THEN t.amount ELSE 0 END) AS total_referral_amount,
        SUM(CASE WHEN t.type = 'withdraw' THEN t.amount ELSE 0 END) AS total_withdraw_amount,
        SUM(CASE WHEN t.type = 'referral' AND t.date >= ? THEN t.amount ELSE 0 END) AS total_income_last_month,
        SUM(CASE WHEN t.type = 'withdraw' AND t.date >= ? THEN t.amount ELSE 0 END) AS total_withdraw_last_month,
        SUM(CASE WHEN t.type = 'referral' AND t.date >= ? THEN t.amount ELSE 0 END) AS total_income_last_week,
        SUM(CASE WHEN t.type = 'withdraw' AND t.date >= ? THEN t.amount ELSE 0 END) AS total_withdraw_last_week
    FROM
        users u
    LEFT JOIN
        transactions t ON u.role = 'admin'
    WHERE
        u.role = 'admin' OR t.date >= ?";

// Execute the query and fetch the result
$params = [
    $one_month_ago,
    $one_month_ago,
    $one_week_ago,
    $one_week_ago,
    $one_month_ago,
];
$result = fetchSingleRow($sql, $params, 'sssss');

// Check if the query was successful and assign variables
if ($result) {
    $admin_balance = (int)$result['admin_balance'];
    $admin_balance_last_week = (int)$result['admin_balance'];
    $admin_balance_last_month = (int)$result['admin_balance'];
    $total_income = (int)$result['total_referral_amount'];
    $total_withdraw_amount = (int)$result['total_withdraw_amount'];
    $total_income_last_month = (int)$result['total_income_last_month'];
    $total_withdraw_last_month = (int)$result['total_withdraw_last_month'];
    $total_income_last_week = (int)$result['total_income_last_week'];
    $total_withdraw_last_week = (int)$result['total_withdraw_last_week'];
}

$path = "../config/user_logs/";
$files = array_diff(scandir($path), array('.', '..'));
$activity_logs = [];
if (is_array($files) && !empty($files)) {
	foreach ($files as $single) {
		$logs = json_decode(file_get_contents($path . $single), true);
		$len = count($logs);
		if ((int)$len < 5) {
			$activity_logs = array_merge($activity_logs, $logs);
		}else {
			$activity_logs = array_merge($activity_logs, array_slice($logs, $len - 5));
		}
	}
}

$activity_logs = getLatestEntries($activity_logs);

$transactions = fetchMultipleRows("SELECT * FROM `transactions` ORDER BY `date` DESC LIMIT 5;", [], "");

$new_users = fetchMultipleRows("SELECT * FROM `users` ORDER BY `id` DESC LIMIT 5;", [], "");

?>

<div class="nk-content-body">
	<div class="nk-block-head nk-block-head-sm">
		<div class="nk-block-between">
			<div class="nk-block-head-content">
				<h3 class="nk-block-title page-title">Admin Dashboard</h3>
				<div class="nk-block-des text-soft">
					<p>Welcome to Admin Dashboard</p>
				</div>
			</div>
		</div>
	</div>
	<div class="nk-block">
		<div class="row g-gs">
			<!-- Total Income -->
			<div class="col-md-4">
				<div class="card card-bordered card-full">
					<div class="card-inner">
						<div class="card-title-group align-start mb-0">
							<div class="card-title">
								<h6 class="subtitle">Total Income</h6>
							</div>
							<div class="card-tools">
								<em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" aria-label="Total Income" data-bs-original-title="Total Income"></em>
							</div>
						</div>
						<div class="card-amount">
							<span class="amount">₹<?= $total_income ?></span>
						</div>
						<div class="invest-data">
							<div class="invest-data-amount g-2">
								<div class="invest-data-history">
									<div class="title">This Month</div>
									<div class="amount">₹<?= $total_income_last_month ?>
									</div>
								</div>
								<div class="invest-data-history">
									<div class="title">This Week</div>
									<div class="amount">₹<?= $total_income_last_week ?></div>
								</div>
							</div>
							<div class="invest-data-ck">
								<canvas class="iv-data-chart" id="totalDeposit" style=" display:block; box-sizing:border-box; height:48px; width:85px;" width="106" height="60"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Total Withdraw -->
			<div class="col-md-4">
				<div class="card card-bordered card-full">
					<div class="card-inner">
						<div class="card-title-group align-start mb-0">
							<div class="card-title">
								<h6 class="subtitle">Total Withdraw</h6>
							</div>
							<div class="card-tools">
								<em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" aria-label="Total Withdraw" data-bs-original-title="Total Withdraw"></em>
							</div>
						</div>
						<div class="card-amount">
							<span class="amount">₹<?= $total_withdraw_amount ?></span>
						</div>
						<div class="invest-data">
							<div class="invest-data-amount g-2">
								<div class="invest-data-history">
									<div class="title">This Month</div>
									<div class="amount">₹<?= $total_withdraw_last_month ?></div>
								</div>
								<div class="invest-data-history">
									<div class="title">This Week</div>
									<div class="amount">₹<?= $total_withdraw_last_week ?></div>
								</div>
							</div>
							<div class="invest-data-ck">
								<canvas class="iv-data-chart" id="totalWithdraw" style=" display:block; box-sizing:border-box; height:48px; width:85px;" width="106" height="60"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Total Balance -->
			<div class="col-md-4">
				<div class="card card-bordered card-full">
					<div class="card-inner">
						<div class="card-title-group align-start mb-0">
							<div class="card-title">
								<h6 class="subtitle">Balance in Account</h6>
							</div>
							<div class="card-tools">
								<em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" aria-label="Total Balance in Account" data-bs-original-title="Total Balance in Account"></em>
							</div>
						</div>
						<div class="card-amount">
							<span class="amount">₹<?= $admin_balance ?></span>
						</div>
						<div class="invest-data">
							<div class="invest-data-amount g-2">
								<div class="invest-data-history">
									<div class="title">This Month</div>
									<div class="amount">₹<?= $admin_balance_last_month ?></div>
								</div>
								<div class="invest-data-history">
									<div class="title">This Week</div>
									<div class="amount">₹<?= $admin_balance_last_week ?></div>
								</div>
							</div>
							<div class="invest-data-ck">
								<canvas class="iv-data-chart" id="totalBalance" style="
						display: block;
						box-sizing: border-box;
						height: 48px;
						width: 85px;
					" width="106" height="60"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Recent Activity -->
			<div class="col-md-6 col-xxl-4">
				<div class="card card-bordered card-full">
					<div class="card-inner border-bottom">
						<div class="card-title-group">
							<div class="card-title">
								<h6 class="title">Recent Activities</h6>
							</div>
						</div>
					</div>
					<ul class="nk-activity">
						<?php
						if (is_array($activity_logs) && !empty($activity_logs)) {
							foreach ($activity_logs as $log) {
								$name = fetchSingleRow("SELECT `name` from users WHERE id = ?;", [$log['id']], "i")['name'];
								$description = "";
								switch ($log['activity']) {
									case 'Profile Updated!':
										$description = "Updated his Profile.";
										break;
									case 'Password Changed!':
										$description = "Changed his password.";
										break;
									default:
										$description = $log['activity'];
										break;
								}
								$duration = calculateDurationFromNow(date("d/m/Y", $log['time']), date("H:i:s", $log['time']));
								?>
								<li class="nk-activity-item">
									<div class="nk-activity-media user-avatar bg-success">
										<div class="user-avatar sm">
											<em class="icon ni ni-user-alt"></em>
										</div>
									</div>
									<div class="nk-activity-data">
										<div class="label"><?= ucfirst($name) ." $description" ?></div>
										<span class="time">
										<?php
										if (isset($duration['error'])) {
											// Handle error
											echo $duration['error'];
										} else {
											// Output the duration
											if ($duration['days'] != 0) {
												echo "Duration: $duration[days] days ago.";
											}else if ($duration['hours'] != 0) {
												echo "Duration: $duration[hours] hours ago.";
											}else if ($duration['minutes'] != 0) {
												echo "Duration: $duration[minutes] minutes ago.";
											}else {
												echo "Duration: $duration[seconds] seconds ago.";
											}
										}
										?>
										</span>
									</div>
								</li>
								<?php
							}
						}
						?>
					</ul>
				</div>
			</div>

			<!-- Active Users -->
			<div class="col-md-6 col-xxl-4">
				<div class="card card-bordered card-full">
					<div class="card-inner-group">
						<div class="card-inner">
							<div class="card-title-group">
								<div class="card-title"><h6 class="title">New Users</h6></div>
								<div class="card-tools">
									<a href="./users" class="link">View All</a>
								</div>
							</div>
						</div>
						<?php
						if (is_array($new_users) && !empty($new_users)) {
							foreach ($new_users as $user) {
								$avatar = strtoupper(implode('', array_map(fn($word) => $word[0], explode(' ', $user['name']))));
								?>
								<div class="card-inner card-inner-md">
									<div class="user-card">
										<div class="user-avatar bg-primary-dim"><span><?= $avatar ?></span></div>
										<div class="user-info">
											<span class="lead-text"><?= $user['name'] ?></span><span class="sub-text"><?= $user['email'] ?></span>
										</div>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>

			<!-- Transactions -->
			<div class="col-xl-12 col-xxl-8">
				<div class="card card-bordered card-full">
					<div class="card-inner">
						<div class="card-title-group">
							<div class="card-title">
								<h6 class="title">
									<span class="me-2">Transaction</span>
									<a href="./referral_transactions.php" class="link d-none d-sm-inline">See History</a>
								</h6>
							</div>
						</div>
					</div>
					<div class="card-inner p-0 border-top">
						<div class="nk-tb-list nk-tb-orders">
							<div class="nk-tb-item nk-tb-head">
								<div class="nk-tb-col"><span>Transaction ID</span></div>
								<div class="nk-tb-col tb-col-sm"><span>Customer Name</span></div>
								<div class="nk-tb-col tb-col-md"><span>Date</span></div>
								<div class="nk-tb-col tb-col-lg"><span>Sponser</span></div>
								<div class="nk-tb-col"><span>Amount</span></div>
								<div class="nk-tb-col"><span>Description</span></div>
								<div class="nk-tb-col">
									<span class=" d-sm-inline">Status</span>
								</div>
								<div class="nk-tb-col"></div>
							</div>

							<?php
							if (is_array($transactions) && !empty($transactions)) {
								foreach ($transactions as $single) {
									$user = fetchSingleRow("SELECT `name`, `sponser_id` from `users` WHERE id = ?", [$single['user_id']], "i");
									$sponser = fetchSingleRow("SELECT `name` from `users` WHERE id = ?", [$user['sponser_id']], "i");
									$sponser = $sponser ? $sponser['name'] : "N/A";
									$class = $single['type'] == "referral" ? "success" : "danger";
									$status = $single['type'] == "referral" ? "Recieved" : "Deducted";
									$avatar = strtoupper(implode('', array_map(fn($word) => $word[0], explode(' ', $user['name']))));
									$description = str_contains($single['description'], "Added") ? str_replace("Added!", "", $single['description']) : (str_contains($single['description'], "Deducted") ? str_replace("Deducted!", "", $single['description']) : ((str_contains($single['description'], "Received") ? str_replace("Received!", "", $single['description']) : $single['description'])));
									?>
									<div class="nk-tb-item">
										<div class="nk-tb-col">
											<span class="tb-lead"><a href="#">#<?= $single['id'] ?></a></span>
										</div>
										<div class="nk-tb-col tb-col-sm">
											<div class="user-card">
												<div class="user-avatar user-avatar-sm bg-purple">
													<span><?= $avatar ?></span>
												</div>
												<div class="user-name">
													<span class="tb-lead"><?= $user['name'] ?></span>
												</div>
											</div>
										</div>
										<div class="nk-tb-col tb-col-md">
											<span class="tb-sub"><?= $single['date'] ?></span>
										</div>
										<div class="nk-tb-col tb-col-lg">
											<span class="tb-sub <?= $sponser != "N/A" ? "text-primary" : "" ?>"><?= $sponser ?></span>
										</div>
										<div class="nk-tb-col">
											<span class="tb-sub tb-amount">₹<?= $single['amount'] ?></span>
										</div>
										<div class="nk-tb-col">
											<span class="tb-sub"><?= $description ?></span>
										</div>
										<div class="nk-tb-col">
											<span class="badge badge-dot badge-dot-xs bg-<?= $class ?>"><?= $status ?></span>
										</div>
										<div class="nk-tb-col nk-tb-col-action"></div>
									</div>
									<?php
								}
							}
							?>
						</div>
					</div>
					<div class="card-inner-sm border-top text-center d-sm-none">
						<a href="#" class="btn btn-link btn-block">See History</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php

require "./footer.php";
