<?php

class AdminController extends AbstractController
{

    public function backOffice(): void
    {
        if (isset($_SESSION['admin'])) {
            //get all users
            $um = new UserManager();
            $users = $um->findAll();
            //get reported GIFS if there is
            $gm = new GifManager();
            $reportedGifs = $gm->findReported();
            if (is_null($reportedGifs)) {
                $this->render("back-office.html.twig", ["users" => $users]);
            } else {
                $this->render("back-office.html.twig", ["users" => $users, "reportedGifs" => $reportedGifs]);
            }
        } else {
            $this->redirect("index.php?route=home");
        }
    }
    public function deleteUser(): void
    {
        if (isset($_SESSION['admin'])) {
            //get user id from params
            if (isset($_GET['user'])) {
                $id = $_GET['user'];
                //init manager
                $um = new UserManager();
                $um->deleteUser($id);
                $this->redirect("index.php?route=back-office");
            }
        } else {
            $this->redirect("index.php?route=home");
        }
    }
    public function updateUser(): void
    {

        if (isset($_SESSION['admin'])) {
            if (isset($_GET['user'])) {


                $id = $_GET['user'];
                //init manager
                $um = new UserManager;
                //if modifications have been submitted
                if (isset($_POST['email']) && isset($_POST['name'])) {

                    // check CSRF token
                    $tokenManager = new CSRFTokenManager();
                    if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {

                        //update mail, name and avatar
                        if (isset($_POST['avatar']) && $_POST['avatar'] == "") {
                            $gm = new GifManager;
                            $avatar = $gm->getRandom1()->getLink();
                        } else {
                            $avatar = $_POST['avatar'];
                        }
                        //apply modifications in db
                        $um->updateUser($id, $_POST['email'], $_POST['name'], $avatar);

                        //update password if a new one has been submitted
                        if (isset($_POST['password']) && isset($_POST['password-confirm'])) {
                            //check confirm password 
                            if ($_POST["password"] === $_POST["password-confirm"]) {
                                //check password is 8chars minimum with at least 1 maj, 1min 1 number and 1 special char
                                $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}/';
                                if (preg_match($password_pattern, $_POST["password"])) {
                                    $hash = password_hash($_POST["password"], PASSWORD_BCRYPT);
                                    //init manager and apply to db
                                    $um = new UserManager;
                                    $um->updateUserPassword($id, $hash);
                                    $this->redirect("index.php?route=back-office");
                                } else {
                                    $this->redirect("index.php?route=error&error=Passwords must be at least 8 characters long, with at least one uppercase letter, one lowercase letter, one number and one special character.");
                                }
                            } else {
                                $this->redirect("index.php?route=error&error=Passwords do not match");
                            }
                        } else {
                            $this->redirect("index.php?route=back-office");
                        }
                    } else {
                        $this->redirect("index.php?route=error&error=Invalid CSRF token");
                    }
                }

                //find user
                $user = $um->findById($id);
                $user->setId($id);
                //get all users
                $users = $um->findAll();
                $this->render("back-office.html.twig", ["users" => $users, "user" => $user]);
            }
        } else {
            $this->redirect("index.php?route=home");
        }
    }
    public function createUser(): void
    {
        if (isset($_SESSION['admin'])) {
            //init manager
            $instance = new UserManager;
            //get post info
            $user = new User($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role']);
            $post = $instance->createUser($user);

            header('Location:index.php?route=adminpage');
        } else {
            $this->redirect("index.php?route=home");
        }
    }
    public function toggleAdmin(): void
    {
        if (isset($_SESSION['admin'])) {
            if (isset($_GET['user'])) {
                $id = $_GET['user'];
                //init manager
                $um = new UserManager;
                //get post info
                $um->toggleAdmin($id);
                $this->redirect("index.php?route=back-office");
            }
        } else {
            $this->redirect("index.php?route=home");
        }
    }
    public function deleteGif(): void
    {
        if (isset($_SESSION['admin'])) {
            //get user id from params
            if (isset($_GET['gif'])) {
                $id = $_GET['gif'];
                //init manager
                $gm = new GifManager();
                $gm->deleteGif($id);
                $this->redirect("index.php?route=back-office");
            }
        } else {
            $this->redirect("index.php?route=home");
        }
    }
    public function reinstateGif(): void
    {
        if (isset($_SESSION['admin'])) {
            if (isset($_GET['gif'])) {
                $id = $_GET['gif'];
                //init manager
                $gm = new GifManager;
                //get post info
                $gm->toggleReported($id);
                $this->redirect("index.php?route=back-office");
            }
        } else {
            $this->redirect("index.php?route=home");
        }
    }
}
