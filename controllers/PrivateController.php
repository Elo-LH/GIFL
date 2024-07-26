<?php

class PrivateController extends AbstractController
{
    public function welcome(): void
    {
        if (isset($_SESSION['email'])) {
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
            //get all collections from user
            $cm = new CollectionManager;
            $collections = $cm->findByUserId($_SESSION['id']);
            //get first gif from each collection
            $gifs = [];
            foreach ($collections as $collection) {
                $gif = $gm->findLatestInCollection($collection->getId());
                array_push($gifs, $gif);
            }
            $this->render("welcome.html.twig", ["hashtagsLatestGifs" => $hashtagsLatestGifs, "hashtags" => $hashtags, "collections" => $collections, "gifs" => $gifs]);
        } else {
            $this->render("home.html.twig", []);
        }
    }
    public function myCollections(): void
    {
        if (isset($_SESSION['email'])) {
            //get collections of user
            $cm = new CollectionManager;
            $collections = $cm->findByUserId($_SESSION['id']);
            //get all gifs of each collection
            $gm = new GifManager;
            $collectionGifs = [];
            foreach ($collections as $collection) {
                $gifs = $gm->findByCollection($collection->getId());
                array_push($collectionGifs, $gifs);
            }
            $this->render("my-collections.html.twig", ["collections" => $collections, "collectionGifs" => $collectionGifs]);
        } else {
            $this->render("home.html.twig", []);
        }
    }
    public function collection(): void
    {
        //check if connected
        if (isset($_SESSION['email'])) {
            //get collection id from params
            if (isset($_GET['collection'])) {
                //get collection infos
                $id = $_GET['collection'];
                $cm = new CollectionManager;
                $collection = $cm->findById($id);
                //get gifs from collection
                $gm = new GifManager;
                $gifs = $gm->findByCollection($id);
                //get action from params
                if (isset($_GET['action'])) {
                    $action = $_GET['action'];
                    if ($action == "manage") {
                        $this->render("collection-manage.html.twig", ["collection" => $collection, "gifs" => $gifs]);
                    } else if ($action == "upload") {
                        $this->render("collection-upload.html.twig", ["collection" => $collection, "gifs" => $gifs]);
                    } else if ($action == "add") {
                        $this->render("collection-add.html.twig", ["collection" => $collection, "gifs" => $gifs]);
                        //else default load = share collection
                    } else {
                        $this->render("collection-share.html.twig", ["collection" => $collection, "gifs" => $gifs]);
                    }
                } else {
                    $this->render("collection-share.html.twig", ["collection" => $collection, "gifs" => $gifs]);
                }
            }
        }
    }
    public function removeGifFromCollection(): void
    {
        if (isset($_GET['gif']) && isset($_GET['collection'])) {
            $gifId = $_GET['gif'];
            $collectionId = $_GET['collection'];
            //get user id from params
            if (isset($_GET['gif'])) {
                $id = $_GET['gif'];
                //init manager
                $gm = new GifManager();
                $gm->removeGifFromCollection($gifId, $collectionId);
                $this->redirect("index.php?route=collection&collection=$collectionId&action=manage");
            }
        } else {
            $this->redirect("index.php?route=error&error=Coudn't delete GIF from collection");
        }
    }
    public function toggleCollectionPrivacy(): void
    {
        if (isset($_GET['collection'])) {
            $id = $_GET['collection'];
            //get user id from params

            $cm = new CollectionManager();
            $cm->toggleCollectionPrivacy($id);
            $this->redirect("index.php?route=collection&collection=$id");
        } else {
            $this->redirect("index.php?route=error&error=Coudn't toggle collection privacy");
        }
    }
}
