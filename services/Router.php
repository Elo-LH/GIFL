<?php

class Router
{
    private PublicController $pc;

    public function __construct()
    {

        $this->pc = new PublicController();
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
        } else if ($get["route"] === "error") {
            $this->pc->error($_GET['error']);
        } else {
            $this->pc->home();
        }
    }
}
