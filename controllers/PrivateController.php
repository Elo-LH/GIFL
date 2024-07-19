<?php

class PrivateController extends AbstractController
{
    public function welcome(): void
    {
        $this->render("welcome.html.twig", []);
    }
}
