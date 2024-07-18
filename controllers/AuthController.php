<?php

class AuthController extends AbstractController
{
    public function signIn(): void
    {
        $this->render("sign-in.html.twig", []);
    }

    public function checkSignIn(): void
    {
        //get form data
        $email = $_POST['email'];
        $password = $_POST['password'];
        //init manager
        $instance = new UserManager;
        //find user 
        $userFound = $instance->findByEmail($email);
        //if user not found in db give error
        if (!$userFound) {
            $this->redirect("index.php?route=error&error=Unknown Email, please register first.");
            //if user found check password
        } else {
            $hashFound = $userFound->getPassword();
            $isPasswordCorrect = password_verify($password, $hashFound);
            if ($isPasswordCorrect) {
                //connect session
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $userFound->getName();

                if ($userFound->isAdmin()) {

                    $_SESSION['role'] = "ADMIN";
                    $this->redirect("index.php?route=admin-page");
                } else {
                    $_SESSION['role'] = "USER";
                    //redirect to logged in home page
                    $this->render("welcome.html.twig", []);
                }
            } else {
                $this->redirect("index.php?route=error&error=Incorrect password");
            }
        }
    }

    public function signUp(): void
    {
        $this->render("sign-up.html.twig", []);
    }
    public function checkSignUp(): void
    {
        //get form data
        $email = $_POST['email'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $avatar = $_POST['avatar'];
        if ($avatar == "") {
            $gm = new GifManager;
            $avatar = $gm->getRandom1()->getLink();
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        //init manager
        $user = new User($name, $email, $hash, false, $avatar);
        $instance = new UserManager;
        //find user 
        $userFound = $instance->findByEmail($email);
        //if email allready used give error
        if ($userFound) {
            $this->redirect("index.php?route=error&error=Email allready used, please log in.");
            //if email not found create new user
        } else {
            $instance->createUser($user);
            //redirect to home
            $this->redirect("index.php?route=home");
        }
    }
    public function checkSignOut(): void
    {
        session_destroy();
        $this->redirect("index.php?route=sign-out");
    }
    public function signOut(): void
    {
        $this->render("sign-out.html.twig", []);
    }
}
