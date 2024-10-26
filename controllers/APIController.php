<?php

class APIController extends AbstractController
{

    public function getFirstHashtagSearchResult(): void
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
    public function getHashtagSearchResult(): void
    {
        //get search input in GET params
        if (isset($_GET['input']) && $_GET['input'] != "") {
            $input = htmlspecialchars($_GET['input']);
            //find all hashtags containing this input 
            $hm = new HashtagManager();
            $hashtags = $hm->findAllContaining($input);
            $array = [];
            if ($hashtags != []) {
                foreach ($hashtags as $hashtag) {
                    // for each hashtag found add all its gifs to the return array;
                    $gm = new GifManager();
                    $gifs = $gm->findByHashtag($hashtag->getId());
                    if ($gifs != []) {
                        foreach ($gifs as $gif) {
                            array_push($array, $gif);
                        }
                    } else {
                        echo null;
                    }
                }
                // $cleanedArray = array_map("unserialize", array_unique(array_map("serialize", $array)));
                //filter duplicate gifs
                $cleanedArray = array_filter($array, function ($gif) {
                    static $idList = array();
                    if (in_array($gif->getId(), $idList)) {
                        return false;
                    }
                    $idList[] = $gif->getId();
                    return true;
                });
                $resultArray = [];
                foreach ($cleanedArray as $gif) {
                    array_push($resultArray, $gif->toArray());
                }

                echo json_encode($resultArray);
            } else {
                echo null;
            }
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
            if (!is_null($hashtags)) {

                foreach ($hashtags as $hashtag) {
                    array_push($array, $hashtag->toArray());
                }
            }
            echo json_encode($array);
        } else {
            echo null;
        }
    }
    public function putGifReported(): void
    {
        if (isset($_SESSION['admin'])) {
            if (isset($_GET['gif'])) {
                $id = $_GET['gif'];
                //init manager
                $gm = new GifManager;
                //get post info
                $gm->setReported($id);
                echo 'gif reported';
            }
        } else {
            echo 'fail';
        }
    }
}
