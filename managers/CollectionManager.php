<?php
class CollectionManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findByName($name): ?Collection
    {
        $query = $this->db->prepare("SELECT * FROM collections WHERE name LIKE :name ORDER BY created_at DESC");
        $parameters = [
            "name" => "%" . $name . "%"
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {

            $um = new UserManager;
            $user = $um->findById($result['user_id']);
            $collection = new Collection($user, $result["name"], $result["private"], DateTime::createFromFormat('Y-m-d H:i:s', $result["created_at"]));
            $collection->setId($result["collection_id"]);
            return $collection;
        } else {
            return null;
        }
    }
    public function findById($id): ?Collection
    {
        $query = $this->db->prepare("SELECT * FROM collections WHERE collection_id = :id");
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $um = new UserManager;
            $user = $um->findById($result['user_id']);
            $collection = new Collection($user, $result["name"], $result["private"], DateTime::createFromFormat('Y-m-d H:i:s', $result["created_at"]));
            $collection->setId($result["collection_id"]);
            return $collection;
        } else {
            return null;
        }
    }
    public function findByUserId($id): ?array
    {
        $query = $this->db->prepare("SELECT * FROM collections WHERE user_id = :id ORDER BY created_at DESC");
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $collections = [];
        if ($results) {
            $um = new UserManager;
            $user = $um->findById($id);
            foreach ($results as $result) {
                $collection = new Collection($user, $result["name"], $result["private"], DateTime::createFromFormat('Y-m-d H:i:s', $result["created_at"]));
                $collection->setId($result["collection_id"]);
                array_push($collections, $collection);
            }
            return $collections;
        } else {
            return null;
        }
    }
    public function toggleCollectionPrivacy($id): void
    {
        $query = $this->db->prepare("UPDATE collections SET private= !private WHERE collection_id =:id");
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
    }
}
