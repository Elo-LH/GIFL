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
    public function findPublicByName($name): ?Collection
    {
        $query = $this->db->prepare("SELECT * FROM collections WHERE name LIKE :name AND private = 0 ORDER BY created_at DESC");
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
    public function initNewUserCollections($id): void
    {
        $dateTime = new DateTime();
        $query = $this->db->prepare("INSERT INTO collections(user_id, name, private, created_at) VALUES(:id, 'favorites', 1, :createdAt), (:id, 'uploads', 1, :createdAt) ");
        $parameters = [
            "id" => $id,
            "createdAt" => $dateTime->format('Y-m-d H:i:s')
        ];
        $query->execute($parameters);
    }
    public function findUserUploads($id): ?Collection
    {
        $query = $this->db->prepare("SELECT * FROM collections WHERE user_id = :id AND name = 'uploads'");
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
    public function createCollection(int $user_id, string $name, bool $private): void
    {
        $dateTime = new DateTime();

        $query = $this->db->prepare("INSERT INTO collections(user_id, name, private, created_at) VALUES(:user_id, :name, :private, :createdAt) ");
        $parameters = [
            "user_id" => $user_id,
            "name" => $name,
            "private" => $private,
            "createdAt" => $dateTime->format('Y-m-d H:i:s')
        ];
        $query->execute($parameters);
    }
    public function isCollectionFromUser(int $collection_id, int $user_id): bool
    {
        $query = $this->db->prepare("SELECT * FROM collections WHERE user_id = :user_id AND collection_id = :collection_id");
        $parameters = [
            "user_id" => $user_id,
            "collection_id" => $collection_id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function deleteCollection($id): void
    {
        $query = $this->db->prepare("DELETE FROM collections WHERE collection_id = :id");
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
    }
    public function findByNameFromUser($name, $user_id): ?Collection
    {
        $query = $this->db->prepare("SELECT * FROM collections WHERE name = :name AND user_id = :user_id");
        $parameters = [
            "name" =>  $name,
            "user_id" => $user_id
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
}
