<?php

require "./header.php";

$sponser_name = "No sponser!";
if ($user['sponser_id'] != "0") {
	$sponser_name = fetchSingleRow("SELECT name FROM users WHERE id = ?", [$user['sponser_id']], "i")['name'];
}

$row = fetchSingleRow("SELECT `value` FROM `options` WHERE name = ?", ["{$user_id}_bank_details"], "s")['value'];
$bank_details = json_decode($row, true);

$activity = fetchSingleRow("SELECT `value` FROM `options` WHERE name = ?", ["{$user_id}_activity_log"], "s")['value'];
$auth = fetchSingleRow("SELECT `value` FROM `options` WHERE name = ?", ["{$user_id}_2fa"], "s")['value'];
$path = "../config/user_logs/{$user_id}_logs.json";
if (file_exists($path)) {
	$last_changed_date = getClosestPasswordChangeDate(json_decode(file_get_contents($path), true));	
}else {
	$last_changed_date = "Never";
}

?>
<div class="nk-content-body">
	<div class="nk-block-head">
		<div class="nk-block-head-content">
			<div class="nk-block-head-sub"><span>Account Setting</span></div>
			<h2 class="nk-block-title fw-normal">My Profile</h2>
			<div class="nk-block-des">
				<p>You have full control to manage your own account setting.</p>
			</div>
		</div>
	</div>

	<!-- Tabs Header -->
	<ul class="nav nav-tabs mt-n3" role="tablist">
		<li class="nav-item" role="presentation">
			<a class="nav-link active" data-bs-toggle="tab" href="#mlm_user_profile_personal_tab" aria-selected="true" role="tab">
				<em class="icon ni ni-user"></em>
				<span>Personal</span>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" data-bs-toggle="tab" href="#mlm_user_profile_security_tab" aria-selected="false" role="tab" tabindex="-1">
				<em class="icon ni ni-lock-alt"></em>
				<span>Security</span>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" data-bs-toggle="tab" href="#mlm_user_profile_account_tab" aria-selected="false" role="tab" tabindex="-1">
				<em class="icon ni ni-home"></em>
				<span>Account</span>
			</a>
		</li>
	</ul>

	<!-- Tabs Content -->
	<div class="tab-content">
		<div class="tab-pane active show" id="mlm_user_profile_personal_tab" role="tabpanel">

			<div class="nk-block">
				<div class="nk-block-head">
					<div class="nk-block-head-content">
						<h5 class="nk-block-title">Personal Information</h5>
						<div class="nk-block-des">
							<p>Basic info, like your name and email, that you use on our Platform.</p>
						</div>
					</div>
				</div>

				<div class="nk-data data-list">
					<div class="data-head"><h6 class="overline-title">Basics</h6></div>
					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_edit">
						<div class="data-col">
							<span class="data-label">Full Name</span>
							<span class="data-value"><?= ucwords($user['name']) ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>
					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_edit">
						<div class="data-col">
							<span class="data-label">Display Name</span><span class="data-value"><?= ucfirst(explode(" ", $user['name'])[0]) ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>
					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_edit">
						<div class="data-col">
							<span class="data-label">Email</span><span class="data-value"><?= $user['email'] ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>
					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_edit">
						<div class="data-col">
							<span class="data-label">Phone Number</span><span class="data-value"><?= $user['phone'] == "0" ? "Not added yet!" : $user['phone'] ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>
					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_edit">
						<div class="data-col">
							<span class="data-label">Sponser Name</span><span class="data-value"><?= $sponser_name ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="mlm_user_profile_edit" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
					<div class="modal-content">
						<a href="#" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
						<div class="modal-body modal-body-lg">
							<h5 class="title">Update Profile</h5>
							<ul class="nk-nav nav nav-tabs" role="tablist">
								<li class="nav-item" role="presentation">
									<a class="nav-link active" data-bs-toggle="tab" href="#mlm_user_profile_personal" aria-selected="true" role="tab">Personal</a>
								</li>
							</ul>

							<!-- Modal Content -->
							<div class="tab-content">
								<div class="tab-pane active" id="mlm_user_profile_personal" role="tabpanel">
									<div class="row gy-4">
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="mlm_user_profile_name">Name</label>
												<input type="text" class="form-control form-control-lg" id="mlm_user_profile_name" value="<?= $user['name'] ?>" placeholder="Enter Full name"/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="mlm_user_profile_phone">Phone Number</label>
												<input type="number" class="form-control form-control-lg" id="mlm_user_profile_phone" value="<?= $user['phone'] == "0" ? "" : $user['phone'] ?>" placeholder="Phone Number"/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="mlm_user_profile_email">Email</label>
												<input type="email" class="form-control form-control-lg" id="mlm_user_profile_email" value="<?= $user['email'] ?>"/>
											</div>
										</div>
										<input type="hidden" class="form-control" id="mlm_user_profile_id" value="<?= $user_id ?>">
										<div class="col-12">
											<ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
												<li>
													<button id="mlm_user_profile_submit" class="btn btn-lg btn-primary">Update Profile</button>
												</li>
												<li>
													<a href="#" data-bs-dismiss="modal" class="link link-light">Cancel</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane" id="mlm_user_profile_security_tab" role="tabpanel">
			<div class="nk-block">
				<div class="nk-block-head">
					<div class="nk-block-head-content">
						<h5 class="nk-block-title">Security Settings</h5>
						<div class="nk-block-des">
							<p>These settings are helps you keep your account secure.</p>
						</div>
					</div>
				</div>
				<div class="card card-bordered">
					<div class="card-inner-group">
						<!-- Activity Logs -->
						<div class="card-inner">
							<div class="between-center flex-wrap flex-md-nowrap g-3">
								<div class="nk-block-text">
									<h6>Save my Activity Logs</h6>
									<p>You can save your all activity logs.</p>
								</div>
								<div class="nk-block-actions">
									<ul class="align-center gx-3">
										<li class="order-md-last d-inline-flex">
											<div class="custom-control custom-switch me-n2">
												<input type="checkbox" class="custom-control-input" data-id="<?= $user['id'] ?>" <?= $activity ? "checked" : "" ?> id="mlm_user_profile_activity_log"/>
												<label class="custom-control-label" for="mlm_user_profile_activity_log"></label>
											</div>
										</li>
										<li>
											<a href="./activity_logs.php" class="link link-sm link-primary">See Recent Activity</a>
										</li>
									</ul>
								</div>
							</div>
						</div>

						<!-- Change Password -->
						<div class="card-inner">
							<div class="between-center flex-wrap flex-md-nowrap g-3">
								<div class="nk-block-text">
									<h6>Change Password</h6>
									<p>Set a unique password to protect your account.</p>
								</div>
								<div class="nk-block-actions flex-shrink-sm-0">
									<ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
										<li class="order-md-last">
											<a href="#" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_change_password_modal" class="btn btn-primary">Change Password</a>
										</li>
										<li>
											<em class="text-soft text-date fs-12px">Last changed: <span><?= !empty($last_changed_date) ? $last_changed_date : "Never." ?></span></em>
										</li>
									</ul>
								</div>

								<div class="modal fade" id="mlm_user_profile_change_password_modal" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Change Password</h5>
												<a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
													<em class="icon ni ni-cross"></em>
												</a>
											</div>
											<div class="modal-body">
												<form id="mlm_user_profile_change_password_form" name="mlm_user_profile_change_password_form" class="form-validate is-alter" novalidate="novalidate">
													<!-- Current Password -->
													<div class="form-group">
														<div class="form-label-group">
															<label class="form-label" for="mlm_user_profile_change_password_old">Current Password</label>
														</div>
														<div class="form-control-wrap">
															<a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg is-hidden" data-target="mlm_user_profile_change_password_old">
																<em class="passcode-icon icon-show icon ni ni-eye"></em>
																<em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
															</a>
															<input autocomplete="new-password" type="password" class="form-control form-control-lg is-hidden" required="" id="mlm_user_profile_change_password_old" placeholder="Enter your passcode">
														</div>
													</div>
													<!-- New Password -->
													<div class="form-group">
														<div class="form-label-group">
															<label class="form-label" for="mlm_user_profile_change_password_new">New Password</label>
														</div>
														<div class="form-control-wrap">
															<a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg is-hidden" data-target="mlm_user_profile_change_password_new">
																<em class="passcode-icon icon-show icon ni ni-eye"></em>
																<em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
															</a>
															<input autocomplete="new-password" type="password" class="form-control form-control-lg is-hidden" required="" id="mlm_user_profile_change_password_new" placeholder="Enter your passcode">
														</div>
													</div>
													<!-- Confirm New Password -->
													<div class="form-group">
														<div class="form-label-group">
															<label class="form-label" for="mlm_user_profile_change_password_confirm_new">Confirm New Password</label>
														</div>
														<div class="form-control-wrap">
															<a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg is-hidden" data-target="mlm_user_profile_change_password_confirm_new">
																<em class="passcode-icon icon-show icon ni ni-eye"></em>
																<em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
															</a>
															<input autocomplete="new-password" type="password" class="form-control form-control-lg is-hidden" required="" id="mlm_user_profile_change_password_confirm_new" placeholder="Enter your passcode">
														</div>
													</div>

													<div class="form-group">
														<button id="mlm_user_profile_change_password_submit" name="mlm_user_profile_change_password_submit" data-id="<?= $user_id ?>" class="btn btn-lg btn-primary">Submit</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>

						<!-- 2FA Authentication -->
						<div class="card-inner">
							<div class="between-center flex-wrap flex-md-nowrap g-3">
								<div class="nk-block-text">
									<h6>2FA Authentication <span class="badge bg-<?= $auth === "true" ? "success" : "danger" ?>"><?= $auth === "true" ? "Enabled" : "Disabled" ?></span></h6>
									<p> Secure your account with 2FA security. When it is activated you will need to enter not only your password, but also a OTP (One Time Password) sent to your email address.</p>
								</div>
								<div class="nk-block-actions">
									<button id="mlm_user_profile_2fa" data-id="<?= $user_id ?>" class="btn btn-primary"><?= $auth === "true" ? "Disable" : "Enable" ?></button>
								</div>
							</div>
						</div>

						<!-- Delete Account -->
						<div class="card-inner">
							<div class="between-center flex-wrap flex-md-nowrap g-3">
								<div class="nk-block-text">
									<h6>Delete Your Account permanently.</h6>
									<p>Note: Withdraw your wallet balance before deleting as you won't be able to access your account anymore.</p>
								</div>
								<div class="nk-block-actions flex-shrink-sm-0">
									<ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
										<li class="order-md-last">
											<button id="mlm_user_delete_account" class="btn btn-danger" data-id="<?= $user_id ?>">Delete</button>
										</li>
									</ul>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane" id="mlm_user_profile_account_tab" role="tabpanel">
			<div class="nk-block">
				<div class="nk-block-head">
					<div class="nk-block-head-content">
						<h5 class="nk-block-title">Bank Account Details</h5>
						<div class="nk-block-des">
							<p>Securely link your bank account for seamless transactions.</p>
						</div>
					</div>
				</div>

				<div class="nk-data data-list">
					<div class="data-head"><h6 class="overline-title">Basics</h6></div>

					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_account_edit">
						<div class="data-col">
							<span class="data-label">Account Holder's Name</span>
							<span class="data-value"><?= $bank_details['name'] ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>

					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_account_edit">
						<div class="data-col">
							<span class="data-label">Bank Name</span><span class="data-value"><?= $bank_details['bank_name'] ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>

					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_account_edit">
						<div class="data-col">
							<span class="data-label">Account Number</span><span class="data-value"><?= $bank_details['account_number'] ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>

					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_account_edit">
						<div class="data-col">
							<span class="data-label">IFSC Code</span><span class="data-value"><?= $bank_details['ifsc_code'] ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>

					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_account_edit">
						<div class="data-col">
							<span class="data-label">Routing Number/ABA number (if applicable)</span><span class="data-value"><?= $bank_details['routing_number'] ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>

					<div class="data-item" data-bs-toggle="modal" data-bs-target="#mlm_user_profile_account_edit" data-tab-target="#address">
						<div class="data-col">
							<span class="data-label">SWIFT/BIC Code (for international transfers)</span><span class="data-value"><?= $bank_details['swift_number'] ?></span>
						</div>
						<div class="data-col data-col-end">
							<span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="mlm_user_profile_account_edit" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
					<div class="modal-content">
						<a href="#" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
						<div class="modal-body modal-body-lg">
							<h5 class="title">Update Account Details</h5>

							<ul class="nk-nav nav nav-tabs" role="tablist">
								<li class="nav-item" role="presentation">
									<a class="nav-link active" data-bs-toggle="tab" href="#mlm_user_account_basic" aria-selected="true" role="tab">Basic</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" data-bs-toggle="tab" href="#mlm_user_account_international" aria-selected="false" tabindex="-1" role="tab">International</a>
								</li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane active" id="mlm_user_account_basic" role="tabpanel">
									<input type="hidden" id="mlm_user_account_id" name="mlm_user_account_id" class="form-control" value="<?= $user_id ?>">
									<div class="row gy-4">
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="mlm_user_account_name">Account Holder's Name</label>
												<input type="text" class="form-control form-control-lg" id="mlm_user_account_name" value="<?= $bank_details['name'] ?>"/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="mlm_user_account_bank_name">Bank Name</label>
												<input type="text" class="form-control form-control-lg" id="mlm_user_account_bank_name" value="<?= $bank_details['bank_name'] ?>"/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="mlm_user_account_account_number">Account Number</label>
												<input type="number" class="form-control form-control-lg" id="mlm_user_account_account_number" value="<?= $bank_details['account_number'] ?>"/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="mlm_user_account_ifsc_code">IFSC Code</label>
												<input type="text" class="form-control form-control-lg" id="mlm_user_account_ifsc_code" value="<?= $bank_details['ifsc_code'] ?>"/>
											</div>
										</div>
										<div class="col-12">
											<ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
												<li>
													<button class="btn btn-lg btn-primary mlm_user_account_submit">Update</button>
												</li>
												<li>
													<a href="#" data-bs-dismiss="modal" class="link link-light">Cancel</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="mlm_user_account_international" role="tabpanel">
									<div class="row gy-4">
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="mlm_user_account_routing_number">Routing Number/ABA number</label>
												<input type="number" class="form-control form-control-lg" id="mlm_user_account_routing_number" value="<?= $bank_details['routing_number'] ?>"/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="mlm_user_account_swift_number">SWIFT/BIC Code</label>
												<input type="text" class="form-control form-control-lg" id="mlm_user_account_swift_number" value="<?= $bank_details['swift_number'] ?>"/>
											</div>
										</div>
										<div class="col-12">
											<ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
												<li><button class="btn btn-lg btn-primary mlm_user_account_submit">Update</button></li>
												<li><a href="#" data-bs-dismiss="modal" class="link link-light">Cancel</a></li>
											</ul>
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

</div>
<?php

require "./footer.php";
