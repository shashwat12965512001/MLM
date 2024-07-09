<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

// Construct the current URL
$currentUrl = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (str_contains($currentUrl, "admin") || str_contains($currentUrl, "user")) {
    require "../config/conn.php";
}else if (str_contains($currentUrl, "config")) {
    require "./conn.php";
}else {
    require "./config/conn.php";
}

function formatQueryWithParams($sql, $params) {
    // Replace placeholders in the SQL query with parameters
    foreach ($params as $key => $value) {
        if ($key > 0) { // Skip the first element since it contains the parameter types
            $sql = preg_replace('/\?/', '"' . $value . '"', $sql, 1);
        }
    }
    return $sql;
}

function filterArrayById($array, $id) {
    // Initialize an array to hold the filtered results
    $filteredArray = array();
    
    // Iterate over each element in the input array
    foreach ($array as $element) {
        // Check if the element's ID matches the specified ID
        if ($element['id'] == $id) {
            // If it matches, add the element to the filtered array
            $filteredArray[] = $element;
        }
    }
    
    // Return the filtered array
    return $filteredArray;
}

function getClosestPasswordChangeDate($array) {
    // Initialize variables to hold the closest date and current date
    $closestDate = null;
    $today = new DateTime(); // Get the current date as a DateTime object
    $minDifference = null; // To store the minimum difference in days

    // Iterate through the array
    foreach ($array as $element) {
        // Check if the activity is "Password Changed!"
        if ($element['activity'] === 'Password Changed!') {
            // Convert the date string to a DateTime object
            $date = DateTime::createFromFormat('d/m/Y', date("d/m/Y", $element['time']));

            // Calculate the difference in days between today and the current date
            $difference = abs($date->format('U') - $today->format('U'));

            // Check if this is the first iteration or if the difference is smaller than the minimum difference found so far
            if ($minDifference === null || $difference < $minDifference) {
                $minDifference = $difference;
                $closestDate = date("d/m/Y", $element['time']); // Update the closest date
            }
        }
    }
    
    // Return the closest date found
    return $closestDate;
}

// Function to execute SQL query with parameters
function executeQuery($sql, $params = array(), $types = "") {
    global $conn;
    // Prepare and bind the SQL statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return "Error preparing SQL statement: " . $conn->error;
    }

    // Bind parameters if provided
    if (!empty($params)) {
        $bindParams = array_merge(array($types), $params);
        $bindArgs = array();
        foreach ($bindParams as $key => $value) {
            $bindArgs[$key] = &$bindParams[$key];
        }
        call_user_func_array(array($stmt, 'bind_param'), $bindArgs);
    }

    // Execute the statement
    $result = $stmt->execute();
    if ($result === false) {
        return "Error executing SQL statement: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    
    // Return true if execution was successful
    return true;
}

// Function to fetch single row from database
function fetchSingleRow($sql, $params = [], $types = "")
{
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false; // Return false if preparation of statement fails
    }

    if ($params && $types) {
        $stmt->bind_param($types, ...$params);
    }

    $success = $stmt->execute();
    if (!$success) {
        return false; // Return false if execution of statement fails
    }

    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $result;
}

// Function to fetch multiple rows from database
function fetchMultipleRows($sql, $params = [], $types = "")
{
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false; // Return false if preparation of statement fails
    }

    if ($params && $types) {
        $stmt->bind_param($types, ...$params);
    }

    $success = $stmt->execute();
    if (!$success) {
        return false; // Return false if execution of statement fails
    }

    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $result;
}

function appendOrCreateFile($filename, $data) {
    // Convert the data to a JSON string
    $jsonData = json_encode($data);
    
    // Check if the file exists
    if (file_exists($filename)) {
        // Read the existing file contents
        $fileContents = file_get_contents($filename);
        
        // Check if the file is not empty
        if (!empty($fileContents)) {
            // Remove the last closing square bracket from the existing JSON array
            $fileContents = rtrim($fileContents, ']');
            // Append a comma if the file is not empty
            if ($fileContents !== '[') {
                $fileContents .= ',';
            }
        }
        
        // Append the new JSON data and close the JSON array
        $fileContents .= $jsonData . ']';
    } else {
        // Create a new JSON array with the new data
        $fileContents = '[' . $jsonData . ']';
    }
    
    // Write the final JSON data back to the file
    file_put_contents($filename, $fileContents);
}

function addActivity($user_id, $activity) {
    $activity_logs = fetchSingleRow("SELECT `value` FROM `options` WHERE name = ?", ["{$user_id}_activity_log"], "s")['value'];
    if ($activity_logs) {
        if (str_contains($activity, "Logged Out!")) {
            $path = dirname(__DIR__) . "/config/user_logs/{$user_id}_logs.json";
        }else {
            $path = dirname(__FILE__) . "/user_logs/{$user_id}_logs.json";
        }
        appendOrCreateFile($path, [
            "id" => $user_id,
            "activity" => $activity,
            "time" => strtotime("now"),
        ]);
    }
}

function calculateDurationFromNow($givenDate, $givenTime) {
    // Convert the given date and time into a single string
    $givenDateTimeString = $givenDate . ' ' . $givenTime;

    // Create a DateTime object for the given date and time
    $givenDateTime = DateTime::createFromFormat('d/m/Y H:i:s', $givenDateTimeString);

    // Check if the given date and time are in the correct format
    if ($givenDateTime === false) {
        return [
            'error' => 'Invalid date or time format. Expected format: d/m/Y H:i:s'
        ];
    }

    // Create a DateTime object for the current date and time
    $currentDateTime = new DateTime();

    // Calculate the difference between the two dates and times
    $interval = $currentDateTime->diff($givenDateTime);

    // Extract the duration in desired units
    $duration = [
        'days' => $interval->days,
        'hours' => $interval->h,
        'minutes' => $interval->i,
        'seconds' => $interval->s
    ];

    // Return the duration as an associative array
    return $duration;
}

function getLatestEntries($array) {
    // Sort the array based on date and time in descending order
    usort($array, function($a, $b) {
        // Convert date and time to a single DateTime object
        
        $dateTimeA = DateTime::createFromFormat('d/m/Y H:i:s', date("d/m/Y", $a['time']) . ' ' . date("H:i:s", $a['time']));
        $dateTimeB = DateTime::createFromFormat('d/m/Y H:i:s', date("d/m/Y", $b['time']) . ' ' . date("H:i:s", $b['time']));
        
        // Compare the two DateTime objects
        if ($dateTimeA == $dateTimeB) {
            return 0;
        }
        return ($dateTimeA > $dateTimeB) ? -1 : 1;
    });

    // Return the first 10 entries from the sorted array
    return array_slice($array, 0, 5);
}

function debug($data = []) {
    echo "<pre>";
    print_r($data);
    die;
}
