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

function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
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

function addStudent($student_id, $first_name, $last_name) {
    $conn = con(); // Use the MySQLi connection function

    // Sanitize and validate the input data
    $student_id = sanitize_input($student_id);
    $first_name = sanitize_input($first_name);
    $last_name = sanitize_input($last_name);

    // SQL query to insert new student into the database
    $query = "INSERT INTO students (student_id, first_name, last_name) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $student_id, $first_name, $last_name);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<div class='alert alert-success'>Student added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to add student. Please try again.</div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn); // Close the database connection
}

function fetchStudents() {
    $conn = con(); // Use the MySQLi connection function
    $query = "SELECT * FROM students";
    $result = mysqli_query($conn, $query);

    $students = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
    } else {
        return "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn); // Close the database connection
    return $students;
}


function countAllSubjects() {
    try {
        // Get the database connection
        $conn = getConnection();

        // SQL query to count all subjects
        $sql = "SELECT COUNT(*) AS total_subjects FROM subjects";
        $stmt = $conn->prepare($sql);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the count
        return $result['total_subjects'];
    } catch (PDOException $e) {
        // Handle any errors
        return "Error: " . $e->getMessage();
    }
}


function countAllStudents() {
    $conn = con(); // Use the MySQLi connection function
    $sql = "SELECT COUNT(*) AS total_students FROM students";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        mysqli_close($conn); // Close connection after query execution
        return $data['total_students'];
    } else {
        return "Error: " . mysqli_error($conn);
    }
}



function calculateTotalPassedAndFailedStudents() {
    $conn = con();
    $sql = "SELECT student_id, 
                   SUM(grade) AS total_grades, 
                   COUNT(subject_id) AS total_subjects 
            FROM students_subjects 
            GROUP BY student_id";
    $result = mysqli_query($conn, $sql);

    $passed = $failed = 0;

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $average_grade = $row['total_grades'] / $row['total_subjects'];
            if ($average_grade >= 75) {
                $passed++;
            } else {
                $failed++;
            }
        }
        mysqli_close($conn); // Close connection
        return ['passed' => $passed, 'failed' => $failed];
    } else {
        return "Error: " . mysqli_error($conn);
    }
}



function addSubject($subject_code, $subject_name) {
    $validateSubjectData = validateSubjectData($subject_code, $subject_name);

    $checkDuplicate = checkDuplicateSubjectData($subject_code, $subject_name);

    if(count($validateSubjectData) > 0 ){
        echo displayErrors($validateSubjectData);
        return;
    }

    if(count($checkDuplicate) == 1 ){
        echo displayErrors($checkDuplicate);
        return;
    }


    // Get database connection
    $conn = getConnection();

    try {
        // Prepare SQL query to insert subject into the database
        $sql = "INSERT INTO subjects (subject_code, subject_name) VALUES (:subject_code, :subject_name)";
        $stmt = $conn->prepare($sql);

        // Bind parameters to the SQL query
        $stmt->bindParam(':subject_code', $subject_code);
        $stmt->bindParam(':subject_name', $subject_name);

        // Execute the query
        if ($stmt->execute()) {
            return true; // Subject successfully added
        } else {
            return "Failed to add subject."; // Query execution failed
        }
    } catch (PDOException $e) {
        // Return error message if the query fails
        return "Error: " . $e->getMessage();
    }
}





function validateSubjectData($subject_code, $subject_name ) {
    $errors = [];

    // Check if subject_code is empty
    if (empty($subject_code)) {
        $errors[] = "Subject code is required.";
    }

    // Check if subject_name is empty
    if (empty($subject_name)) {
        $errors[] = "Subject name is required.";
    }

    return $errors;
}

// Function to check if the subject already exists in the database (duplicate check)
function checkDuplicateSubjectData($subject_code, $subject_name) {
    // Get database connection
    $conn = getConnection();

    // Query to check if the subject_code already exists in the database
    $sql = "SELECT * FROM subjects WHERE subject_code = :subject_code OR subject_name = :subject_name";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':subject_code', $subject_code);
    $stmt->bindParam(':subject_name', $subject_name);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $existing_subject = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a subject exists with the same code or name, return an error
    if ($existing_subject) {
        return ["Duplicate subject found: The subject code or name already exists."];
    }

    return [];
}



// Function to check if the subject already exists in the database (duplicate check)
function checkDuplicateSubjectForEdit($subject_name) {
    // Get database connection
    $conn = getConnection();

    // Query to check if the subject_code already exists in the database
    $sql = "SELECT * FROM subjects WHERE subject_name = :subject_name";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':subject_name', $subject_name);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $existing_subject = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a subject exists with the same code or name, return an error
    if ($existing_subject) {
        return ["Duplicate subject found: The subject code or name already exists."];
    }

    return [];
}



?>
