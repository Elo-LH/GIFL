<?php
class HashtagManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findLatest10(): ?array
    {
        $query = $this->db->prepare('SELECT * FROM hashtags ORDER BY created_at DESC LIMIT 10');
        $parameters = [];
        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($results) {
            $hashtags = [];
            foreach ($results as $result) {
                $hashtag = new Hashtag($result["name"], DateTime::createFromFormat('Y-m-d H:i:s', $result["created_at"]));
                $hashtag->setId($result["hashtag_id"]);
                array_push($hashtags, $hashtag);
            }
            return $hashtags;
        }
        return null;
    }
    public function findByName($name): ?Hashtag
    {
        $query = $this->db->prepare("SELECT * FROM hashtags WHERE name LIKE :name ORDER BY created_at DESC");
        $parameters = [
            "name" => "%" . $name . "%"
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $hashtag = new Hashtag($result["name"], DateTime::createFromFormat('Y-m-d H:i:s', $result["created_at"]));
            $hashtag->setId($result["hashtag_id"]);
            return $hashtag;
        }
        return null;
    }
    public function findAllContaining($input): ?array
    {
        $query = $this->db->prepare("SELECT * FROM hashtags WHERE name LIKE :input ORDER BY created_at DESC");
        $parameters = [
            "input" => "%" . $input . "%"
        ];
        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($results) {
            $hashtags = [];
            foreach ($results as $result) {
                $hashtag = new Hashtag($result["name"], DateTime::createFromFormat('Y-m-d H:i:s', $result["created_at"]));
                $hashtag->setId($result["hashtag_id"]);
                array_push($hashtags, $hashtag);
            }
            return $hashtags;
        }
        return null;
    }
    public function findByExactName($name): ?Hashtag
    {
        $query = $this->db->prepare("SELECT * FROM hashtags WHERE name = :name ORDER BY created_at DESC");
        $parameters = [
            "name" => "%" . $name . "%"
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $hashtag = new Hashtag($result["name"], DateTime::createFromFormat('Y-m-d H:i:s', $result["created_at"]));
            $hashtag->setId($result["hashtag_id"]);
            return $hashtag;
        }
        return null;
    }
    public function findById($id): ?Hashtag
    {
        $query = $this->db->prepare("SELECT * FROM hashtags WHERE hashtag_id = :id");
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $hashtag = new Hashtag($result["name"], DateTime::createFromFormat('Y-m-d H:i:s', $result["created_at"]));
            $hashtag->setId($result["hashtag_id"]);
            return $hashtag;
        }
        return null;
    }
    public function createHashtag(Hashtag $hashtag, int $gif_id): void
    {
        //add new Hashtag 
        $query = $this->db->prepare("INSERT INTO hashtags(name, created_at) VALUES(:name, :createdAt) ");
        $parameters = [
            "name" => $hashtag->getName(),
            "createdAt" => $hashtag->getCreatedAt()->format('Y-m-d H:i:s')
        ];
        $query->execute($parameters);
        //add to link table with associated gif
        $hashtag->setId($this->db->lastInsertId());
        //add to uploads collection
        $query = $this->db->prepare("INSERT INTO gifs_hashtags(gif_id, hashtag_id) VALUES(:gif_id, :hashtag_id) ");
        $parameters = [
            "gif_id" => $gif_id,
            "hashtag_id" => $hashtag->getId()
        ];
        $query->execute($parameters);
    }
}
