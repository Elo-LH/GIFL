<?php

class PublicController extends AbstractController
{
    public function home(): void
    {
        $gm = new GifManager;
        $gifs = $gm->findLatest10();
        $this->render("home.html.twig", ["gifs" => $gifs]);
    }
}
