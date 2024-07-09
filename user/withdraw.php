<?php

require "./header.php";

$balance = 0;
$sql = "SELECT `balance` FROM `users` WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();
$stmt->close();

$referral_amount = fetchSingleRow("SELECT `value` from options where `name` = ?;", ["referral_amount"], "s");
$referral_amount = is_array($referral_amount) ? $referral_amount['value'] : 0;
$minimum_amount_to_withdraw = fetchSingleRow("SELECT `value` FROM `options` WHERE name = ?", ["minimum_amount_to_withdraw"], "s");
$minimum_amount_to_withdraw = is_array($minimum_amount_to_withdraw) ? $minimum_amount_to_withdraw['value'] : 0;

?>

<!-- Header -->
<div class="nk-block-head nk-block-head-sm">
  <div class="nk-block-between g-3">
    <div class="nk-block-head-content">
      <h3 class="nk-block-title page-title">Withdraw</h3>
    </div>
  </div>
</div>

<div class="card card-bordered card-preview">
  <div class="card-inner">
    <div class="preview-block">
        <div class="row gy-4">

			<form id="mlm_withdraw_request_form">
				<!-- Available Balance -->
				<div class="col-sm-6">
					<div class="form-group">
						<label class="form-label" for="mlm_withdraw_balance">Available Balance</label>
						<div class="form-control-wrap">
							<input type="number" class="form-control" id="mlm_withdraw_balance" value="<?= $balance ?>" readonly disabled/>
						</div>
					</div>
				</div>

				<!-- Minimum Amount to Withdraw -->
				<div class="col-sm-6">
					<div class="form-group">
						<label class="form-label" for="mlm_withdraw_min_amount">Minimum Amount to Withdraw</label>
						<div class="form-control-wrap">
							<input type="number" class="form-control" id="mlm_withdraw_min_amount" value="<?= $minimum_amount_to_withdraw ?>" readonly disabled/>
						</div>
					</div>
				</div>

				<div class="col-sm-6"></div>
	
				<!-- Withdraw Amount -->
				<div class="col-sm-6">
					<div class="form-group">
						<label class="form-label" for="mlm_withdraw_amount">Withdraw Amount</label>
						<div class="form-control-wrap">
							<input type="number" class="form-control" id="mlm_withdraw_amount" placeholder="Withdraw Amount" steps="10" min="<?= $minimum_amount_to_withdraw ?>"/>
						</div>
					</div>
				</div>
	
				<div class="col-sm-6"></div>
	
				<!-- Admin Charges -->
				<div class="col-sm-6">
					<div class="form-group">
						<label class="form-label" for="mlm_withdraw_admin_charges">Admin Charges (5%)</label>
						<div class="form-control-wrap">
							<input type="text" class="form-control" id="mlm_withdraw_admin_charges" placeholder="Admin Charges" readonly disabled="true" value="0.00"/>
						</div>
					</div>
				</div>
	
				<div class="col-sm-6"></div>
	
				<!-- Net Payable Amount -->
				<div class="col-sm-6">
					<div class="form-group">
						<label class="form-label" for="mlm_withdraw_net_payable_amount">Net Payable Amount</label>
						<div class="form-control-wrap">
							<input type="text" class="form-control" id="mlm_withdraw_net_payable_amount" placeholder="Net Payable Amount" readonly disabled="true" value="0.00"/>
						</div>
					</div>
				</div>
			</form>
		</div>
		<hr class="preview-hr" />
		<button id="mlm_withdraw_submit" data-id="<?= $user_id ?>" class="btn btn-primary">Submit</button>
    </div>
  </div>
</div>


<?php

require "./footer.php";
