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
        } else {
            $this->pc->home();
        }
    }
}
