<?php

class AdminController extends AbstractController
{

    public function backOffice(): void
    {
        //get all users
        $um = new UserManager();
        $users = $um->findAll();
        $this->render("back-office.html.twig", ["users" => $users]);
    }
    public function deleteUser(): void
    {
        //get user id from params
        if (isset($_GET['user'])) {
            $id = $_GET['user'];
            //init manager
            $um = new UserManager();
            $um->deleteUser($id);
            $this->redirect("index.php?route=back-office");
        }
    }
    public function updateUser(): void
    {
        if (isset($_GET['user'])) {
            $id = $_GET['user'];
        //init manager
        $instance = new UserManager;
        $user = new User($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role']);
        $user->setId($_GET['user']);
        $instance->modifyUser($user);
        $route = "users";
        require 'templates/layout.phtml';
    }
    public function createUser(): void
    {
        //init manager
        $instance = new UserManager;
        //get post info
        $user = new User($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role']);
        $post = $instance->createUser($user);

        header('Location:index.php?route=adminpage');
    }
}
