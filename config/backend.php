<?php
require "./config.php";

$return = [
    "status" => false,
    "message" => "",
    "data" => []
];

function calculatePercentage($part, $whole){
    return ($part * $whole) / 100;
}

function referralLink($user_id) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $domain_url = $protocol . '://' . $_SERVER['HTTP_HOST'];
    $referral_link = $domain_url . "?account_register_token=" . urlencode(base64_encode($user_id));
    $name = "{$user_id}_referral_link";
    $sql = "UPDATE `options` SET `value` = ? WHERE `name` = ?";
    executeQuery($sql, [$referral_link, $name], "ss");
}

function resetPassword($user_id, $password, $newPassword) {
    // Verifying old password
    $hashedPassword = fetchSingleRow("SELECT `value` FROM `options` WHERE `name` = ?", ["{$user_id}_password"], "s");
    if ($hashedPassword) {
        $salt = fetchSingleRow("SELECT `value` FROM `options` WHERE `name` = ?", ["{$user_id}_salt"], "s");
    }
    if ($hashedPassword && isset($salt) && password_verify($password . $salt['value'], $hashedPassword['value'])) {
        $salt = bin2hex(random_bytes(16));
        $hashedPassword = password_hash($newPassword . $salt, PASSWORD_BCRYPT, ['cost' => 12]);        
        // Insert hashed password into the options table
        $sql = "UPDATE `options` SET `value` = ? WHERE `name` = ?";
        executeQuery($sql, [$hashedPassword, "{$user_id}_password"], "ss");

        // Insert salt into the options table
        $sql = "UPDATE `options` SET `value` = ? WHERE `name` = ?";
        executeQuery($sql, [$salt, "{$user_id}_salt"], "ss");
        return true;
    }else {
        return false;
    }
}

function updatePassword($user_id, $newPassword) {
    $salt = bin2hex(random_bytes(16));
    $hashedPassword = password_hash($newPassword . $salt, PASSWORD_BCRYPT, ['cost' => 12]);        
    // Insert hashed password into the options table
    $sql = "UPDATE `options` SET `value` = ? WHERE `name` = ?";
    executeQuery($sql, [$hashedPassword, "{$user_id}_password"], "ss");

    // Insert salt into the options table
    $sql = "UPDATE `options` SET `value` = ? WHERE `name` = ?";
    executeQuery($sql, [$salt, "{$user_id}_salt"], "ss");
}

function insertPassword($user_id, $password) {
    $salt = bin2hex(random_bytes(16));
    $hashedPassword = password_hash($password . $salt, PASSWORD_BCRYPT, ['cost' => 12]);        
    // Insert hashed password into the options table
    $sql = "UPDATE `options` SET `value` = ? WHERE `name` = ?";
    executeQuery($sql, [$hashedPassword, "{$user_id}_password"], "ss");
    
    // Insert salt into the options table
    $sql = "UPDATE `options` SET `value` = ? WHERE `name` = ?";
    executeQuery($sql, [$salt, "{$user_id}_salt"], "ss");
    return true;
}

function sendMail($to, $subject = "", $message = "") {
    $headers = 'From: noreply@' . $_SERVER['HTTP_HOST'] . "\r\n" .
    'Reply-To: noreply@' . $_SERVER['HTTP_HOST'] . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    if (mail($to, $subject, $message, $headers)) {
        return true; // Return the OTP if email sent successfully
    } else {
        return false; // Return false if email failed to send
    }

}

function deleteUserAccount($user_id) {
    $result = executeQuery("DELETE FROM `users` WHERE id = ?", [$user_id], "i");
    if ($result) {
        // Create an array of names to delete
        $names = [
            $user_id . "_2fa",
            $user_id . "_password",
            $user_id . "_referral_link",
            $user_id . "_salt",
            $user_id . "_activity_log",
            $user_id . "_bank_details",
        ];        

        // Prepare the placeholders for the query
        $placeholders = implode(',', array_fill(0, count($names), '?'));

        // Prepare the query using the placeholders
        $query = "DELETE FROM `options` WHERE `name` IN ($placeholders)";

        // Execute the query using a single call
        executeQuery($query, $names, str_repeat('s', count($names)));

        // Deleting All transactions of user
        executeQuery("DELETE from `transactions` WHERE user_id = ?", [$user_id], "i");

        // Deleting All withdraw request of user
        executeQuery("DELETE from `withdraw_requests` WHERE user_id = ?", [$user_id], "i");

        // Removing user activities log file.
        unlink(dirname(__FILE__) . "/user_logs/{$user_id}_logs.json");
        return true;
    }
    return false;
}

try {
    if (isset($_POST['mlm_add_new_user'])) {
        // Admin Add New User
        $data = json_decode($_POST['mlm_add_new_user'], true);
        $sql = "INSERT INTO `users` (`name`, `email`, `phone`, `role`, `sponser_id`, `balance`) VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$data['name'], $data['email'], $data['phone'], "customer", 0, 0.00];
        $result = executeQuery($sql, $params, "ssssid");
        if ($result) {

            $user_id = $conn->insert_id;

            // Updating options for new user
            $auth = $user_id . "_2fa";
            $password_name = $user_id . "_password";
            $referral_link_name = $user_id . "_referral_link";
            $salt_name = $user_id . "_salt";
            $activity_log_name = $user_id . "_activity_log";
            $bank_details_name = $user_id . "_bank_details";
            $sql = "INSERT INTO `options` (`name`, `value`) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$password_name, ""]);
            $stmt->execute([$referral_link_name, ""]);
            $stmt->execute([$salt_name, ""]);
            $stmt->execute([$activity_log_name, false]);
            $stmt->execute([$auth, false]);
            $stmt->execute([$bank_details_name, json_encode([
                "name" => "",
                "bank_name" => "",
                "account_number" => "",
                "ifsc_code" => "",
                "routing_number" => "",
                "swift_number" => "",
            ])]);

            $return["status"] = true;
            $return["message"] = "New User created successfully!";
        } else {
            $return["message"] = $result;
        }
    }

    else if (isset($_POST['mlm_login'])) {
        // Login
        $data = json_decode($_POST['mlm_login'], true);

        $sql = "SELECT `id`, `role` FROM `users` WHERE `email` = ? and role = '$data[role]' LIMIT 1;";
        $user = fetchSingleRow($sql, [$data['email']], "s");

        if ($user) {
            $user_id = $user['id'];
            $role = $user['role'];
            $hashedPassword = "";
            $salt = "";
            $password = $data['password'];

            $hashedPassword = fetchSingleRow("SELECT `value` FROM `options` WHERE `name` = ?", ["{$user_id}_password"], "s");

            if ($hashedPassword) {
                $salt = fetchSingleRow("SELECT `value` FROM `options` WHERE `name` = ?", ["{$user_id}_salt"], "s");
            }

            if ($hashedPassword && isset($salt) && password_verify($password . $salt['value'], $hashedPassword['value'])) {
                // Password is correct
                // Proceed with authentication
                $return["status"] = true;
                // After successful login

                // Adding log in activity
                addActivity($user_id, "Logged In!");

                $auth = fetchSingleRow("SELECT `value` FROM `options` WHERE name = ?", ["{$user_id}_2fa"], "s")['value'];
                if ($auth === "true") {
                    $return["message"] = "We have sent a one-time password (OTP) to your email address.";
                    $otp = rand(100000, 999999);
                    sendMail($data['email'], "Two-Factor Authentication Code", 'Your two-factor authentication code is: ' . $otp . "\n\n" . 'Please use this code to complete your login.');
                    $_SESSION['mlm_login_otp'] = $otp;
                    $_SESSION['mlm_login_role'] = $role;
                }else {
                    $return["message"] = "Login Successfull!.";
                    $currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER["HTTP_HOST"] . "/";
                    $path = $role == "admin" ? "admin/" : "user/";
                    $return["data"] = $currentURL . $path;
                }

                // Setting user id in session
                $_SESSION['mlm_user_id'] = $user_id;
            } else {
                // Password is incorrect
                // Reject authentication
                $return["status"] = false;
                $return["message"] = "Invalid Credentials!.";
            }
        } else {
            $return["status"] = false;
            $return["message"] = "Invalid Credentials!.";
        }
    }

    else if (isset($_POST['mlm_register'])) {
        // Register
        $data = json_decode($_POST['mlm_register'], true);

        // Check if email already exists
        $userExists = fetchSingleRow("SELECT * FROM `users` WHERE email = ?", [$data['email']], "s");

        if (!$userExists) {
            // Inserting User in `users` table
            $sql = "INSERT INTO `users` (`name`, `email`, `phone`, `role`, `sponser_id`, `balance`) VALUES (?, ?, ?, ?, ?, ?)";
            $params = [
                $data['name'],
                $data['email'],
                0, // Assuming default phone value is 0
                "customer", // Assuming default role is customer
                $data['sponser_id'],
                0.00 // Assuming default balance is 0.00
            ];
            executeQuery($sql, $params, "ssisid");

            // Retrieve user ID after insertion
            $user_id = $conn->insert_id;

            // Adding referral amount in sponsor's wallet
            if ($data['sponser_id'] != "0") {
                $referral_amount = fetchSingleRow("SELECT `value` FROM `options` WHERE name = 'referral_amount'", [], "");

                if ($referral_amount) {
                    // Update sponsor's balance
                    executeQuery("UPDATE users SET balance = balance + ? WHERE id = ?", [$referral_amount['value'], $data['sponser_id']], "ii");

                    // Insert into transactions
                    executeQuery("INSERT INTO transactions (amount, type, date, user_id, description) VALUES (?, 'referral', NOW(), ?, 'Referral Bonus Added!')", [$referral_amount['value'], $data['sponser_id']], "ii");
                }
            }

            // Updating options for new user
            $auth = $user_id . "_2fa";
            $password_name = $user_id . "_password";
            $referral_link_name = $user_id . "_referral_link";
            $salt_name = $user_id . "_salt";
            $activity_log_name = $user_id . "_activity_log";
            $bank_details_name = $user_id . "_bank_details";
            $sql = "INSERT INTO `options` (`name`, `value`) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$password_name, ""]);
            $stmt->execute([$referral_link_name, ""]);
            $stmt->execute([$salt_name, ""]);
            $stmt->execute([$activity_log_name, false]);
            $stmt->execute([$auth, false]);
            $stmt->execute([$bank_details_name, json_encode([
                "name" => "",
                "bank_name" => "",
                "account_number" => "",
                "ifsc_code" => "",
                "routing_number" => "",
                "swift_number" => "",
            ])]);

            // Inserting Password if user_id found
            if (isset($user_id)) {
                insertPassword($user_id, $data['password']);
                referralLink($user_id);
            }

            $return["status"] = true;
            $return["message"] = "You're successfully registered!";
        } else {
            $return["message"] = "Email already exists!";
        }
    }

    else if (isset($_POST["mlm_withdraw"])) {
        // Withdraw Request
        $data = json_decode($_POST['mlm_withdraw'], true);
    
        // Inserting in Withdraw Requests table
        $sql = "INSERT INTO `withdraw_requests` (user_id, amount, status) VALUES (?, ?, ?)";
        $status = "pending";
        $params = [
            $data["user_id"],
            $data["amount"],
            $status
        ];
        $result = executeQuery($sql, $params, "iis");
        if ($result) {
            // Adding Request Widthdrawl activity
            addActivity($data["user_id"], "Requested to widthdrawl!");
            $return["status"] = true;
            $return["message"] = "Your withdraw request has been submitted successfully. You will receive the money once it is approved by the admin.";
        }else{
            $return["message"] = $result;
        }
    }

    else if (isset($_POST["mlm_user_profile"])) {
        // User Profile Update
        $data = json_decode($_POST['mlm_user_profile'], true);
    
        // Update user profile
        $sql = "UPDATE `users` SET name = ?, email = ?, phone = ? WHERE id = ?";
        $params = [
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['id']
        ];

        // Adding Update Profile activity
        addActivity($data['id'], "Profile Updated!");

        $result = executeQuery($sql, $params, "ssii");
        if ($result) {
            $return["status"] = true;
            $return["message"] = "Profile details updated successfully!";
        } else {
            $return["message"] = $result;
        }
    }

    else if (isset($_POST["mlm_user_account"])) {
        // User Account Details Update
        $data = json_decode($_POST['mlm_user_account'], true);
        $user_id = $data['id'];
        unset($data['id']);
        $data = json_encode($data);
        $option_name = $user_id . '_bank_details';
    
        // Update user account details
        $sql = "INSERT INTO `options` (name, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = VALUES(value)";
        $params = [
            $option_name,
            $data
        ];
        $result = executeQuery($sql, $params, "ss");
        if ($result) {
            $return["status"] = true;
            $return["message"] = "Bank details updated successfully!";
        } else {
            $return["message"] = $result;
        }
    }

    else if (isset($_POST["mlm_admin_options"])) {
        // Admin Options
        $data = json_decode($_POST['mlm_admin_options'], true);

        // Prepare a query that inserts multiple rows at once
        $sql = "INSERT INTO options (name, value) VALUES ";
        $values = [];
        $params = [];
        $types = '';

        // Iterate through the $data array and build the query and parameters
        foreach ($data as $name => $value) {
            $sql .= "(?, ?),";
            $values[] = $name;
            $values[] = $value;
            $types .= 'ss';
        }

        // Remove the trailing comma and add the ON DUPLICATE KEY UPDATE clause
        $sql = rtrim($sql, ',') . " ON DUPLICATE KEY UPDATE value = VALUES(value)";

        // Execute the query using the executeQuery function
        $result = executeQuery($sql, $values, $types);

        // Check the result and set the return message
        $return = [];
        if ($result) {
            $return["status"] = true;
            $return["message"] = "Options updated successfully!";
        } else {
            $return["message"] = $result;
        }

        // // Update admin options
        // $result = executeQuery("INSERT INTO options (name, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = VALUES(value)", ["referral_amount",$data['referral_amount']], "ss");
        // if ($result) {
        //     $return["status"] = true;
        //     $return["message"] = "Options updated successfully!";
        // } else {
        //     $return["message"] = $result;
        // }

        // executeQuery("INSERT INTO options (name, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = VALUES(value)", ["minimum_amount_to_withdraw",$data['minimum_amount_to_withdraw']], "ss");
    }

    else if (isset($_POST["mlm_admin_withdraw_request"])) {
        // Admin Withdraw Request
        $data = json_decode($_POST['mlm_admin_withdraw_request'], true);
    
        // Updating request status in withdraw requests
        $sql_update_request = "UPDATE `withdraw_requests` SET `status` = ? WHERE `id` = ?";
        $params_update_request = [
            $data['status'],
            $data['id']
        ];
        $result = executeQuery($sql_update_request, $params_update_request, "si");
        if ($result) {
            $return["status"] = true;
            $return["message"] = "Request " . ucfirst($data['status']) . " successfully!";
        } else {
            $return["message"] = $result;
        }

        // If status is approved, process additional transactions
        if ($data['status'] == "approved") {
            // Calculate admin charges (5%)
            $percent = calculatePercentage(5, (int)$data['amount']);

            // Deduct amount from user's wallet
            $sql_update_user_balance = "UPDATE users SET balance = balance - ? WHERE id = ?";
            $params_update_user_balance = [
                $data['amount'],
                $data['user_id']
            ];
            executeQuery($sql_update_user_balance, $params_update_user_balance, "ii");
    
            // Add transaction for user withdrawal
            $sql_insert_user_transaction = "INSERT INTO `transactions` (amount, type, description, date, user_id) VALUES (?, 'withdraw', 'Withdraw Amount Deducted!', NOW(), ?)";
            $params_insert_user_transaction = [
                $data['amount'],
                $data['user_id']
            ];
            executeQuery($sql_insert_user_transaction, $params_insert_user_transaction, "ii");
    
            // Add admin charges to admin's wallet
            $sql_update_admin_balance = "UPDATE users SET balance = balance + ? WHERE role = 'admin'";
            $params_update_admin_balance = [$percent];
            executeQuery($sql_update_admin_balance, $params_update_admin_balance, "i");
    
            // Add transaction for admin charges
            $sql_insert_admin_transaction = "INSERT INTO `transactions` (amount, type, description, date, user_id) VALUES (?, 'referral', 'Withdraw Charges Received!', NOW(), (SELECT id FROM users WHERE role = 'admin'))";
            executeQuery($sql_insert_admin_transaction, [$percent], "i");
        }
    }

    else if (isset($_POST["mlm_user_profile_activity_log"])) {
        // User Profile Activity Log
        $data = json_decode($_POST['mlm_user_profile_activity_log'], true);
        $sql = "UPDATE `options` SET `value` = ? WHERE `name` = ?";
        $result = executeQuery($sql, [$data['val'], "$data[user_id]_activity_log"], "ss");
        if ($result) {
            $return["status"] = true;
            $return["message"] = $data['val'] ? "Activity Logs Enabled!, You can see your activity logs now." : "Activity Logs Disabled!, You won't be able to see your activity logs now.";
        } else {
            $return["message"] = $result;
        }
    }

    else if (isset($_POST["mlm_user_profile_change_password"])) {
        // User Profile Password Change
        $data = json_decode($_POST['mlm_user_profile_change_password'], true);
        $result = resetPassword($data['user_id'], $data['old'], $data['new']);
        if ($result) {
            $return["status"] = true;
            $return["message"] = "Password Changed Successfully!";
            $return["data"] = "https://$_SERVER[HTTP_HOST]/user/logout.php";

            // Adding Change Password activity
            addActivity($data['user_id'], "Password Changed!");
        }else {
            $return["message"] = "Incorrect Password!";
        }
    }

    else if (isset($_POST["mlm_user_profile_2fa"])) {
        // User Profile 2FA Authentication
        $data = json_decode($_POST['mlm_user_profile_2fa'], true);
        $sql = "UPDATE `options` SET `value` = ? WHERE `name` = ?";
        $result = executeQuery($sql, [$data['val'], "$data[user_id]_2fa"], "ss");
        if ($result) {
            $return["status"] = true;
            $return["message"] = "Two Factor Authentication (2FA) " . ($data['val'] ? "Enabled!" : "Disabled!");
            $return["data"] = "https://$_SERVER[HTTP_HOST]/user/logout.php";
        } else {
            $return["message"] = $result;
        }
    }

    else if (isset($_POST["mlm_forgot_password_verify_email"])) {
        // Verify Email
        $data = json_decode($_POST['mlm_forgot_password_verify_email'], true);
        $result = fetchSingleRow("SELECT `id` from `users` where `email` = ?;", [$data['email']], "s");
        if ($result) {
            $otp = rand(100000, 999999);
            if (sendMail($data['email'], "Password Reset Request", 'You requested a password reset. Your OTP is: ' . $otp . "\n\n" . 'Please use this OTP to reset your password.')) {
                $_SESSION['mlm_verify_email'] = $data['email'];
                $_SESSION['mlm_verify_email_id'] = $result['id'];
                $_SESSION['mlm_verify_email_otp'] = $otp;
                $return['status'] = true;
                $return['message'] = "Email verified! You will receive a One Time Password (OTP) on your email that can be used to reset your password.";
            }else {
                $return['message'] = "Email not sent!";
            }
        }else {
            $return['message'] = "Invalid Email!";
        }
    }

    else if (isset($_POST["mlm_forgot_password_verify_otp"])) {
        // Verify OTP
        $data = json_decode($_POST['mlm_forgot_password_verify_otp'], true);
        if ($data['otp'] == $_SESSION['mlm_verify_email_otp']) {
            $return['status'] = true;
            $return['message'] = "Your OTP has been verified successfully! You can now proceed to create a new password for your account.";
        }else {
            $return['message'] = "Incorrect OTP!";
        }
    }

    else if (isset($_POST["mlm_forgot_password_verify_password"])) {
        // Reset Password
        $data = json_decode($_POST['mlm_forgot_password_verify_password'], true);
        updatePassword($_SESSION['mlm_verify_email_id'], $data['password']);
        sendMail($_SESSION['mlm_verify_email'], 'Password Reset Confirmation', 'Your password has been reset successfully!' . "\n\n" . 'If you did not request this change, please contact support immediately.');
        unset($_SESSION['mlm_verify_email']);
        unset($_SESSION['mlm_verify_email_id']);
        unset($_SESSION['mlm_verify_email_otp']);
        $return['status'] = true;
        $return['message'] = "Your password has been reset successfully!";
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $return['data'] = $protocol . $_SERVER['SERVER_NAME'];
    }

    else if (isset($_POST["mlm_login_otp"])) {
        // 2FA Login Verify OTP
        $data = json_decode($_POST['mlm_login_otp'], true);
        if ($data['otp'] == $_SESSION['mlm_login_otp']) {
            $return['status'] = true;
            $return['message'] = "Login Successfull!";
            $currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER["HTTP_HOST"] . "/";
            $path = $role == "admin" ? "admin/" : "user/";
            $return["data"] = $currentURL . $path;
        }else{
            $return['message'] = "Invalid OTP!";
        }
    }

    else if (isset($_POST["mlm_user_delete_account"])) {
        // Delete User Account
        $data = json_decode($_POST['mlm_user_delete_account'], true);
        if (deleteUserAccount($data['id'])) {
            $return['status'] = true;
            $return['message'] = "Your account has been successfully deleted!";
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
            $currentUrl = $protocol . $_SERVER['SERVER_NAME'] . '/user/logout.php';
            $return['data'] = $currentUrl;
        }else {
            $return['message'] = "We were unable to delete your account.";
        }
    }
} catch(Exception $e) {
    $return["message"] = 'Message: ' . $e->getMessage();
}

// Send JSON response
echo json_encode($return);

// Close the database connection
$conn->close();
?>
