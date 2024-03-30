<?php
class Timetable
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function getAllTimetableActions(){
        $query = "SELECT * FROM timetable";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        
        $timetableActions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $timetableActions;
    }
    public function getTimetableActionById($id)
    {

        $query = "SELECT * FROM timetable WHERE id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $timetableAction = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $timetableAction;
    }
    public function addTimetableAction($data)
    {
        $day = $data['day'];
        $time_from = $data['time_from'];
        $time_to = $data['time_to'];
        $subject = $data['subject'];
        $action = $data['action'];
        $room = $data['room'];
        $teacher = $data['teacher'];

        $query = "INSERT INTO timetable (day, time_from, time_to, subject, action, room, teacher) 
        VALUES (:day, :time_from, :time_to, :subject, :action, :room, :teacher)";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':day', $day, PDO::PARAM_STR);
        $stmt->bindParam(':time_from', $time_from, PDO::PARAM_STR);
        $stmt->bindParam(':time_to', $time_to, PDO::PARAM_STR);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $stmt->bindParam(':room', $room, PDO::PARAM_STR);
        $stmt->bindParam(':teacher', $teacher, PDO::PARAM_STR);

        $result = $stmt->execute();
        return $result;
    }
    public function updateTimetableAction($id, $data)
    {
        $day = $data['day'];
        $time_from = $data['time_from'];
        $time_to = $data['time_to'];
        $subject = $data['subject'];
        $action = $data['action'];
        $room = $data['room'];
        $teacher = $data['teacher'];

        $query = "UPDATE timetable SET day=:day, time_from=:time_from, time_to=:time_to, 
        subject=:subject, action=:action, room=:room, teacher=:teacher WHERE id=:id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':day', $day, PDO::PARAM_STR);
        $stmt->bindParam(':time_from', $time_from, PDO::PARAM_STR);
        $stmt->bindParam(':time_to', $time_to, PDO::PARAM_STR);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $stmt->bindParam(':room', $room, PDO::PARAM_STR);
        $stmt->bindParam(':teacher', $teacher, PDO::PARAM_STR);
        
        $result = $stmt->execute();
        return $result;

    }
    public function deleteTimetableAction($id)
    {
        $query = "DELETE FROM timetable WHERE id=:id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        $result = $stmt->execute();
        return $result;
    }
}
