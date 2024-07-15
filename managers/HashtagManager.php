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
        } else {
            return null;
        }
    }
}
