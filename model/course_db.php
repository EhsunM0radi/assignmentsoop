<?php

// models/course_db.php

class Course
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getCourses()
    {
        $query = 'SELECT * FROM courses ORDER BY ID';
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getCourseName($course_id)
    {
        if (!$course_id) {
            return "All Courses";
        }
        global $db;
        $query = 'SELECT `Name` FROM courses Where ID = :course_id';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
        $course_name = $statement->fetch();
        $statement->closeCursor();
        return $course_name;
    }

    public function deleteCourse($course_id)
    {
        global $db;
        $query = 'DELETE FROM courses WHERE ID = :course_id';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
        $statement->closeCursor();
    }

    public function addCourse($course_name)
    {
        global $db;
        $query = 'INSERT INTO courses (`Name`) VALUES (:Name)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':Name', $course_name);
        $statement->execute();
        $statement->closeCursor();
    }
}
