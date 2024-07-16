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
            $collection = new Collection($user, $result["name"], DateTime::createFromFormat('Y-m-d H:i:s', $result["created_at"]));
            $collection->setId($result["collection_id"]);
            return $collection;
        } else {
            return null;
        }
    }
}
