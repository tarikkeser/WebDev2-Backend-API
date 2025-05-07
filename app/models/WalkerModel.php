<?php

namespace App\Models;

use PDO;

class WalkerModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // get all walkers
    public function getAllWalkers(){
       $stmt=self::$pdo->prepare("SELECT * FROM user WHERE role ='walker'");
       $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}