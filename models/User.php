<?php
class User
{
    private ?int $id = null;

    public function __construct(private string $email, private string $name, private string $password, private bool $admin = true, private ?string $avatar = null)
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }
    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }
}
