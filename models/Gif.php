<?php
class Gif
{
    private ?int $id = null;

    public function __construct(
        private string $link,
        private User $author,
        private DateTime $createdAt = new DateTime(),
        private bool $reported = false
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLink(): string
    {
        return $this->link;
    }
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function isReported(): bool
    {
        return $this->reported;
    }
    public function setReported(bool $reported): void
    {
        $this->reported = $reported;
    }
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "link" => $this->link,
            "author" => $this->author,
            "createdAt" => $this->createdAt,
            "reported" => $this->reported
        ];
    }
}
