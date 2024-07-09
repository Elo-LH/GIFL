<?php

class Router
{
    private BlogController $bc;

    public function __construct()
    {

        $this->bc = new BlogController();
    }
    public function handleRequest(array $get): void
    {
        if (!isset($get["route"])) {
            $this->bc->home();
        } else if ($get["route"] === "category") {
            $this->bc->category($get["category_id"]);
        } else {
            $this->bc->home();
        }
    }
}
