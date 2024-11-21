<?php
include '../../functions.php'; // Include the functions
include '../partials/header.php';

$logoutPage = '../logout.php';
$dashboardPage = '../dashboard.php';
$studentPage = '.register.php';
$subjectPage = './subject/add.php';
include '../partials/side-bar.php';


// $student_data = getStudentById($_GET['student_id']);

if(isPost()){
   detachSubject(GETdata('student_id'), GETdata('subject_id'), 'attach-subject.php?student_id='.GETdata('student_id'));
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
    <p class="text-left">Are you sure you want to dettach this subject from this student record?</p>
    <ul class="text-left">
        <li><strong>Student ID:</strong> <?= GETdata("student_id")//htmlspecialchars($student_data['student_id']) ?></li>
        <li><strong>First Name:</strong> <?= GETdata("firstname")//htmlspecialchars($student_data['first_name']) ?></li>
        <li><strong>Last Name:</strong> <?= GETdata("lastname")//htmlspecialchars($student_data['last_name']) ?></li>
        <li><strong>Subject Code:</strong> <?= GETdata("subject_id")//htmlspecialchars($student_data['first_name']) ?></li>
        <li><strong>Subject Name:</strong> <?= GETdata("subject_name")//htmlspecialchars($student_data['last_name']) ?></li>
    </ul>
    

    <!-- Confirmation Form -->
    <form method="POST" class="text-left">
        <a href="attach-subject.php?student_id=<?=GETdata('student_id')?>" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Delete Subject from Student</button>
    </form>

    </div>

</div>

<?php
include '../partials/footer.php';
?>