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
        return false;
    }
    public function addTimetableAction($data)
    {
        return false;
    }
    public function updateTimetableAction($id, $data)
    {
        return false;
    }
    public function deleteTimetableAction($id)
    {
        return false;
    }
}
