<?php

class BlogController extends AbstractController
{
    public function home(): void
    {

        $this->render("home.html.twig", []);
    }

    public function category($id): void
    {
        $postManager = new PostManager;
        $categoryManager = new CategoryManager;
        $categories = $categoryManager->findAll();
        $category = $categoryManager->findOne($id);
        $posts = $postManager->findByCategory($id);
        $this->render("category.html.twig", ["posts" => $posts, "categories" => $categories, "category" => $category]);
    }
}
