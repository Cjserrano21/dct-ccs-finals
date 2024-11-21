<?php
include '../../functions.php'; // Include the functions
include '../partials/header.php';

$logoutPage = '../logout.php';
$dashboardPage = '../dashboard.php';
$studentPage = '../student/register.php';
$subjectPage = './subject/add.php';
include '../partials/side-bar.php';


?>

<div class="col-md-9 col-lg-10">

<h3 class="text-left mb-5 mt-5">Edit Student</h1>

<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
        </ol>
    </nav>

    <!-- Heading for Edit Subject -->
     <?php
        $student_data = getStudentById($_GET['student_id']);

        if(isPost()){
            $student_id = $student_data['student_id'];
            $firstname = postData("first_name");
            $lastname = postData("last_name");

            updateStudent($student_id, $firstname, $lastname, 'register.php');
        }

     ?>
    

    <!-- Edit Subject Form -->
    <div class="card p-4 mb-5">
        <form method="POST">
            <!-- Student Code (disabled) -->
            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" value="<?= htmlspecialchars($student_data['student_id']) ?>" disabled>
            </div>

            <!-- First Name -->
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($student_data['first_name']) ?>">
            </div>

            <!-- Last Name -->
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($student_data['last_name']) ?>">
            </div>


            <!-- Update Subject Button -->
            <button type="submit" class="btn btn-primary btn-sm w-100">Update Student</button>
        </form>
    </div>

</div>

<?php
include '../partials/footer.php';
?>