<?php

namespace App\Models;

use PDO;

class DogModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // get all dogs by owner id
    public function getDogsByOwner($ownerId)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM dogs WHERE owner_Id = ?");
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // get single dog by id 
    public function getDog($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM dogs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // create a new dog for the current user.
    public function createDog($inputData)
    {
        $data = [
            "name" => $inputData["name"],
            "breed" => $inputData["breed"],
            "age" => $inputData["age"],
            "size" => $inputData["size"],
            "photo" => $inputData["photo"] ?? null,
            "owner_id" => $inputData["owner_id"]
        ];
        $query = "INSERT INTO dogs (name, breed, age, size, photo, owner_id) VALUES (:name, :breed,:age, :size, :photo , :owner_id)";
        $statement = self::$pdo->prepare($query);
        $statement->execute($data);

        return $this->getDog(self::$pdo->lastInsertId());
    }

    // update dog 
    public function updateDog($id,$data)
    {
        $data = [
            "id" =>$id,
            "name" => $data["name"],
            "breed" => $data["breed"],
            "age" => $data["age"],
            "size" => $data["size"],
            "photo" => $data["photo"] ?? null, 
        ];

        $query = "UPDATE dogs SET 
        name = :name, breed = :breed, age = :age, size = :size, photo = :photo WHERE id = :id";
        
        $statement = self::$pdo->prepare($query);
        $statement->execute($data);

        return $this->getDog($id);
    }

    // delete dog 
    public function deleteDog($id){
        $query = "DELETE FROM dogs Where id=:id";
        $statement= self::$pdo->prepare($query);
        $statement->execute(["id"=>$id]);
    }

}