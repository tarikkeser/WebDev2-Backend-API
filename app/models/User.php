<?php

namespace App\Models;

class User extends Model
{
    public function create($email, $password, $name, $role)
    {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        // Additional validation for email length and domain
        if (strlen($email) > 254) {
            throw new \InvalidArgumentException('Email is too long');
        }

        // Extract domain and validate
        $domain = substr(strrchr($email, "@"), 1);
        if (!checkdnsrr($domain, 'MX')) {
            throw new \InvalidArgumentException('Invalid email domain');
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = self::$pdo->prepare("INSERT INTO user (email, password, name, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $hashedPassword, $name, $role]);

        $userId = self::$pdo->lastInsertId();
        return $this->findById($userId);
    }

    public function findByEmail($email)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }



    // get user information by id 
    public function getUserInfo($id)
    {
        $stmt = self::$pdo->prepare("SELECT name,email,password,profile_picture,price,experience FROM user WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    // get user information by email
    public function getUserByEmail($email)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }   
    // update user information will be implemented.
}
