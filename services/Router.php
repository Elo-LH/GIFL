<?php

class Router
{
    private PublicController $pc;
    private AuthController $ac;
    private PrivateController $prc;
    private AdminController $adc;

    public function __construct()
    {

        $this->pc = new PublicController();
        $this->ac = new AuthController();
        $this->prc = new PrivateController();
        $this->adc = new AdminController();
    }
    public function handleRequest(array $get): void
    {
        if (!isset($get["route"])) {
            $this->pc->home();
        } else if ($get["route"] === "search") {
            $this->pc->search();
        } else if ($get["route"] === "search-result") {
            $this->pc->searchResult();
        } else if ($get["route"] === "hashtag-page") {
            $this->pc->hashtagPage();
        } else if ($get["route"] === "gif") {
            $this->pc->gif();
        } else if ($get["route"] === "sign-in") {
            $this->ac->signIn();
        } else if ($get["route"] === "sign-up") {
            $this->ac->signUp();
        } else if ($get["route"] === "check-sign-in") {
            $this->ac->checkSignIn();
        } else if ($get["route"] === "check-sign-up") {
            $this->ac->checkSignUp();
        } else if ($get["route"] === "sign-out") {
            $this->ac->signOut();
        } else if ($get["route"] === "check-sign-out") {
            $this->ac->checkSignOut();
        } else if ($get["route"] === "welcome") {
            $this->prc->welcome();
        } else if ($get["route"] === "back-office") {
            $this->adc->backOffice();
        } else if ($get["route"] === "modify-user") {
            $this->adc->modifyUser();
        } else if ($get["route"] === "delete-user") {
            $this->adc->deleteUser();
        } else if ($get["route"] === "error") {
            $this->pc->error($_GET['error']);
        } else {
            $this->pc->home();
        }
    }
}
