<?php
class Hashtag
{
    private ?int $id = null;

    public function __construct(private string $name, private DateTime $createdAt = new DateTime())
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

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "createdAt" => $this->createdAt

        ];
    }
}
