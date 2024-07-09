<?php

if (isset($_SESSION['mlm_user_id'])) {
	// Redirect to dashboard
	header("Location: ./dashboard");
}else {
	// Redirect to index.php
	header("Location: ../");
}
exit;
