<?php
require "./header.php";

$referral_amount = fetchSingleRow("SELECT `value` FROM `options` WHERE name = ?", ["referral_amount"], "s");
$referral_amount = is_array($referral_amount) ? $referral_amount['value'] : 0;
$minimum_amount_to_withdraw = fetchSingleRow("SELECT `value` FROM `options` WHERE name = ?", ["minimum_amount_to_withdraw"], "s");
$minimum_amount_to_withdraw = is_array($minimum_amount_to_withdraw) ? $minimum_amount_to_withdraw['value'] : 0;

?>

<!-- Header -->
<div class="nk-block-head nk-block-head-sm">
  <div class="nk-block-between g-3">
    <div class="nk-block-head-content">
      <h3 class="nk-block-title page-title">Options</h3>
    </div>
  </div>
</div>


<div class="card card-bordered card-preview">
  <div class="card-inner">
    <div class="preview-block">
        <div id="mlm_admin_options" class="row gy-4">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="form-label" for="mlm_admin_options_referral_amount">Referral Amount</label>
					<div class="form-control-wrap">
						<input type="text" class="form-control" id="mlm_admin_options_referral_amount" value="<?= $referral_amount ?>"/>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="form-label" for="mlm_admin_options_minimum_amount_to_withdraw">Minimum Amount to Withdrawal</label>
					<div class="form-control-wrap">
						<input type="text" class="form-control" id="mlm_admin_options_minimum_amount_to_withdraw" value="<?= $minimum_amount_to_withdraw ?>"/>
					</div>
				</div>
			</div>
		</div>
		<hr class="preview-hr" />
		<button id="mlm_admin_options_submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</div>

<?php

require "./footer.php";