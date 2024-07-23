<?php
class UserManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findById(int $id): ?User
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE user_id=:id');

        $parameters = [
            "id" => $id
        ];

        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $user = new User($result["email"], $result["name"], $result["password"], $result["avatar"], $result["admin"]);
            $user->setId($result["user_id"]);
            return $user;
        }
        return null;
    }
    public function findByEmail(string $email): ?User
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE email=:email');

        $parameters = [
            "email" => $email
        ];

        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $user = new User($result["email"], $result["name"], $result["password"], $result["avatar"], $result["admin"]);
            $user->setId($result["user_id"]);
            return $user;
        }
        return null;
    }

    public function findAll(): ?array
    {
        $query = $this->db->prepare('SELECT * FROM users');
        $parameters = [];
        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        if ($results) {
            foreach ($results as $result) {
                $user = new User($result["email"], $result["name"], $result["password"], $result["avatar"], $result["admin"]);
                $user->setId($result["user_id"]);
                array_push($users, $user);
            }
            return $users;
        }
        return null;
    }

    public function createUser(User $user): void
    {
        $parameters = [
            "email" => $user->getEmail(),
            "name" => $user->getName(),
            "password" => $user->getPassword(),
            "avatar" => $user->getAvatar(),
        ];
        $query = $this->db->prepare('INSERT INTO users (email, name, password, avatar) VALUES (:email, :name, :password, :avatar)');
        $query->execute($parameters);
        $user->setId($this->db->lastInsertId());
    }
    public function deleteUser(int $id): void
    {
        //change user_id of public collections to GIFL public domain
        $query = $this->db->prepare('UPDATE collections SET user_id=1 WHERE user_id=:id AND private=0');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        //delete private collections 
        //delete from collections AND collections_hashtags AND collections_gifs
        $query = $this->db->prepare('DELETE FROM collections WHERE user_id=:id AND private=1');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);

        //change user_id of all updated GIFS to GIFL public domain
        $query = $this->db->prepare('UPDATE gifs SET user_id=1 WHERE user_id=:id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        //delete from users 
        $query = $this->db->prepare('DELETE FROM users WHERE user_id=:id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
    }
    public function updateUser(int $id, string $email, string $name, string $avatar)
    {
        $parameters = [
            "id" => $id,
            "email" => $email,
            "name" => $name,
            "avatar" => $avatar,
        ];
        $query = $this->db->prepare("UPDATE users SET email=:email, name=:name, avatar=:avatar WHERE user_id =:id");
        $query->execute($parameters);
    }
}
