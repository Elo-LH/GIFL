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
            $um = new UserManager;
            //if modifications have been submitted
            if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['avatar'])) {

                //apply modifications in db
                $um->updateUser($id, $_POST['email'], $_POST['name'], $_POST['avatar']);
            }
            //find user
            $user = $um->findById($id);
            $user->setId($id);
            //get all users
            $users = $um->findAll();
            $this->render("back-office.html.twig", ["users" => $users, "user" => $user]);
        }
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
