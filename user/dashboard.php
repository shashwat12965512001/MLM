<?php

require "./header.php";

// Sql to get last year transaction count from today for referral
$referral_count = fetchMultipleRows("SELECT DATE_FORMAT(date, '%M') AS month, COUNT(*) AS 'count' FROM transactions WHERE user_id = ? AND type = 'referral' AND date >= ? GROUP BY DATE_FORMAT(date, '%Y-%m') ORDER BY date DESC;", [$user_id, date('Y-m-d', strtotime('-1 year'))], "is");
$data = [
	"January",
	"February",
	"March",
	"April",
	"May",
	"June",
	"July",
	"August",
	"September",
	"October",
	"November",
	"December",
];
$refBarChartdata = array_fill(0, 12, 0);
if (is_array($referral_count) && !empty($referral_count)) {
	foreach ($referral_count as $single) {
		$index = array_search($single['month'], $data);
		$refBarChartdata[$index] = $single['count'];
	}
}

// Transaction count
$row_transaction_count = fetchSingleRow("SELECT COUNT(*) AS transaction_count FROM `transactions` WHERE `user_id` = ?", [$user_id], "i");
$transaction_count = $row_transaction_count['transaction_count'];

// Referred user count
$row_user_count = fetchSingleRow("SELECT COUNT(*) AS user_count FROM `users` WHERE sponser_id = ?", [$user_id], "i");
$user_count = $row_user_count['user_count'];

$type = "referral";
$total_amount = fetchSingleRow("SELECT SUM(amount) AS total_amount FROM `transactions` WHERE `user_id` = ? AND `type` = ?", [$user_id, $type], "is");
$total_amount = !empty($total_amount['total_amount']) ? $total_amount['total_amount'] : 0;

$option_name = $user_id . '_referral_link';
$referral_link = fetchSingleRow("SELECT `value` FROM `options` WHERE `name` = ?", [$option_name], "s")['value'];

?>
<div class="container-xl wide-lg">
	<div class="nk-content-body">

		<!-- Header -->
		<div class="nk-block-head">
			<div class="nk-block-head-sub"><span>Welcome!</span></div>
				<div class="nk-block-between-md g-4">
					<div class="nk-block-head-content">
						<h2 class="nk-block-title fw-normal"><?= ucfirst($user['name']) ?></h2>
						<div class="nk-block-des">
							<p>At a glance summary of your account. Have fun!</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="nk-block">
			<div class="row gy-gs">
				<div class="col-lg-5 col-xl-4">
					<div class="nk-block">
						<div class="nk-block-head-xs">
							<div class="nk-block-head-content">
								<h5 class="nk-block-title title">Overview</h5>
							</div>
						</div>
						<div class="nk-block">
							<div class="card card-bordered text-light is-dark h-100">
								<div class="card-inner">
									<div class="nk-wg7">
										<div class="nk-wg7-stats">
											<div class="nk-wg7-title">Available balance</div>
											<div class="number-lg amount">₹ <?= $user['balance'] ?></div>
										</div>
										<div class="nk-wg7-stats-group">
											<div class="nk-wg7-stats w-50">
												<div class="nk-wg7-title">Transactions</div>
												<div class="number"><?= $transaction_count ?></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="nk-block">
			<div class="card card-bordered">
				<div class="nk-refwg">
					<!-- Refer Us & Earn -->
					<div class="nk-refwg-invite card-inner">
						<div class="nk-refwg-head g-3">
							<div class="nk-refwg-title">
								<h5 class="title">Refer Us &amp; Earn</h5>
								<div class="title-sub">Use the bellow link to invite your friends.</div>
							</div>
						</div>
						<div class="nk-refwg-url">
							<div class="form-control-wrap">
								<div class="form-clip clipboard-init" data-clipboard-target="#refUrl" data-success="Copied" data-text="Copy Link">
									<em class="clipboard-icon icon ni ni-copy"></em>
									<span class="clipboard-text">Copy Link</span>
								</div>
								<div class="form-icon"><em class="icon ni ni-link-alt"></em></div>
								<input type="text" readonly class="form-control copy-text" id="refUrl" value="<?= $referral_link ?>"/>
							</div>
						</div>
					</div>

					<!-- My Referral -->
					<div class="nk-refwg-stats card-inner bg-lighter">
						<div class="nk-refwg-group g-3">
							<div class="nk-refwg-name">
								<h6 class="title">
								My Referral
									<em class="icon ni ni-info" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Referral Informations" data-bs-original-title="Referral Informations"></em>
								</h6>
							</div>
							<div class="nk-refwg-info g-3">
								<div class="nk-refwg-sub">
									<div class="title"><?= $user_count ?></div>
									<div class="sub-text">Total Joined</div>
								</div>
								<div class="nk-refwg-sub">
									<div class="title">₹ <?= $total_amount ?></div>
									<div class="sub-text">Referral Earn</div>
								</div>
							</div>
						</div>
						<div class="nk-refwg-ck">
							<canvas class="chart-refer-stats" id="refBarChart" data-json='<?= json_encode($refBarChartdata) ?>'></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php

require "./footer.php";
