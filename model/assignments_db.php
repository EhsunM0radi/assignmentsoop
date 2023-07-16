<?php
class Assignment
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAssignmentsByCourse($course_id)
    {
        if ($course_id) {
            $query = 'SELECT A.ID, A.Description, C.Name FROM assignments A LEFT JOIN courses C ON A.courseID = C.ID WHERE A.courseID = :course_id ORDER BY A.ID';
            $statement = $this->db->prepare($query);
            $statement->bindValue(':course_id', $course_id);
        } else {
            $query = 'SELECT A.ID, A.Description, C.Name FROM assignments A LEFT JOIN courses C ON A.courseID = C.ID ORDER BY C.ID';
            $statement = $this->db->prepare($query);
        }
        $statement->execute();
        $assignments = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $assignments;
    }

    public function deleteAssignment($assignment_id)
    {
        $query = 'DELETE FROM assignments WHERE ID = :assignment_id';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':assignment_id', $assignment_id);
        $statement->execute();
    }

    public function addAssignment($course_id, $description)
    {
        global $db;
        $query = 'INSERT INTO assignments (`Description`, courseID) VALUES (:descr, :courseID)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':descr', $description);
        $statement->bindValue(':courseID', $course_id);
        $statement->execute();
        $statement->closeCursor();
    }
}
