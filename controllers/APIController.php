<?php

class APIController extends AbstractController
{

    public function getHashtagSearchResult(): void
    {
        //get search input in GET params
        if (isset($_GET['input'])) {
            //find by hashtag 
            $hm = new HashtagManager();
            $hashtag = $hm->findByName($_GET['input']);
            $gm = new GifManager();
            $gifs = $gm->findByHashtag($hashtag->getId());
            // return $gifs;
            $array = [];
            foreach ($gifs as $gif) {
                array_push($array, $gif->toArray());
            }
            echo json_encode($array);
        } else {
            echo null;
        }
    }
}
