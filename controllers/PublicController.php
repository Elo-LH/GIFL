<?php

class PublicController extends AbstractController
{
    public function home(): void
    {
        $gm = new GifManager;
        $gifs = $gm->findRandom10();
        $this->render("home.html.twig", ["gifs" => $gifs]);
    }
    public function error($error): void
    {
        $this->render("error.html.twig", ["error" => $error]);
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

        if (isset($_POST['input-search']) && $_POST['input-search'] != "") {
            $input = htmlspecialchars($_POST['input-search']);
            if ($_POST['action'] === 'hashtag') {
                //search in db the hashtag corresponding to input
                $hm = new HashtagManager;
                $hashtag = $hm->findbyName($input);
                if (is_null($hashtag)) {
                    //If no hashtag has been found, display nothing was found
                    $this->redirect("index.php?route=error&error=No hashtag found");
                }
                //load gifs corresponding to this hashtag
                $gm = new GifManager;
                $gifs = $gm->findByHashtag($hashtag->getId());
                $this->render("search-result.html.twig", ["search" => "hashtag", "hashtag" => $hashtag, "gifs" => $gifs]);
            } else {
                //search in db the collection corresponding to input
                $cm = new CollectionManager;
                $collection = $cm->findPublicByName($input);
                if (is_null($collection)) {
                    //If no collection has been found, display nothing was found
                    $this->redirect("index.php?route=error&error=No collection found");
                }
                //load gifs corresponding to this colllection
                $gm = new GifManager;
                $gifs = $gm->findByCollection($collection->getId());
                $this->render("collection-public.html.twig", ["collection" => $collection, "gifs" => $gifs]);
            }
        } else {
            //If input is empty, redirect to search page
            $this->redirect("index.php?route=search");
        }
    }
    public function hashtagPage(): void
    {

        if (isset($_GET['hashtag'])) {
            $hashtagId = $_GET['hashtag'];
            //search in db the hashtag corresponding to input
            $hm = new HashtagManager;
            $hashtag = $hm->findbyId($hashtagId);
            if (is_null($hashtag)) {
                //If no hashtag has been found, display nothing was found
                $this->redirect("index.php?route=error&error=No hashtag found");
            }
            //load gifs corresponding to this hashtag
            $gm = new GifManager;
            $gifs = $gm->findByHashtag($hashtag->getId());
            $this->render("search-result.html.twig", ["search" => "hashtag", "hashtag" => $hashtag, "gifs" => $gifs]);
        } else {
            //If input is empty, redirect to search page
            $this->redirect("index.php?route=search");
        }
    }
    public function collection(): void
    {
        if (isset($_GET['collection'])) {
            $collectionId = $_GET['collection'];
            //search in db the collection corresponding to id
            $cm = new CollectionManager;
            $collection = $cm->findbyId($collectionId);

            if ($collection) {
                if ($collection->getPrivate()) {
                    //If collection is private, show error
                    $this->redirect("index.php?route=error&error=This collection is private");
                } else {
                    //else display collection share
                    //get gifs from collection
                    $gm = new GifManager;
                    $gifs = $gm->findByCollection($collectionId);
                    $this->render("collection-public.html.twig", ["collection" => $collection, "gifs" => $gifs]);
                }
            } else {
                $this->redirect("index.php?route=error&error=Collection with this id was not found");
            }
        } else {
            //If get is empty, redirect to search page
            $this->redirect("index.php?route=search");
        }
    }
    public function gif(): void
    {
        if (isset($_GET['gif'])) {
            $gifId = $_GET['gif'];
            //search in db the gif corresponding to id
            $gm = new GifManager;
            $gif = $gm->findbyId($gifId);
            if (is_null($gif)) {
                //If no gif has been found, display nothing was found
                $this->redirect("index.php?route=error&error=No GIF found");
            }
            $hashtags = $gm->findHashtags($gifId);
            $this->render("gif.html.twig", ["gif" => $gif, "hashtags" => $hashtags]);
        } else {
            //If input is empty, redirect to search page
            $this->redirect("index.php?route=search");
        }
    }
    public function terms(): void
    {
        $this->render("terms.html.twig", []);
    }
}
