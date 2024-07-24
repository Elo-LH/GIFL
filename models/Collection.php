<?php
class Collection
{
    private ?int $id = null;

    public function __construct(private User $author, private string $name, private bool $private, private DateTime $createdAt = new DateTime())
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

    public function getAuthor(): User
    {
        return $this->author;
    }
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrivate(): string
    {
        return $this->private;
    }
    public function setPrivate(string $private): void
    {
        $this->private = $private;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
