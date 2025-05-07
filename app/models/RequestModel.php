<?php

namespace App\Models;

use Exception;
use PDO;

class RequestModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // Owner: send request to a walker
    public function createRequest($dogId,$walkerId,$ownerId,$startTime,$endTime){
        $stmt=self::$pdo->prepare("INSERT INTO request (dog_id, walker_id, owner_id, start_time, end_time, status, created_at) 
        VALUES (?, ?, ?, ?, ?, 'pending', CURRENT_TIMESTAMP);");
        return $stmt->execute([$dogId, $walkerId, $ownerId, $startTime, $endTime]);
    }
    
    // owner : get sent requests 
    public function getRequestsByOwner($ownerId) {
        $sql = "SELECT 
                request.*, 
                dogs.name as dog_name, 
                user.name as walker_name,
                user.price as walker_price,
                DATE_FORMAT(request.start_time, '%Y-%m-%d %H:%i') as formatted_start_time,
                DATE_FORMAT(request.end_time, '%Y-%m-%d %H:%i') as formatted_end_time,
                DATE_FORMAT(request.created_at, '%Y-%m-%d %H:%i') as formatted_created_at
                FROM request 
                JOIN dogs ON request.dog_id = dogs.id
                JOIN user ON request.walker_id = user.id 
                WHERE request.owner_id = ?   --/ ? is a placeholder for the owner_id 
                ORDER BY request.created_at DESC";
                
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // walker : get received requests by walker
    public function getRequestsByWalker($walkerId) {
        $sql = "SELECT 
                request.*, 
                dogs.name as dog_name, 
                dogs.age as dog_age,
                dogs.breed as dog_breed,
                dogs.size as dog_size,
                user.name as owner_name,
                DATE_FORMAT(request.start_time, '%Y-%m-%d %H:%i') as formatted_start_time,
                DATE_FORMAT(request.end_time, '%Y-%m-%d %H:%i') as formatted_end_time,
                DATE_FORMAT(request.created_at, '%Y-%m-%d %H:%i') as formatted_created_at
                FROM request 
                JOIN dogs ON request.dog_id = dogs.id
                JOIN user ON request.owner_id = user.id 
                WHERE request.walker_id = ?
                ORDER BY request.created_at DESC";
                
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$walkerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // walker: accept request
    public function acceptRequest($requestId) {
        try{
            self::$pdo->beginTransaction();
            // get request details
            $stmt = self::$pdo->prepare("SELECT * FROM request WHERE id = ? AND status = 'pending'");
            $stmt->execute([$requestId]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$request) {
                return false; // request not found or already accepted
            }

            // update request status
            $stmt = self::$pdo->prepare("UPDATE request SET status = 'accepted' WHERE id = ?");
            return $stmt->execute([$requestId]);
    
            // create appointment record
            $stmt = self::$pdo->prepare("INSERT INTO   approvedAppointments 
            (request_id, start_time, end_time, dog_id, created_at) 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
            return $stmt->execute([$requestId, $startTime, $endTime, $dogId]);
        }
        catch (Exception $e) {
            self::$pdo->rollBack();
            return $e->getMessage('Error occurred while accepting the request');
        }
    }

    //walker: reject request
    public function rejectRequest($requestId) {
        try{
            self::$pdo->beginTransaction();
            // get request details
            $stmt = self::$pdo->prepare("SELECT * FROM request WHERE id = ? AND status = 'pending'");
            $stmt->execute([$requestId]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$request) {
                return false; // request not found or already accepted
            }
            // update request status
            $stmt = self::$pdo->prepare("UPDATE request SET status = 'rejected' WHERE id = ?");
            return $stmt->execute([$requestId]);
        }
        catch (Exception $e) {
            self::$pdo->rollBack();
            return $e->getMessage('Error occurred while rejecting the request');
        }
    }
    // owner: cancel request
    public function cancelRequest($requestId) {
        try{
            self::$pdo->beginTransaction();
            // get request details
            $stmt = self::$pdo->prepare("SELECT * FROM request WHERE id = ? AND status = 'pending'");
            $stmt->execute([$requestId]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$request) {
                return false; // request not found or already accepted
            }
            // update request status
            $stmt = self::$pdo->prepare("UPDATE request SET status = 'canceled' WHERE id = ?");
            return $stmt->execute([$requestId]);
        }
        catch (Exception $e) {
            self::$pdo->rollBack();
            return $e->getMessage('Error occurred while canceling the request');
        }
    }
}