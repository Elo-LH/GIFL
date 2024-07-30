<?php

class Router
{
    private PublicController $pc;
    private AuthController $ac;
    private PrivateController $prc;
    private AdminController $adc;
    private APIController $apic;

    public function __construct()
    {

        $this->pc = new PublicController();
        $this->ac = new AuthController();
        $this->prc = new PrivateController();
        $this->adc = new AdminController();
        $this->apic = new APIController();
    }
    public function handleRequest(array $get): void
    {
        // Public routes
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
        }
        // Authentification routes
        else if ($get["route"] === "sign-in") {
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
        }
        // Private routes
        else if ($get["route"] === "welcome") {
            $this->prc->welcome();
        } else if ($get["route"] === "my-collections") {
            $this->prc->myCollections();
        } else if ($get["route"] === "collection") {
            $this->prc->collection();
        } else if ($get["route"] === "remove-gif-from-collection") {
            $this->prc->removeGifFromCollection();
        } else if ($get["route"] === "toggle-collection-privacy") {
            $this->prc->toggleCollectionPrivacy();
        } else if ($get["route"] === "upload") {
            $this->prc->upload();
        }
        // Admin routes
        else if ($get["route"] === "back-office") {
            $this->adc->backOffice();
        } else if ($get["route"] === "update-user") {
            $this->adc->updateUser();
        } else if ($get["route"] === "delete-user") {
            $this->adc->deleteUser();
        } else if ($get["route"] === "toggle-admin") {
            $this->adc->toggleAdmin();
        } else if ($get["route"] === "delete-gif") {
            $this->adc->deleteGif();
        } else if ($get["route"] === "reinstate-gif") {
            $this->adc->reinstateGif();
        }
        // API routes
        else if ($get["route"] === "get-hashtag-search-result") {
            $this->apic->getHashtagSearchResult();
        }
        // Other routes
        else if ($get["route"] === "error") {
            $this->pc->error($_GET['error']);
        } else {
            $this->pc->home();
        }
    }
}
