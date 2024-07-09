<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Multi Level Marketting</title>
	<?php
	require "../config/config.php";

	// Check if session is already started
	if (!isset($_SESSION['mlm_user_id'])) {
		// Redirect to index.php
		header("Location: ../");
		exit; // Make sure to exit after redirecting to prevent further execution of the script
	}

	$user_id = $_SESSION['mlm_user_id'];

	// Directory containing CSS files
	$directory = '../css/';

	// Scan the directory for CSS files
	$cssFiles = glob($directory . '*.css');

	// Loop through each CSS file and output HTML link tags
	foreach ($cssFiles as $cssFile) {
		echo '<link rel="stylesheet" type="text/css" href="' . $cssFile . '">' . PHP_EOL;
	}

	// Getting user details
	$sql = "SELECT * FROM `users` WHERE `id` = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();
	$stmt->close();

	$activity = fetchSingleRow("SELECT `value` FROM `options` WHERE name = ?", ["{$user_id}_activity_log"], "s")['value'];
	?>
</head>

<body class="nk-body bg-lighter has-sidebar">
	<div class="nk-app-root">
		<div class="nk-main">
			<!-- Sidebar -->
			<div class="nk-sidebar nk-sidebar-fixed is-dark">

				<!-- Sidebar Header -->
				<div class="nk-sidebar-element nk-sidebar-head">
					<div class="nk-menu-trigger">
						<a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex">
							<em class="icon ni ni-menu"></em>
						</a>
					</div>
					<div class="nk-sidebar-brand">
						<a href="./" class="logo-link nk-sidebar-logo">
							<img class="logo-light logo-img" src="../images/bg-dark.png" alt="logo">
						</a>
					</div>
				</div>

				<!-- Sidebar Body Content -->
				<div class="nk-sidebar-element nk-sidebar-body">
					<div class="nk-sidebar-content">
						<div class="nk-sidebar-menu">

							<div class="simplebar-wrapper">
								<div class="simplebar-mask">
									<div class="simplebar-offset">
										<div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content">
											<div class="simplebar-content">

												<!-- Menu Items -->
												<ul class="nk-menu">
													<!-- Menu Heading -->
													<li class="nk-menu-heading"></li>

													<!-- Dashboard -->
													<li class="nk-menu-item">
														<a class="nk-menu-link" href="./dashboard">
															<span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
															<span class="nk-menu-text">Dashboard</span>
														</a>
													</li>

													<!-- Users -->
													<li class="nk-menu-item">
														<a class="nk-menu-link" href="./users">
															<span class="nk-menu-icon"><em class="icon ni ni-user-list"></em></span>
															<span class="nk-menu-text">Users</span>
														</a>
													</li>

													<!-- Transactions -->
													<li class="nk-menu-item has-sub">
														<a class="nk-menu-link nk-menu-toggle" href="#">
															<span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
															<span class="nk-menu-text">Transactions</span>
														</a>

														<ul class="nk-menu-sub">
															<li class="nk-menu-item">
																<a href="./referral_transactions" class="nk-menu-link"
																><span class="nk-menu-text">Referral Transactions</span></a
																>
															</li>
															<li class="nk-menu-item">
																<a href="./withdraw_transactions" class="nk-menu-link"
																><span class="nk-menu-text">Withdraw Transactions</span></a
																>
															</li>
														</ul>
													</li>

													<!-- Options -->
													<li class="nk-menu-item">
														<a class="nk-menu-link" href="./options">
															<span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
															<span class="nk-menu-text">Options</span>
														</a>
													</li>

													<!-- Profile -->
													<li class="nk-menu-item">
														<a class="nk-menu-link" href="./profile">
															<span class="nk-menu-icon"><em class="icon ni ni-account-setting"></em></span>
															<span class="nk-menu-text">Profile</span>
														</a>
													</li>

													<?php
													if ($activity) {
														?>
														<!-- Activity Logs -->
														<li class="nk-menu-item">
															<a class="nk-menu-link" href="./activity_logs">
																<span class="nk-menu-icon"><em class="icon ni ni-activity-round-fill"></em></span>
																<span class="nk-menu-text">Activity Logs</span>
															</a>
														</li>
														<?php
													}
													?>

													<!-- Withdraw Requests -->
													<li class="nk-menu-item">
														<a class="nk-menu-link" href="./withdraw_requests">
															<span class="nk-menu-icon"><em class="icon ni ni-archive-fill"></em></span>
															<span class="nk-menu-text">Withdraw Requests</span>
														</a>
													</li>

													<!-- Logout -->
													<li class="nk-menu-item">
														<a class="nk-menu-link" href="./logout">
															<span class="nk-menu-icon"><em class="icon ni ni-signout"></em></span>
															<span class="nk-menu-text">Logout</span>
														</a>
													</li>
												</ul>

											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="simplebar-track simplebar-horizontal">
								<div class="simplebar-scrollbar"></div>
							</div>
							<div class="simplebar-track simplebar-vertical">
								<div class="simplebar-scrollbar"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Body Content -->
			<div class="nk-wrap ">

				<!-- Header -->
				<div class="nk-header nk-header-fixed is-light">
					<div class="container-fluid">
						<div class="nk-header-wrap">

						<div class="nk-header-news d-none d-xl-block">
							<div class="nk-news-list">
								<a class="nk-news-item" href="#">
									<div class="nk-news-text">
										<img src="../images/bg-light.png" width="150" alt="logo">
									</div>
								</a>
							</div>
						</div>

							<!-- User Profile -->
							<div class="nk-header-tools">
									<ul class="nk-quick-nav">
										<li class="dropdown user-dropdown">
											<a href="./profile" class="dropdown-toggle show" data-bs-toggle="dropdown" aria-expanded="true">
												<div class="user-toggle">
													<div class="user-avatar sm">
														<em class="icon ni ni-user-alt"></em>
													</div>
													<div class="user-info d-none d-md-block">
														<div class="user-name"><?= ucfirst($user['name']) ?></div>
													</div>
												</div>
											</a>
										</li>
									</ul>
								</div>

							</div>
					</div>
				</div>

				<div class="nk-content">