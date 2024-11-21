<?php
include '../../functions.php'; // Include the functions
include '../partials/header.php';

$logoutPage = '../logout.php';
$dashboardPage = '../dashboard.php';
$studentPage = '../student/register.php';
$subjectPage = './subject/add.php';
include '../partials/side-bar.php';


$student_data = getStudentById($_GET['student_id']);

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


    <?php
    if (isPost()) {
        // Check if any subjects were selected
        if (isset($_POST['subjects']) && !empty($_POST['subjects'])) {
            $subjects = $_POST['subjects'];  // The array of selected subjects
            //var_dump($subjects);
            assignSubjectsToStudent($student_data['student_id'], $subjects);
        } else {
            echo displayErrors(["No subjects selected."]);
        }
    }

    ?>

   
    <div class="border p-5">
    <!-- Confirmation Message -->
    <h4 class="text-left mb-2 mt-5">Selected Student Information</h4>
    <ul class="text-left">
        <li><strong>Student ID:</strong> <?= htmlspecialchars($student_data['student_id']) ?></li>
        <li><strong>Name:</strong> <?= htmlspecialchars($student_data['first_name']) .' '. htmlspecialchars($student_data['last_name'])  ?></li>
        
    </ul>
    <hr>

  
    

    <!-- Confirmation Form -->
    <form method="POST" class="text-left">
    <?php

echo getAllSubjectsCheckboxes($student_data['student_id']);
?>
        <button type="submit" class="btn btn-primary mt-3">Attach Subjects</button>
    </form>

    </div>


    <div class="card p-4 mt-5 mb-5">
        <h3 class="card-title text-left">Subject List</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Grade</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
            <?php $assignedSubjects = fetchAssignSubjects($student_data['student_id']);  if (!empty($assignedSubjects)): ?>
                    <?php foreach ($assignedSubjects as $subject): ?>
                        <tr>
                            <td><?= htmlspecialchars($subject['subject_id']) ?></td>
                            <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                            <td><?= htmlspecialchars($subject['grade']) ?></td>
                            <td>
                                <!-- Edit Button (Green) -->
                                <a href="detach-subject.php?subject_id=<?= urlencode($subject['subject_id']) ?>&subject_name=<?= urlencode($subject['subject_name']) ?>&student_id=<?= $student_data['student_id']?>&firstname=<?= $student_data['first_name']?>&lastname=<?= $student_data['last_name']?>" class="btn btn-danger btn-sm">Detach Subject</a>

                                <!-- Delete Button (Red) -->
                                <a href="assign-grade.php?subject_id=<?= urlencode($subject['subject_id']) ?>&subject_name=<?= urlencode($subject['subject_name']) ?>&student_id=<?= $student_data['student_id']?>&firstname=<?= $student_data['first_name']?>&lastname=<?= $student_data['last_name']?>" class="btn btn-success btn-sm">Assign Grade</a>
                                
                                
                            
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No subjects found.</td>
                    </tr>
                <?php endif; ?>
           
                
            </tbody>
        </table>
    </div>

</div>

<?php
include '../partials/footer.php';
?>