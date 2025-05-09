<?php

namespace App\Models;

use PDO;

class AppointmentModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // get all appointments by walker 
    public function getAppointmentsByWalker($walkerId)
    {
        $sql = "SELECT 
        d.name as dog_name,
        u.name as owner_name,
        DATE_FORMAT(a.start_time, '%Y-%m-%d %H:%i') as formatted_start_time,
        DATE_FORMAT(a.end_time, '%Y-%m-%d %H:%i') as formatted_end_time
        FROM approvedAppointments as a
        JOIN request r ON a.request_id = r.id
        JOIN dogs d ON a.dog_id = d.id
        JOIN user u ON r.owner_id = u.id
        WHERE r.walker_id = ?
        ORDER BY a.start_time ASC";
        
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$walkerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // get all appointments by owner
    public function getAppointmentsByOwner($ownerId)
    {
        $sql = "SELECT 
        d.name as dog_name,
        u.name as walker_name,
        DATE_FORMAT(a.start_time, '%Y-%m-%d %H:%i') as formatted_start_time,
        DATE_FORMAT(a.end_time, '%Y-%m-%d %H:%i') as formatted_end_time
        FROM approvedAppointments as a
        JOIN request r ON a.request_id = r.id
        JOIN dogs d ON a.dog_id = d.id
        JOIN user u ON r.walker_id = u.id
        WHERE r.owner_id = ?
        ORDER BY a.start_time ASC";
        
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // cancel appointment - for both owner and walker
    public function cancelAppointment($appointmentId)
    {
        $sql = "DELETE FROM approvedAppointments WHERE id = ?";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$appointmentId]);
    }
    

}
