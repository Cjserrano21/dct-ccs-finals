<?php
session_start();

function postData($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

function con() {
    $conn = mysqli_connect("localhost", "root", "", "dct-ccs-finals");

    if (!$conn) {
        exit("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function validateLoginCredentials($email, $password) {
    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    return $errors;
}

function checkLoginCredentials($email, $password) {
    $conn = con();

    // Hash the input password with md5 (make sure it's exactly how it's stored in the database)
    $hashedPassword = md5($password);

    // Query to get user data based on the email and hashed password
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        mysqli_close($conn);
        return false;
    }

    // Bind parameters and execute
    mysqli_stmt_bind_param($stmt, "ss", $email, $hashedPassword);
    mysqli_stmt_execute($stmt);

    // Fetch the user data if found
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $user ?: false;
}


function displayErrors($errors) {
    if (empty($errors)) {
        return '';
    }

    $output = '<ul>';
    foreach ($errors as $error) {
        $output .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $output .= '</ul>';

    return $output;
}

function login($email, $password) {
    // Validate the login credentials
    $validateLogin = validateLoginCredentials($email, $password);

    if (count($validateLogin) > 0) {
        return displayErrors($validateLogin); // Return the validation error message
    }

    // Check the login credentials in the database
    $user = checkLoginCredentials($email, $password);

    if ($user) {
        // Login successful, store session and redirect
        $_SESSION['email'] = $user['email'];
        header("Location: admin/dashboard.php");
        exit(); // Don't forget to exit after the redirect to avoid further execution
    } else {
        return "Invalid email or password"; // Return error message if no match is found
    }
}

?>
