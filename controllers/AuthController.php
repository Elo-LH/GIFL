<?php

class AuthController extends AbstractController
{
    public function signIn(): void
    {
        $this->render("sign-in.html.twig", []);
    }

    public function checkSignIn(): void
    {
        // check if data received from form
        if (isset($_POST["email"]) && isset($_POST["password"])) {
            // check CSRF token
            $tokenManager = new CSRFTokenManager();

            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {

                //get form data
                $email = htmlspecialchars($_POST['email']);
                $password = htmlspecialchars($_POST['password']);
                //init manager
                $instance = new UserManager;
                //find user 
                $userFound = $instance->findByEmail($email);
                //if user not found in db give error
                if (!$userFound) {
                    $this->redirect("index.php?route=error&error=Unknown Email $email, please register first.");
                    //if user found check password
                } else {
                    $hashFound = $userFound->getPassword();
                    $isPasswordCorrect = password_verify($password, $hashFound);
                    if ($isPasswordCorrect) {
                        //connect session
                        $_SESSION['email'] = $email;
                        $_SESSION['name'] = $userFound->getName();
                        $_SESSION['avatar'] = $userFound->getAvatar();

                        if ($userFound->isAdmin()) {

                            $_SESSION['admin'] = true;
                            $this->redirect("index.php?route=welcome");
                        } else {

                            //redirect to logged in home page
                            $this->redirect("index.php?route=welcome");
                        }
                    } else {
                        $this->redirect("index.php?route=error&error=Incorrect password");
                    }
                }
            } else {
                $this->redirect("index.php?route=error&error=Invalid CSRF token");
            }
        } else {
            $this->redirect("index.php?route=error&error=Missing informations");
        }
    }

    public function signUp(): void
    {
        $this->render("sign-up.html.twig", []);
    }
    public function checkSignUp(): void
    {

        // check if data received from form
        if (isset($_POST["email"]) && isset($_POST["name"]) && isset($_POST["password"]) && isset($_POST["password-confirm"])) {

            // check CSRF token
            $tokenManager = new CSRFTokenManager();
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {

                //check confirm password 
                if ($_POST["password"] === $_POST["password-confirm"]) {
                    //check password is 8chars minimum with at least 1 maj, 1min 1 number and 1 special char
                    $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}/';
                    if (preg_match($password_pattern, $_POST["password"])) {

                        //get form data
                        $email = htmlspecialchars($_POST['email']);
                        $name = htmlspecialchars($_POST['name']);
                        $avatar = htmlspecialchars($_POST['avatar']);
                        // If no avatar chosen byc user, assign one random GIF
                        if ($avatar == "") {
                            $gm = new GifManager;
                            $avatar = $gm->getRandom1()->getLink();
                        }
                        $hash = password_hash($_POST["password"], PASSWORD_BCRYPT);
                        //init manager
                        $user = new User($email, $name, $hash, $avatar);
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
                    } else {
                        $this->redirect("index.php?route=error&error=Passwords must be at least 8 characters long, with at least one uppercase letter, one lowercase letter, one number and one special character.

");
                    }
                } else {
                    $this->redirect("index.php?route=error&error=Passwords do not match");
                }
            } else {
                $this->redirect("index.php?route=error&error=Invalid CSRF token");
            }
        } else {
            $this->redirect("index.php?route=error&error=Missing informations");
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
