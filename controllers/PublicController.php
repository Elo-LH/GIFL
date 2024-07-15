<?php

class PublicController extends AbstractController
{
    public function home(): void
    {
        $gm = new GifManager;
        $gifs = $gm->findRandom10();
        $this->render("home.html.twig", ["gifs" => $gifs]);
    }
    public function search(): void
    {
        $gm = new GifManager;
        $hm = new HashtagManager;
        $hashtagsLatestGifs = [];
        //Getting the 10 latest created hashtags
        $hashtags = $hm->findLatest10();
        //For each of these latest hashtags, get the latest associated gif
        foreach ($hashtags as $hashtag) {
            $gif = $gm->findLatestWithHashtag($hashtag->getId());
            array_push($hashtagsLatestGifs, $gif);
        }
        $this->render("search.html.twig", ["hashtagsLatestGifs" => $hashtagsLatestGifs, "hashtags" => $hashtags]);
    }
    public function searchResult(): void
    {
        if ($_POST['action'] === 'hashtag') {
            $this->render("search-result.html.twig", ["search" => "hashtag"]);
        } else {
            $this->render("search-result.html.twig", ["search" => "collection"]);
        }
    }
}
