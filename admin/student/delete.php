<?php
include '../../functions.php'; // Include the functions
include '../partials/header.php';

$logoutPage = '../logout.php';
$dashboardPage = '../dashboard.php';
$studentPage = '../student/register.php';
$subjectPage = './subject/add.php';
include '../partials/side-bar.php';


$student_data = getStudentById($_GET['student_id']);

if(isPost()){
    deleteStudent($student_data['student_id'], 'register.php');
}


?>

<div class="col-md-9 col-lg-10">

<h3 class="text-left mb-5 mt-5">Delete a Student</h3>

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
        </ol>
    </nav>

   
    <div class="border p-5">
    <!-- Confirmation Message -->
    <p class="text-left">Are you sure you want to delete the following student record?</p>
    <ul class="text-left">
        <li><strong>Student ID:</strong> <?= htmlspecialchars($student_data['student_id']) ?></li>
        <li><strong>First Name:</strong> <?= htmlspecialchars($student_data['first_name']) ?></li>
        <li><strong>Last Name:</strong> <?= htmlspecialchars($student_data['last_name']) ?></li>
    </ul>
    

    <!-- Confirmation Form -->
    <form method="POST" class="text-left">
        <a href="register.php" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Delete Student Record</button>
    </form>

    </div>

</div>

<?php
include '../partials/footer.php';
?>