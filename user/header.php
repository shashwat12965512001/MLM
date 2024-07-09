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

<body class="nk-body npc-crypto bg-white has-sidebar no-touch nk-nio-theme">
	<div class="nk-app-root">
		<div class="nk-main">
			<!-- Sidebar -->
			<div class="nk-sidebar nk-sidebar-fixed">

				<!-- Sidebar Header -->
				<div class="nk-sidebar-element nk-sidebar-head">
					<div class="nk-menu-trigger">
						<a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex">
							<em class="icon ni ni-menu"></em>
						</a>
					</div>
					<div class="nk-sidebar-brand">
						<a href="#" class="logo-link nk-sidebar-logo">
							<img class="logo-img" src="../images/bg-light-user.png" alt="logo">
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

													<!-- My Account -->
													<li class="nk-menu-item">
														<a class="nk-menu-link" href="./my_account">
															<span class="nk-menu-icon"><em class="icon ni ni-user-c"></em></span>
															<span class="nk-menu-text">My Account</span>
														</a>
													</li>

													<!-- Transactions -->
													<li class="nk-menu-item">
														<a class="nk-menu-link" href="./transactions">
															<span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
															<span class="nk-menu-text">Transactions</span>
														</a>
													</li>

													<!-- Referred Users -->
													<li class="nk-menu-item">
														<a class="nk-menu-link" href="./referred_users">
															<span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
															<span class="nk-menu-text">Referred Users</span>
														</a>
													</li>

													<!-- Withdraw -->
													<li class="nk-menu-item">
														<a class="nk-menu-link" href="./withdraw">
															<span class="nk-menu-icon"><em class="icon ni ni-archive-fill"></em></span>
															<span class="nk-menu-text">Withdraw</span>
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

						<!-- Logo -->
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
									<li>
										<a href="./profile" aria-expanded="true">
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

				<div class="nk-content nk-content-fluid">