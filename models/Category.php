<?php
class Category
{
    private int $id = 0;
    private array $posts = [];

    public function __construct(
        private string $title,
        private string $description
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Get the value of posts
     */
    public function getPosts(): array
    {
        return $this->posts;
    }

    /**
     * Set the value of posts
     */
    public function setPosts(array $posts): void
    {
        $this->posts = $posts;
    }

    public function addPost(Post $post): void
    {
        $isIndexFound = array_search($post, $this->posts);
        if (!$isIndexFound) {
            array_push($this->posts, $post);
        }
    }
    public function removePost(Post $post): void
    {
        $indexToRemove = array_search($post, $this->posts);
        unset($this->posts, $indexToRemove);
    }
}
