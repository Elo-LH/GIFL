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
            $user = new User($result["name"], $result["email"], $result["password"], $result["avatar"], $result["admin"]);
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
            $user = new User($result["name"], $result["email"], $result["password"], $result["avatar"], $result["admin"]);
            $user->setId($result["user_id"]);
            return $user;
        }
        return null;
    }
    public function createUser(User $user): void
    {
        $parameters = [
            "name" => $user->getName(),
            "password" => $user->getPassword(),
            "email" => $user->getEmail(),
            "avatar" => $user->getAvatar(),
        ];
        $query = $this->db->prepare('INSERT INTO users (email, name, password, avatar) VALUES (:email, :name, :password, :avatar)');
        $query->execute($parameters);
        $user->setId($this->db->lastInsertId());
    }
}
