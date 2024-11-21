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

<h3 class="text-left mb-5 mt-5">Register a New Student</h3>

<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Register Student</li>
        </ol>
    </nav>


    <?php 

        if(isPost()){
            $student_id = postData("student_id");
            $first_name = postData("first_name");
            $last_name = postData("last_name");
            addStudent($student_id, $first_name, $last_name);

        }

    ?>

    
    <!-- Register Student Form -->
    <div class="card p-4 mb-5">
        <form method="POST">
            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id">
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name">
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name">
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100">Add Student</button>
        </form>
    </div>

    <!-- Student List Table -->
    <div class="card p-4">
        <h3 class="card-title text-center">Student List</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
            <?php $students = fetchStudents();  if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['student_id']) ?></td>
                            <td><?= htmlspecialchars($student['first_name']) ?></td>
                            <td><?= htmlspecialchars($student['last_name']) ?></td>
                            <td>
                                <!-- Edit Button (Green) -->
                                <a href="edit.php?student_id=<?= urlencode($student['student_id']) ?>" class="btn btn-primary btn-sm">Edit</a>

                                <!-- Delete Button (Red) -->
                                <a href="delete.php?student_id=<?= urlencode($student['student_id']) ?>" class="btn btn-danger btn-sm">Delete</a>
                                
                                <!-- Delete Button (Red) -->
                                <a href="attach-subject.php?student_id=<?= urlencode($student['student_id']) ?>" class="btn btn-warning btn-sm">Attach Subject</a>
                            
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No students found.</td>
                    </tr>
                <?php endif; ?>
                
            </tbody>
        </table>
    </div>

</div>

<?php 
    if (isPost()) {
        $student_id = postData("student_id");
        $first_name = postData("first_name");
        $last_name = postData("last_name");

        if (empty($student_id) || empty($first_name) || empty($last_name)) {
            echo "<div class='alert alert-danger'>Please fill in all fields.</div>";
        } else {
            addStudent($student_id, $first_name, $last_name);
            echo "<div class='alert alert-success'>Student added successfully!</div>";
        }
    }
?>


<?php
include '../partials/footer.php';
?>