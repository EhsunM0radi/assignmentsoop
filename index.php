<?php
require_once('model/database.php');
require_once('model/course_db.php');
require_once('model/assignments_db.php');

$dsn = "mysql:host=localhost;dbname=assignment_tracker";
$username = "ehsan";
$password = "123";

// Create the database object
$db = new Database($dsn, $username, $password);

// Create the course and assignment objects using the existing classes
$course = new Course($db);
$assignment = new Assignment($db);

$course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
if (!$course_id) {
    $course_id = filter_input(INPUT_GET, 'course_id', FILTER_VALIDATE_INT);
}

$action = htmlspecialchars(filter_input(INPUT_POST, 'action'));
if (!$action) {
    $action = htmlspecialchars(filter_input(INPUT_GET, 'action'));
    if (!$action) {
        $action = 'list_assignments';
    }
}


switch ($action) {
    case "list_courses":
        $courses = $course->getCourses();
        include('view/course_list.php');
        break;
    case "add_course":
        $course_name = htmlspecialchars(filter_input(INPUT_POST, 'course_name'));
        if (!empty($course_name)) {
            $course->addCourse($course_name);
        }
        header("Location: .?action=list_courses");
        break;
    case "add_assignment":
        $description = htmlspecialchars(filter_input(INPUT_POST, 'description'));
        if ($course_id && !empty($description)) {
            $assignment->addAssignment($course_id, $description);
        }
        header("Location: .?course_id=$course_id");
        break;
    case "delete_course":
        if ($course_id) {
            try {
                $course->deleteCourse($course_id);
            } catch (PDOException $e) {
                $error = "You cannot delete a course if assignments exist in the course.";
                include('view/error.php');
                exit();
            }
        }
        header("Location: .?action=list_courses");
        break;
    case "delete_assignment":
        $assignment_id = filter_input(INPUT_POST, 'assignment_id', FILTER_VALIDATE_INT);
        if ($assignment_id) {
            $assignment->deleteAssignment($assignment_id);
        }
        header("Location: .?course_id=$course_id");
        break;
    default:
        $course_name = $course->getCourseName($course_id);
        $courses = $course->getCourses();
        $assignments = $assignment->getAssignmentsByCourse($course_id);
        include('view/assignment_list.php');
}
