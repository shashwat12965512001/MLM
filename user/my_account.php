<?php

require "./header.php";

$total_withdraw = fetchSingleRow("SELECT SUM(amount) AS 'sum' FROM `transactions` WHERE `user_id` = ? AND type = 'withdraw';", [$user_id], "i")['sum'];
$total_referral = fetchSingleRow("SELECT SUM(amount) AS 'sum' FROM `transactions` WHERE `user_id` = ? AND type = 'referral';", [$user_id], "i")['sum'];
$transaction_count = fetchSingleRow("SELECT COUNT(*) AS 'count' FROM `transactions` WHERE `user_id` = ?;", [$user_id], "i")['count'];
$withdraw_month = fetchSingleRow("SELECT SUM(amount) AS 'sum' FROM `transactions` WHERE `user_id` = ? AND `type` = 'withdraw' AND date >= ? AND date <= ?", [$user_id, date('Y-m-01'), date('Y-m-t')], "iss")['sum'];
$referral_month = fetchSingleRow("SELECT SUM(amount) AS 'sum' FROM `transactions` WHERE `user_id` = ? AND `type` = 'referral' AND date >= ? AND date <= ?", [$user_id, date('Y-m-01'), date('Y-m-t')], "iss")['sum'];

?>

<div class="nk-content-body">
	<div class="nk-block-head">
		<div class="nk-block-head-sub"><span>Account Balance</span></div>
		<div class="nk-block-between-md g-4">
			<div class="nk-block-head-content">
				<h2 class="nk-block-title fw-normal">My Account</h2>
				<div class="nk-block-des">
					<p>At a glance summary of your account. Have fun!</p>
				</div>
			</div>
			<div class="nk-block-head-content">
				<ul class="nk-block-tools gx-3">
					<li class="btn-wrap">
						<a href="./withdraw.php" class="btn btn-icon btn-xl btn-warning"><em class="icon ni ni-wallet-out"></em></a><span class="btn-extext">Withdraw</span>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="nk-block">
		<div class="card card-bordered">
			<div class="card-inner">
				<div class="nk-wg1 mb-3">
					<div class="nk-wg1-group g-2">
						<div class="nk-wg1-item me-xl-4">
							<div class="nk-wg1-title">Available Balance</div>
							<div class="nk-wg1-amount">
								<div class="amount">₹ <?= $user['balance'] ?></div>
							</div>
						</div>
						<div class="nk-wg1-item ms-lg-auto">
							<div class="nk-wg1-title">In this month</div>
							<div class="nk-wg1-group g-2">
								<div class="nk-wg1-sub">
									<div class="sub-text">
										<span>Received</span>
										<div class="dot" data-bg="#9cabff" style="background: rgb(156, 171, 255)"></div>
									</div>
									<div class="lead-text">₹<?= !empty($referral_month) ? $referral_month : 0 ?></div>
								</div>
								<div class="nk-wg1-sub">
									<div class="sub-text">
										<span>Withdraw</span>
										<div class="dot" data-bg="#a7ccff" style="background: rgb(167, 204, 255)"></div>
									</div>
									<div class="lead-text">₹<?= !empty($withdraw_month) ? $withdraw_month : 0 ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="nk-ck1">
					<canvas class="chart-account-balance" id="mainBalance"></canvas>
				</div>
			</div>
		</div>
		<div class="card card-bordered">
			<div class="card-inner">
				<div class="card-head ui-v2">
					<div class="card-title">
						<h5 class="title">Balance Summary</h5>
					</div>
				</div>
				<div class="nk-wg4">
					<div class="nk-wg4-group flex-lg-nowrap justify-between g-3">
						<div class="nk-wg4-item">
							<div class="nk-wg4-group g-3">
								<div class="nk-wg4-sub">
									<div class="sub-text">
										<div class="dot dot-lg sq" data-bg="#5ce0aa" style="background: rgb(92, 224, 170)"></div>
										<span>Total Received</span>
									</div>
									<div class="lead-text-lg">₹<?= !empty($total_referral) ? $total_referral : 0 ?></div>
								</div>
								<div class="nk-wg4-sub">
									<div class="sub-text">
										<div class="dot dot-lg sq" data-bg="#f6ca3e" style="background: rgb(246, 202, 62)"></div>
										<span>Total Withdraw</span>
									</div>
									<div class="lead-text-lg">₹<?= !empty($total_withdraw) ? $total_withdraw : 0 ?></div>
								</div>
							</div>
						</div>
						<div class="nk-wg4-item text-lg-right">
							<div class="nk-wg4-note">
								Total <span><?= !empty($transaction_count) ? $transaction_count : 0 ?></span> transaction made
							</div>
						</div>
					</div>
				</div>
				<div class="nk-ck2">
					<canvas class="chart-account-summary" id="summaryBalance" style="
              display: block;
              box-sizing: border-box;
              height: 240px;
              width: 1067px;
            " width="1334" height="300"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>


<?php

require "./footer.php";
