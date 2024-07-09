<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Multi Level Marketing</title>

	<?php
	require "./config/config.php";

	if (isset($_SESSION['mlm_user_id']) && !empty($_SESSION['mlm_user_id'])) {
		// Redirect to dashboard
		$role = fetchSingleRow("SELECT `role` from `users` where id = ? ", [$_SESSION['mlm_user_id']], "i")['role'];
		$url = $role == "admin" ? "./admin/dashboard" : "./user/dashboard";
		header("Location: $url");
		exit; // Make sure to exit after redirecting to prevent further execution of the script
	}

	// Directory containing CSS files
	$directory = './css/';

	// Scan the directory for CSS files
	$cssFiles = glob($directory . '*.css');

	// Loop through each CSS file and output HTML link tags
	foreach ($cssFiles as $cssFile) {
		echo '<link rel="stylesheet" type="text/css" href="' . $cssFile . '">' . PHP_EOL;
	}
	?>

</head>
<body class="nk-body bg-white npc-general pg-auth">
	<div class="nk-app-root">
		<div class="nk-main">
			<div class="nk-wrap nk-wrap-nosidebar">
				<div class="nk-content">

					<div class="nk-split nk-split-page nk-split-lg">
						<!-- Form Area -->
						<div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
							<!-- Login Panel -->
							<div id="mlm_login_panel" class="nk-block nk-block-middle nk-auth-body login-panel">
								<div class="nk-block-head">
									<div class="nk-block-head-content">
										<h5 class="nk-block-title">Sign-In</h5>
									</div>
								</div>

								<form action="#" id="mlm_login_form" class="form-validate is-alter" autocomplete="off" novalidate="novalidate">
									<!-- Email -->
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="mlm_login_email">Email</label>
										</div>
										<div class="form-control-wrap">
											<input autocomplete="off" type="email" class="form-control form-control-lg" required id="mlm_login_email" placeholder="Enter your email address"/>
										</div>
									</div>

									<!-- Password -->
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="mlm_login_password">Passcode</label>
											<a class="mlm_link_forgot_code link link-primary link-sm" tabindex="-1" href="#">Forgot Code?</a>
										</div>
										<div class="form-control-wrap">
											<a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" data-target="mlm_login_password">
												<em class="passcode-icon icon-show icon ni ni-eye"></em>
												<em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
											</a>
											<input autocomplete="new-password" type="password" class="form-control form-control-lg" required="" id="mlm_login_password" placeholder="Enter your passcode"/>
										</div>
									</div>

									<!-- Role -->
									<div class="form-group">
										<label class="form-label" for="mlm_login_role">Role</label>
										<div class="form-control-wrap ">
											<div class="form-control-select">
												<select class="form-control" id="mlm_login_role">
													<option value="customer">Customer</option>
													<option value="admin">Administrator</option>
												</select>
											</div>
										</div>
									</div>

									<!-- Submit -->
									<div class="form-group">
										<button id="mlm_login_submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
									</div>
								</form>

								<form id="mlm_login_verify_otp" class="d-none">
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="mlm_login_otp">Enter OTP (One Time Password)</label>
										</div>
										<div class="form-control-wrap">
											<input type="number" class="form-control form-control-lg" id="mlm_login_otp" placeholder="Enter OTP (One Time Password)">
										</div>
									</div>
									<div class="form-group">
										<button id="mlm_login_otp_submit" class="btn btn-lg btn-primary btn-block">Verify</button>
									</div>
								</form>

								<div class="form-note-s2 pt-4">New on our platform?
									<a class="mlm_login_note" href="#">Create an account</a>
								</div>
							</div>

							<!-- Forgot Password -->
							<div id="mlm_forgot_password_panel" class="nk-block nk-block-middle nk-auth-body d-none">
								<div class="nk-block-head">
									<div class="nk-block-head-content">
										<h5 class="nk-block-title">Reset password</h5>
										<div class="nk-block-des">
											<p>If you forgot your password, well, then we’ll send an OTP (One Time Password) to reset your password.</p>
										</div>
									</div>
								</div>

								<!-- Verify Email -->
								<form id="mlm_forgot_password_verify_email" class="mlm_forgot_password">
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="mlm_forgot_password_email">Email</label>
										</div>
										<div class="form-control-wrap">
											<input type="email" class="form-control form-control-lg" id="mlm_forgot_password_email" placeholder="Enter your email address">
										</div>
									</div>
									<div class="form-group">
										<button id="mlm_forgot_password_email_submit" class="btn btn-lg btn-primary btn-block">Verify</button>
									</div>
								</form>

								<!-- Verify OTP -->
								<form id="mlm_forgot_password_verify_otp" class="mlm_forgot_password d-none">
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="mlm_forgot_password_otp">Enter OTP (One Time Password)</label>
										</div>
										<div class="form-control-wrap">
											<input type="number" class="form-control form-control-lg" id="mlm_forgot_password_otp" placeholder="Enter OTP (One Time Password)">
										</div>
									</div>
									<div class="form-group">
										<button id="mlm_forgot_password_otp_submit" class="btn btn-lg btn-primary btn-block">Verify</button>
									</div>
								</form>

								<!-- Reset Password -->
								<form id="mlm_forgot_password_verify_password" class="mlm_forgot_password d-none">
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="mlm_forgot_password">New Password</label>
										</div>
										<div class="form-control-wrap">
											<a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" data-target="mlm_forgot_password">
												<em class="passcode-icon icon-show icon ni ni-eye"></em>
												<em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
											</a>
											<input type="password" class="form-control form-control-lg" id="mlm_forgot_password" placeholder="Enter your New Password">
										</div>
									</div>
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="mlm_forgot_confirm_password">Confirm New Password</label>
										</div>
										<div class="form-control-wrap">
											<a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" data-target="mlm_forgot_confirm_password">
												<em class="passcode-icon icon-show icon ni ni-eye"></em>
												<em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
											</a>
											<input type="password" class="form-control form-control-lg" id="mlm_forgot_confirm_password" placeholder="Confirm your new Password">
										</div>
									</div>
									<div class="form-group">
										<button id="mlm_forgot_password_submit" class="btn btn-lg btn-primary btn-block">Reset Password</button>
									</div>
								</form>

								<div class="form-note-s2 pt-5">
									<a class="mlm_link_forgot_code" href="#"><strong>Return to login</strong></a>
								</div>
							</div>

							<!-- Register Panel -->
							<div id="mlm_register_panel" class="nk-block nk-block-middle nk-auth-body register-panel d-none">
								<div class="nk-block-head">
									<div class="nk-block-head-content">
										<h5 class="nk-block-title">Register</h5>
										<div class="nk-block-des"><p>Create New Account</p></div>
									</div>
								</div>
								<form id="mlm_register_form">
									<?php
									if (isset($_GET["account_register_token"])) {
										$sponser_id = urldecode(base64_decode($_GET["account_register_token"]));
										$sql = "SELECT `name` FROM `users` WHERE `id` = ?";
										$stmt = $conn->prepare($sql);
										$stmt->bind_param("i", $sponser_id);
										$stmt->execute();
										$result = $stmt->get_result();
										$row = $result->fetch_assoc();
										$sponser_name = $row['name'];
										$stmt->close();
										?>
										<div class="form-group">
											<label class="form-label">Sponser : <?= ucfirst($sponser_name) ?></label>
											<div class="form-control-wrap">
												<input type="hidden" class="form-control form-control-lg" id="mlm_register_sponser_id" value="<?= $sponser_id ?>" readonly disabled/>
											</div>
										</div>
										<?php
									}else {
										?>
										<div class="form-group d-none">
											<label class="form-label" for="mlm_register_sponser_id">Sponser</label>
											<div class="form-control-wrap">
												<input type="text" class="form-control form-control-lg" id="mlm_register_sponser_id" value="0" readonly disabled/>
											</div>
										</div>
										<?php
									}
									?>
									<div class="form-group">
										<label class="form-label" for="mlm_register_name">Name</label>
										<div class="form-control-wrap">
											<input type="text" class="form-control form-control-lg" id="mlm_register_name" placeholder="Enter your name"/>
										</div>
									</div>
									<div class="form-group">
										<label class="form-label" for="mlm_register_email">Email</label>
										<div class="form-control-wrap">
											<input type="email" class="form-control form-control-lg" id="mlm_register_email" placeholder="Enter your email address"/>
										</div>
									</div>
									<div class="form-group">
										<label class="form-label" for="mlm_register_password">Passcode</label>
										<div class="form-control-wrap">
											<a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" data-target="mlm_register_password">
												<em class="passcode-icon icon-show icon ni ni-eye"></em>
												<em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
											</a>
											<input type="password" class="form-control form-control-lg" id="mlm_register_password" placeholder="Enter your passcode"/>
										</div>
									</div>
									<div class="form-group">
										<div class="custom-control custom-control-xs custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="mlm_register_checkbox"/>
											<label class="custom-control-label" for="mlm_register_checkbox">I agree to
												<a tabindex="-1" href="#">Privacy Policy</a>&amp;
												<a tabindex="-1" href="#"> Terms.</a>
											</label>
										</div>
									</div>
									<div class="form-group">
										<button id="mlm_register_submit" class="btn btn-lg btn-primary btn-block">Register</button>
									</div>
								</form>
								<div class="form-note-s2 pt-4">
									Already have an account ?
									<a class="mlm_login_note" href="#">
										<strong>Sign in instead</strong>
									</a>
								</div>
							</div>
						</div>
		
						<!-- Banner Logo -->
						<div class="nk-split-content nk-split-stretch bg-lighter d-flex toggle-break-lg toggle-slide
						 toggle-slide-right">
							<div class="container mlm-auth-logo">
								<img class="w-75" src="./images/logo.png" alt="logo">
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<?php
	// Directory containing JavaScript files
	$directory = './js/';

	// Scan the directory for JavaScript files
	$jsFiles = glob($directory . '*.js');

	// Loop through each JavaScript file and output HTML script tags
	foreach ($jsFiles as $jsFile) {
		echo '<script src="' . $jsFile . '"></script>' . PHP_EOL;
	}
	?>

</body>
</html>
