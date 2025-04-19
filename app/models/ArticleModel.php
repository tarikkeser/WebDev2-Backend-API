<?php

namespace App\Models;


class ArticleModel extends Model
{
    static $resultLimit = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($page = 1)
    {
        $offset = ($page - 1) * self::$resultLimit; // Calculate offset

        // Prepare and execute the paginated query
        $query = self::$pdo->prepare('SELECT * FROM articles ORDER BY posted_at DESC LIMIT :limit OFFSET :offset');
        $query->bindParam(':limit', self::$resultLimit, \PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $query->execute();

        // Fetch results
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get($id)
    {
        $statement = self::$pdo->prepare("SELECT * FROM articles WHERE id = :id");
        $statement->execute(["id" => $id]);
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($article)
    {
        // Convert ISO date format to MySQL datetime if posted_at is provided
        $postedAt = isset($article["posted_at"])
            ? date('Y-m-d H:i:s', strtotime($article["posted_at"]))
            : date('Y-m-d H:i:s');

        // Extract only the needed properties
        $data = [
            "title" => $article["title"],
            "content" => $article["content"],
            "author" => $article["author"],
            "posted_at" => $postedAt
        ];

        $query = "INSERT INTO articles (title, content, author, posted_at) VALUES (:title, :content, :author, :posted_at)";
        $statement = self::$pdo->prepare($query);
        $statement->execute($data);

        $newArticleId = self::$pdo->lastInsertId();
        return $this->get($newArticleId);
    }

    public function update($id, $article)
    {
        $query = "UPDATE articles SET title = :title, content = :content, author = :author WHERE id = :id";
        $statement = self::$pdo->prepare($query);
        $statement->execute([
            "id" => $id,
            "title" => $article["title"],
            "content" => $article["content"],
            "author" => $article["author"]
        ]);

        // Return the updated article
        return $this->get($id);
    }

    public function delete($id)
    {
        $query = "DELETE FROM articles WHERE id = :id";
        $statement = self::$pdo->prepare($query);
        $statement->execute(["id" => $id]);
    }
}
