"use strict";

function accountBalance(selector, set_data) {
	var $selector = $(selector || ".chart-account-balance");
	$selector.each(function () {
		for (
			var $self = $(this), _self_id = $self.attr("id"), _get_data = void 0 === set_data ? eval(_self_id) : set_data, selectCanvas = document.getElementById(_self_id).getContext("2d"), chart_data = [], i = 0;
			i < _get_data.datasets.length;
			i++
		)
			chart_data.push({
				label: _get_data.datasets[i].label,
				data: _get_data.datasets[i].data,
				backgroundColor: _get_data.datasets[i].color,
				borderWidth: 2,
				borderColor: "transparent",
				hoverBorderColor: "transparent",
				borderSkipped: "bottom",
				barPercentage: NioApp.State.asMobile ? 1 : 0.75,
				categoryPercentage: NioApp.State.asMobile ? 1 : 0.75,
			});
		var chart = new Chart(selectCanvas, {
			type: "bar",
			data: { labels: _get_data.labels, datasets: chart_data },
			options: {
				plugins: {
					legend: { display: !1 },
					tooltip: {
						enabled: !0,
						rtl: NioApp.State.isRTL,
						callbacks: {
							label: function (a) {
								return "".concat(a.parsed.y, " ").concat(_get_data.dataUnit);
							},
						},
						backgroundColor: "#eff6ff",
						titleFont: { size: 13 },
						titleColor: "#6783b8",
						titleMarginBottom: 6,
						bodyColor: "#9eaecf",
						bodyFont: { size: 12 },
						bodySpacing: 4,
						padding: 10,
						footerMarginTop: 0,
						displayColors: !1,
					},
				},
				maintainAspectRatio: !1,
				scales: { y: { display: !1 }, x: { display: !1, ticks: { reverse: NioApp.State.isRTL } } },
			},
		});
	});
}

function referStats(selector, set_data) {
	var $selector = $(selector || ".chart-refer-stats");
	$selector.each(function () {
		for (
			var $self = $(this), _self_id = $self.attr("id"), _get_data = void 0 === set_data ? eval(_self_id) : set_data, selectCanvas = document.getElementById(_self_id).getContext("2d"), chart_data = [], i = 0;
			i < _get_data.datasets.length;
			i++
		)
			chart_data.push({
				label: _get_data.datasets[i].label,
				data: _get_data.datasets[i].data,
				backgroundColor: _get_data.datasets[i].color,
				borderWidth: 2,
				borderRadius: 5,
				borderColor: "transparent",
				hoverBorderColor: "transparent",
				borderSkipped: "bottom",
				barPercentage: 0.8,
				categoryPercentage: 0.8,
			});
		var chart = new Chart(selectCanvas, {
			type: "bar",
			data: { labels: _get_data.labels, datasets: chart_data },
			options: {
				plugins: {
					legend: { display: !1 },
					tooltip: {
						enabled: !0,
						rtl: NioApp.State.isRTL,
						callbacks: {
							label: function (a) {
								return "".concat(a.parsed.y, " ").concat(_get_data.dataUnit);
							},
						},
						backgroundColor: "#fff",
						titleFont: { size: 13 },
						titleColor: "#6783b8",
						titleMarginBottom: 6,
						bodyColor: "#9eaecf",
						bodyFont: { size: 12 },
						bodySpacing: 4,
						padding: 10,
						footerMarginTop: 0,
						displayColors: !1,
					},
				},
				maintainAspectRatio: !1,
				scales: { y: { display: !1, ticks: { beginAtZero: !0 } }, x: { display: !1, ticks: { reverse: NioApp.State.isRTL } } },
			},
		});
	});
}

function calculatePercentage(part, whole) {
	return (part * whole) / 100;
}

function isValidURL(str) {
    const regex = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;
    return regex.test(str);
}

function accountSummary(selector, set_data) {
	var $selector = $(selector || ".chart-account-summary");
	$selector.each(function () {
		for (
			var $self = $(this), _self_id = $self.attr("id"), _get_data = void 0 === set_data ? eval(_self_id) : set_data, selectCanvas = document.getElementById(_self_id).getContext("2d"), chart_data = [], i = 0;
			i < _get_data.datasets.length;
			i++
		)
			chart_data.push({
				label: _get_data.datasets[i].label,
				tension: 0.4,
				backgroundColor: "transparent",
				fill: !0,
				borderWidth: 2,
				borderColor: _get_data.datasets[i].color,
				pointBorderColor: "transparent",
				pointBackgroundColor: "transparent",
				pointHoverBackgroundColor: "#fff",
				pointHoverBorderColor: _get_data.datasets[i].color,
				pointBorderWidth: 2,
				pointHoverRadius: 4,
				pointHoverBorderWidth: 2,
				pointRadius: 4,
				pointHitRadius: 4,
				data: _get_data.datasets[i].data,
			});
		var chart = new Chart(selectCanvas, {
			type: "line",
			data: { labels: _get_data.labels, datasets: chart_data },
			options: {
				plugins: {
					legend: { display: !1 },
					tooltip: {
						rtl: NioApp.State.isRTL,
						callbacks: {
							label: function (a) {
								return "".concat(a.parsed.y, " ").concat(_get_data.dataUnit);
							},
						},
						backgroundColor: "#eff6ff",
						titleFont: { size: 13 },
						titleColor: "#6783b8",
						titleMarginBottom: 6,
						bodyColor: "#9eaecf",
						bodyFont: { size: 12 },
						bodySpacing: 4,
						padding: 10,
						footerMarginTop: 0,
						displayColors: !1,
					},
				},
				maintainAspectRatio: !1,
				scales: {
					y: {
						position: NioApp.State.isRTL ? "right" : "left",
						ticks: { beginAtZero: !1, font: { size: 12 }, color: "#9eaecf", padding: 10 },
						grid: { color: NioApp.hexRGB("#526484", 0.2), tickLength: 0, zeroLineColor: NioApp.hexRGB("#526484", 0.2), drawTicks: !1 },
					},
					x: {
						ticks: { font: { size: 12 }, color: "#9eaecf", source: "auto", padding: 5, reverse: NioApp.State.isRTL },
						grid: { color: "transparent", tickLength: 20, zeroLineColor: NioApp.hexRGB("#526484", 0.2), offset: !0, drawTicks: !1 },
					},
				},
			},
		});
	});
}

!(function (NioApp, $) {
	const mainBalance = {
        labels: [
            "01 Nov",
            "02 Nov",
            "03 Nov",
            "04 Nov",
            "05 Nov",
            "06 Nov",
            "07 Nov",
            "08 Nov",
            "09 Nov",
            "10 Nov",
            "11 Nov",
            "12 Nov",
            "13 Nov",
            "14 Nov",
            "15 Nov",
            "16 Nov",
            "17 Nov",
            "18 Nov",
            "19 Nov",
            "20 Nov",
            "21 Nov",
            "22 Nov",
            "23 Nov",
            "24 Nov",
            "25 Nov",
            "26 Nov",
            "27 Nov",
            "28 Nov",
            "29 Nov",
            "30 Nov",
        ],
        dataUnit: "₹",
        datasets: [
            { label: "Send", color: "#6baafe", data: [110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95, 75, 90, 75, 90] },
        ],
    };

	NioApp.coms.docReady.push(function () {
        accountBalance($("#mainBalance"), mainBalance);
    });

	const refBarChart = {
        labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "July",
            "Aug",
            "Sept",
            "Oct",
            "Nov",
            "Dec",
        ],
        dataUnit: "Users",
        datasets: [{ label: "Join", color: "#6baafe", data: [110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95, 75, 90, 75, 90] }],
    };

	NioApp.coms.docReady.push(function () {
        referStats($("#refBarChart"), refBarChart);
    });

	const summaryBalance = {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        dataUnit: "₹",
        datasets: [
            { label: "Total Received", color: "#5ce0aa", data: [110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95] },
            { label: "Total Withdraw", color: "#f6ca3e", data: [90, 98, 115, 70, 87, 95, 67, 90, 98, 115, 70, 87] },
        ],
    };

	NioApp.coms.docReady.push(function () {
        accountSummary($("#summaryBalance"), summaryBalance);
    });

	const queryParams = new URLSearchParams(window.location.search);

	if (queryParams.has('account_register_token')) {
		$("#mlm_login_panel").addClass("d-none");
		$("#mlm_register_panel").removeClass("d-none");
	}

	$("#mlm_forgot_password_submit").on("click", function(e) {
		e.preventDefault();
		const password = $("#mlm_forgot_password").val();
		const confirm_password = $("#mlm_forgot_confirm_password").val();
		if (password != confirm_password) {
			Swal.fire({
				title: "Sorry!",
				text: "Your Passwords doesn't match!",
				icon: "error"
			});
		}else{
			$.ajax({
				url: '../config/backend.php',
				method: 'POST',
				data: {
					"mlm_forgot_password_verify_password": JSON.stringify({password : password})
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message, data } = result;

					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error",
						allowOutsideClick: false
					}).then((result) => {
						// Redirect to dashboard after successfull login
						if (status && data) {
							window.location.href = data;
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}

	});

	$("#mlm_forgot_password_otp_submit").on("click", function(e) {
		e.preventDefault();
		const otp = $("#mlm_forgot_password_otp").val();
		if (otp == "") {
			Swal.fire({
				title: "Sorry!",
				text: "Invalid OTP!",
				icon: "error"
			});
		}else{
			$.ajax({
				url: '../config/backend.php',
				method: 'POST',
				data: {
					"mlm_forgot_password_verify_otp": JSON.stringify({otp : otp})
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message, data } = result;

					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error"
					}).then((result) => {
						// Redirect to dashboard after successfull login
						if (status && data) {
							$(".mlm_forgot_password").addClass("d-none");
							$("#mlm_forgot_password_verify_password").removeClass("d-none");
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}
	});

	$("#mlm_forgot_password_email_submit").on("click", function(e) {
		e.preventDefault();
		const email = $("#mlm_forgot_password_email").val();
		if (email == "") {
			Swal.fire({
				title: "Sorry!",
				text: "Email cannot be empty!",
				icon: "error"
			});
		}else{			
			$.ajax({
				url: '../config/backend.php',
				method: 'POST',
				data: {
					"mlm_forgot_password_verify_email": JSON.stringify({email : email})
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message, data } = result;

					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error"
					}).then((result) => {
						// Redirect to dashboard after successfull login
						if (status && data) {
							$(".mlm_forgot_password").addClass("d-none");
							$("#mlm_forgot_password_verify_otp").removeClass("d-none");
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}

	});

	$("#mlm_user_profile_2fa").on("click", function () {
		$.ajax({
			url: '../config/backend.php',
			method: 'POST',
			data: {
				"mlm_user_profile_2fa": JSON.stringify({
					user_id : $(this).attr("data-id"),
					val : $(this).text() == "Enable" ? "true" : "false",
				})
			},
			success: function(response) {
				console.log(response);
				const result = JSON.parse(response);
				const { status, message, data } = result;
				// Display success or error message
				Swal.fire({
					title: status ? "Done!" : "Sorry!",
					text: message,
					icon: status ? "success" : "error",
					allowOutsideClick: false
				}).then((result) => {
					// Redirect to dashboard after successfull login
					if (status && data) {
						window.location.href = data;
					}
				});
			},
			error: function(xhr, status, error) {
				console.error('Error:', error);
				Swal.fire({
					title: "Error!",
					text: "An error occurred while processing your request.",
					icon: "error"
				});
			}
		});
	});

	$("#mlm_user_profile_change_password_submit").on("click", function (e) {
		e.preventDefault();
		const data = {};
		let send = true;

		$("#mlm_user_profile_change_password_form").find(".form-control").each(function(index, element) {
			const val = $(element).val().trim();
			const id = $(element).attr("id").replace("mlm_user_profile_change_password_", "");

			if (val === "") {
				send = false;
				$(`<span id="${id}-error" class="invalid">This field is required.</span>`).insertAfter($(element));
			} else {
				data[id] = val;
			}
		});

		if (data["new"] != data["confirm_new"]) {
			Swal.fire({
				title: "Sorry!",
				text: "Your Passwords doesn't match.",
				icon: "error"
			});
		}else if (send) {
			data["user_id"] = $(this).attr("data-id");
			$.ajax({
				url: '../config/backend.php',
				method: 'POST',
				data: {
					"mlm_user_profile_change_password": JSON.stringify(data)
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message, data } = result;
		
					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error",
						allowOutsideClick: false
					}).then((result) => {
						// Redirect to dashboard after successfull login
						if (status && data) {
							window.location.href = data;
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}

	});

	$("#mlm_user_profile_activity_log").on("change", function () {
		let value = false;
		if ($(this).is(":checked")) {
			value = true;
		}else {
			value = false;
		}

		$.ajax({
			url: '../config/backend.php',
			method: 'POST',
			data: {
				"mlm_user_profile_activity_log": JSON.stringify({
					user_id : $(this).attr("data-id"),
					val : value,
				})
			},
			success: function(response) {
				console.log(response);
				const result = JSON.parse(response);
				const { status, message, data } = result;
	
				// Display success or error message
				Swal.fire({
					title: status ? "Done!" : "Sorry!",
					text: message,
					icon: status ? "success" : "error",
					allowOutsideClick: false
				}).then((result) => {
					// Redirect to dashboard after successfull login
					if (status && data) {
						window.location.href = data;
					}
				});
			},
			error: function(xhr, status, error) {
				console.error('Error:', error);
				Swal.fire({
					title: "Error!",
					text: "An error occurred while processing your request.",
					icon: "error"
				});
			}
		});

	});

	$(document).on("click", ".mlm_admin_withdraw_request", function() {
		const element = $(this);
		const status = element.attr("data-status");
		const id = element.parent("td").siblings(".id").text();
		const user_id = element.parent("td").siblings(".user_id").text();
		const amount = element.parent("td").siblings(".amount").text().replace("₹", "");

		$.ajax({
			url: '../config/backend.php',
			method: 'POST',
			data: {
				"mlm_admin_withdraw_request": JSON.stringify({
					id : id,
					status : status,
					user_id : user_id,
					amount : amount,
				})
			},
			success: function(response) {
				console.log(response);
				const result = JSON.parse(response);
				const { status, message, data } = result;
	
				// Display success or error message
				Swal.fire({
					title: status ? "Done!" : "Sorry!",
					text: message,
					icon: status ? "success" : "error",
					allowOutsideClick: false
				}).then((result) => {
					// Redirect to dashboard after successfull login
					if (status && data) {
						window.location.href = data;
					}
				});
			},
			error: function(xhr, status, error) {
				console.error('Error:', error);
				Swal.fire({
					title: "Error!",
					text: "An error occurred while processing your request.",
					icon: "error"
				});
			}
		});
	});
	
	$("#mlm_admin_options_submit").on("click", function() {
		$.ajax({
			url: '../config/backend.php',
			method: 'POST',
			data: {
				"mlm_admin_options": JSON.stringify({
					referral_amount : $("#mlm_admin_options_referral_amount").val(),
					minimum_amount_to_withdraw : $("#mlm_admin_options_minimum_amount_to_withdraw").val(),
				})
			},
			success: function(response) {
				console.log(response);
				const result = JSON.parse(response);
				const { status, message, data } = result;
	
				// Display success or error message
				Swal.fire({
					title: status ? "Done!" : "Sorry!",
					text: message,
					icon: status ? "success" : "error",
					allowOutsideClick: false
				}).then((result) => {
					// Redirect to dashboard after successfull login
					if (status && data) {
						location.reload();
					}
				});
			},
			error: function(xhr, status, error) {
				console.error('Error:', error);
				Swal.fire({
					title: "Error!",
					text: "An error occurred while processing your request.",
					icon: "error"
				});
			}
		});
	});
	
	$("#mlm_user_profile_submit").on("click", function() {
		const data = {};
		let send = true;

		$(".invalid").remove();
	
		$("#mlm_user_profile_personal").find(".form-control").each(function(index, element) {
			const val = $(element).val().trim();
			const id = $(element).attr("id").replace("mlm_user_profile_", "");
	
			if (val === "") {
				send = false;
				$(`<span id="${id}-error" class="invalid">This field is required.</span>`).insertAfter($(element));
			} else {
				data[id] = val;
			}
		});
	
		if (send) {
			// Send AJAX request
			$.ajax({
				url: '../config/backend.php',
				method: 'POST',
				data: {
					"mlm_user_profile": JSON.stringify(data)
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message, data } = result;
	
					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error",
						allowOutsideClick: false
					}).then((result) => {
						// Redirect to dashboard after successfull login
						if (status && data) {
							window.location.href = data;
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}
	});
	
	$(".mlm_user_account_submit").on("click", function() {
		const data = {};
		let send = true;
	
		$(".invalid").remove();
	
		$("#mlm_user_account_basic").find(".form-control").each(function(index, element) {
			const val = $(element).val().trim();
			const id = $(element).attr("id").replace("mlm_user_account_", "");
	
			if (val === "") {
				send = false;
				$(`<span id="${id}-error" class="invalid">This field is required.</span>`).insertAfter($(element));
			} else {
				data[id] = val;
			}
		});
		
		$("#mlm_user_account_international").find(".form-control").each(function(index, element) {
			const val = $(element).val().trim();
			const id = $(element).attr("id").replace("mlm_user_account_", "");
			data[id] = val;
		});
	
		if (send) {
			// Send AJAX request
			$.ajax({
				url: '../config/backend.php',
				method: 'POST',
				data: {
					"mlm_user_account": JSON.stringify(data)
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message, data } = result;
	
					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error",
						allowOutsideClick: false
					}).then((result) => {
						// Redirect to dashboard after successfull login
						if (status && data) {
							window.location.href = data;
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}
	});
	
	$("#mlm_withdraw_submit").on("click", function() {
		const id = "mlm_withdraw_amount";
		const amount = $("#mlm_withdraw_net_payable_amount").val();
		const user_id = $(this).attr("data-id");
		const total = $("#mlm_withdraw_amount").val();
		const balance = $("#mlm_withdraw_min_amount").val();

		$(".invalid").remove();

		if (parseInt(total.trim()) < parseInt(balance.trim())) {
			Swal.fire({
				title: "Sorry!",
				text: "Minimum Payout : "+balance+"!",
				icon: "error"
			});
		}
		else if (amount.trim() != "0.00" && amount.trim() != "0") {
			$.ajax({
				url: '../config/backend.php',
				method: 'POST',
				data: {
					"mlm_withdraw": JSON.stringify({
						"amount" : total,
						"user_id" : user_id,
					})
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message } = result;
	
					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error",
						allowOutsideClick: false
					}).then((result) => {
						// Reload the page after displaying the message
						if (result.isConfirmed) {
							location.reload();
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}
		else {
			$(`<span id="${id}-error" class="invalid">This field is required.</span>`).insertAfter($("#"+id));
		}
	});
	
	$("#mlm_withdraw_amount").on("keyup", function() {
		const id = "mlm_withdraw_amount";
		const total = $(this).val();
		const percent = calculatePercentage(5, total);
		const balance = $("#mlm_withdraw_balance").val();
	
		$("#mlm_withdraw_admin_charges").val(percent);
		$("#mlm_withdraw_net_payable_amount").val(total - percent);

		$(".invalid").remove();

		if (parseInt(total.trim()) > parseInt(balance.trim())) {
			$(`<span id="${id}-error" class="invalid">Insufficient Balance!</span>`).insertAfter($("#"+id));
		}else{
			$(".invalid").remove();
		}
	});
	
	$("#mlm_add_new_user_submit").on("click", function(e) {
		e.preventDefault();
		const data = {};
		let send = true;
	
		// Clear previous error messages
		$(".invalid").remove();
	
		// Validate form fields
		$("#mlm_add_new_user_form").find(".form-control").each(function(index, element) {
			const val = $(element).val().trim();
			const id = $(element).attr("id").replace("mlm_add_new_user_", "");
	
			if (val === "") {
				send = false;
				$(`<span id="${id}-error" class="invalid">This field is required.</span>`).insertAfter($(element));
			} else {
				data[id] = val;
			}
		});
	
		if (send) {
			// Send AJAX request
			$.ajax({
				url: '../config/backend.php',
				method: 'POST',
				data: {
					"mlm_add_new_user": JSON.stringify(data)
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message } = result;
	
					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error",
						allowOutsideClick: false
					}).then((result) => {
						// Reload the page after displaying the message
						if (result.isConfirmed) {
							location.reload();
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}
	});
	
	$(".mlm_login_note").on("click", function(e) {
		e.preventDefault();
		const login = $("#mlm_login_panel");
		const register = $("#mlm_register_panel");
		if (login.hasClass("d-none")) {
			login.removeClass("d-none");
			register.addClass("d-none");
		}else{
			register.removeClass("d-none");
			login.addClass("d-none");
		}
	});
	
	$(".mlm_link_forgot_code").on("click", function(e) {
		e.preventDefault();
		const login = $("#mlm_login_panel");
		const forgot = $("#mlm_forgot_password_panel");
		if (login.hasClass("d-none")) {
			login.removeClass("d-none");
			forgot.addClass("d-none");
		}else{
			forgot.removeClass("d-none");
			login.addClass("d-none");
		}
	});
	
	$("#mlm_login_submit").on("click", function(e) {
		e.preventDefault();
		const data = {};
		let send = true;
	
		// Clear previous error messages
		$(".invalid").remove();
	
		// Validate form fields
		$("#mlm_login_form").find(".form-control").each(function(index, element) {
			const val = $(element).val().trim();
			const id = $(element).attr("id").replace("mlm_login_", "");
	
			if (val === "") {
				send = false;
				$(`<span id="${id}-error" class="invalid">This field is required.</span>`).insertAfter($(element));
			} else {
				data[id] = val;
			}
		});
	
		if (send) {
			// Send AJAX request
			$.ajax({
				url: './config/backend.php',
				method: 'POST',
				data: {
					"mlm_login": JSON.stringify(data)
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message, data } = result;
	
					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error",
						allowOutsideClick: false
					}).then((result) => {
						// Redirect to dashboard after successfull login
						if (status && data) {
							if (isValidURL(data)) {
								window.location.href = data;
							}else {
								$("#mlm_login_form").addClass("d-none");
								$("#mlm_login_verify_otp").removeClass("d-none");
							}
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}
	
	});
	
	$("#mlm_register_submit").on("click", function(e) {
		e.preventDefault();
		const data = {};
		let send = true;
	
		// Clear previous error messages
		$(".invalid").remove();
	
		// Validate form fields
		$("#mlm_register_form").find(".form-control").each(function(index, element) {
			const val = $(element).val().trim();
			const id = $(element).attr("id").replace("mlm_register_", "");
			if (val === "") {
				send = false;
				$(`<span id="${id}-error" class="invalid">This field is required.</span>`).insertAfter($(element));
			} else {
				data[id] = val;
			}
		});
	
		if (!$("#mlm_register_checkbox").is(":checked")) {
			send = false;
			$(`<span id="mlm_register_checkbox-error" class="invalid">This field is required.</span>`).insertAfter($("#mlm_register_checkbox"));
		}
	
		if (send) {
			// Send AJAX request
			$.ajax({
				url: './config/backend.php',
				method: 'POST',
				data: {
					"mlm_register": JSON.stringify(data)
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message } = result;
	
					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error",
						allowOutsideClick: false
					}).then((result) => {
						// Reload the page after displaying the message
						if (result.isConfirmed) {
							if (queryParams.has('account_register_token')) {
								window.location.href = window.location.origin;
							}else{
								location.reload();
							}
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}
		
	
	});

	$("#mlm_login_otp_submit").on("click", function(e) {
		e.preventDefault();
		const otp = $("#mlm_login_otp").val();
		if (otp == "") {
			Swal.fire({
				title: "Sorry!",
				text: "Invalid OTP!",
				icon: "error"
			});
		}else{
			$.ajax({
				url: '../config/backend.php',
				method: 'POST',
				data: {
					"mlm_login_otp": JSON.stringify({otp : otp})
				},
				success: function(response) {
					console.log(response);
					const result = JSON.parse(response);
					const { status, message, data } = result;

					// Display success or error message
					Swal.fire({
						title: status ? "Done!" : "Sorry!",
						text: message,
						icon: status ? "success" : "error",
						allowOutsideClick: false
					}).then((result) => {
						// Redirect to dashboard after successfull login
						if (status && data) {
							window.location.href = data;
						}
					});
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					Swal.fire({
						title: "Error!",
						text: "An error occurred while processing your request.",
						icon: "error"
					});
				}
			});
		}
	});

	$("#mlm_user_delete_account").on("click", function(e) {
		e.preventDefault();

		Swal.fire({
			title: 'Are you sure?',
			text: 'Do you really want to delete your account? This action cannot be undone.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			customClass: {
				confirmButton: 'btn btn-danger', // Optional: customize the button styles
				cancelButton: 'btn btn-secondary',
			}
		}).then((result) => {
			if (result.isConfirmed) {
				// User clicked "Yes" button
				// Send an AJAX request to delete the account
				$.ajax({
					url: '../config/backend.php',
					method: 'POST',
					data: {
						"mlm_user_delete_account": JSON.stringify({id : $(this).attr("data-id")})
					},
					success: function(response) {
						console.log(response);
						const result = JSON.parse(response);
						const { status, message, data } = result;
		
						// Display success or error message
						Swal.fire({
							title: status ? "Done!" : "Sorry!",
							text: message,
							icon: status ? "success" : "error",
							allowOutsideClick: false
						}).then((result) => {
							if (status && data) {
								window.location.href = data;
							}
						});
					},
					error: function(xhr, status, error) {
						console.error('Error:', error);
						Swal.fire({
							title: "Error!",
							text: "An error occurred while processing your request.",
							icon: "error"
						});
					}
				});
			}
			// If the user clicked "No" or closed the popup, no further action is required
		});

	});
})(NioApp, jQuery);
