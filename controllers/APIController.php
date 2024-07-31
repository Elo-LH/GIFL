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
    public function getGifInfo(): void
    {
        //if user is connected, display if is allready in collections and add to collections

        //get gif ID in GET params
        if (isset($_GET['gif'])) {
            $array = [];

            //find by id
            $gm = new GifManager();
            $gif = $gm->findById($_GET['gif']);
            array_push($array, $gif->toArray());
            //find hashtags
            $hashtags = $gm->findHashtags($_GET['gif']);

            foreach ($hashtags as $hashtag) {
                array_push($array, $hashtag->toArray());
            }
            echo json_encode($array);
        } else {
            echo null;
        }
    }
}
