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

    // OWNER REQUEST TRANSACTIONS

    // Owner: send request to a walker
public function createRequest($dogId, $walkerId, $ownerId, $startTime, $endTime)
{
    
    $sql = "
        SELECT 1 FROM approvedAppointments
        WHERE dog_id = ?
          AND (start_time < ? AND end_time > ?)
        LIMIT 1
    ";
    $params = [
        $dogId,
        $endTime, 
        $startTime 
    ];
    $stmt = self::$pdo->prepare($sql);
    $stmt->execute($params);
    if ($stmt->fetch()) {
       return false; // There is an existing appointment that conflicts with the requested time.
    }
    
    $stmt = self::$pdo->prepare("INSERT INTO request (dog_id, walker_id, owner_id, start_time, end_time, status, created_at) 
    VALUES (?, ?, ?, ?, ?, 'pending', CURRENT_TIMESTAMP);");
    return $stmt->execute([$dogId, $walkerId, $ownerId, $startTime, $endTime]);
}

    // owner : get sent requests 
    public function getRequestsByOwner($ownerId)
    {
        $sql = "SELECT 
                request.id,
                request.status, 
                dogs.name as dog_name, 
                user.name as walker_name,
                user.price as walker_price,
                DATE_FORMAT(request.start_time, '%Y-%m-%d %H:%i') as formatted_start_time,
                DATE_FORMAT(request.end_time, '%Y-%m-%d %H:%i') as formatted_end_time,
                DATE_FORMAT(request.created_at, '%Y-%m-%d %H:%i') as formatted_created_at
                FROM request 
                JOIN dogs ON request.dog_id = dogs.id
                JOIN user ON request.walker_id = user.id 
                WHERE request.owner_id = ?  
                ORDER BY request.created_at DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      // owner: cancel -delete- request
      public function cancelRequest($requestId)
      {
          $stmt = self::$pdo->prepare("DELETE FROM request WHERE id = ?");
          return $stmt->execute([$requestId]);
      }

    // WALKER REQUEST TRANSACTIONS

    // walker : get received requests by walker
    public function getRequestsByWalker($walkerId)
    {
        $sql = "SELECT 
                request.id,
                request.status,
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
    public function acceptRequest($requestId)
    {
        self::$pdo->beginTransaction();
        // get request details
        $stmt = self::$pdo->prepare("SELECT * FROM request WHERE id = ? AND status = 'pending'");
        $stmt->execute([$requestId]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$request) {
            self::$pdo->rollBack();
            return false;
        }
        // Check for conflicts with existing appointments.
        $conflictStmt = self::$pdo->prepare("
        SELECT 1 FROM approvedAppointments
        WHERE dog_id = ?
        AND (start_time < ? AND end_time > ?)
        LIMIT 1
    ");
    $conflictStmt->execute([
        $request['dog_id'],
        $request['end_time'],
        $request['start_time']
    ]);
    if ($conflictStmt->fetch()) {
        self::$pdo->rollBack();
        return false;
    }

        // Update request status to accepted.
        $updateStmt = self::$pdo->prepare("UPDATE request SET status = 'accepted' WHERE id = ?");
        $updateResult = $updateStmt->execute([$requestId]);
        if (!$updateResult) {
            self::$pdo->rollBack();
            return false;
        }
       // Remove a request if another request for the same dog overlaps with the accepted request.
        $rejectStmt = self::$pdo->prepare("
        UPDATE request SET status = 'rejected'
        WHERE dog_id = ? AND id != ? AND status = 'pending'
          AND (start_time < ? AND end_time > ?)
    ");
    $rejectStmt->execute([
        $request['dog_id'],
        $requestId,
        $request['end_time'],
        $request['start_time']
    ]);

        // create appointment record
        $insertStmt = self::$pdo->prepare("INSERT INTO   approvedAppointments 
            (request_id, start_time, end_time, dog_id, created_at) 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $insertResult = $insertStmt->execute([
            $requestId,
            $request['start_time'],
            $request['end_time'],
            $request['dog_id']
        ]);
        if (!$insertResult) {
            self::$pdo->rollBack();
            return false; // failed to create appointment record
        }

        // commit transaction
        self::$pdo->commit();
        return true; // request accepted and appointment created successfully
    }

    // walker: reject request
    public function rejectRequest($requestId)
    {
        self::$pdo->beginTransaction();
        // get request details
        $stmt = self::$pdo->prepare("SELECT * FROM request WHERE id = ? AND status = 'pending'");
        $stmt->execute([$requestId]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$request) {
            return false; // request not found or already accepted
        }
        // Update request status to rejected.
        $updateStmt = self::$pdo->prepare("UPDATE request SET status = 'rejected' WHERE id = ?");
        $updateResult = $updateStmt->execute([$requestId]);
        if (!$updateResult) {
            self::$pdo->rollBack();
            return false; // failed to update request status
        }
        // commit transaction
        self::$pdo->commit();
        return true; // request rejected successfully

    }
  
}
