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
                        if (isset($_POST["formName"])) {
                            // check CSRF token
                            $tokenManager = new CSRFTokenManager();
                            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                                //upload GIF
                                $uploader = new Uploader();
                                $gif = $uploader->upload($_FILES, "image");
                                //add new GIF to DB
                                $gm = new GifManager();
                                $gm->createGIF($gif);
                                //add new GIF to collection
                                $gm->addGifToCollection($gif->getId(), $id);
                                if (isset($_POST["hashtags"]) && !is_null($_POST["hashtags"])) {
                                    $hm = new HashtagManager();
                                    //if hashtags have been added, enter them in db
                                    $hashtagsNames = explode(" ", $_POST['hashtags']);
                                    foreach ($hashtagsNames as $hashtagName) {
                                        //for each hashtag added, check if it exists in DB
                                        $hashtag = $hm->findByName($hashtagName);
                                        //if hashtag is found then add link gif-hahtags in link table
                                        if ($hashtag) {
                                            $gm->addHashtag($gif->getId(), $hashtag->getId());
                                        } else {
                                            //else create hashtag and then add link in gifs_hashtags table
                                            $hashtag = new Hashtag($hashtagName);
                                            $hm->createHashtag($hashtag, $gif->getId());
                                        }
                                    }
                                }
                                $this->render("collection-upload.html.twig", ["gif" => $gif, "collection" => $collection, "gifs" => $gifs]);
                            } else {
                                $this->redirect("index.php?route=error&error=Invalid CSRF token");
                            }
                        } else {
                            $this->render("collection-upload.html.twig", ["collection" => $collection, "gifs" => $gifs]);
                        }
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
        //check if connected
        if (isset($_SESSION['email'])) {
            $userId = $_SESSION['id'];
            //get collection id and gif id from params
            if (isset($_GET['gif']) && isset($_GET['collection'])) {
                $gifId = $_GET['gif'];
                $collectionId = $_GET['collection'];
                //check if collection is from connected user
                $cm = new CollectionManager();
                $collection = $cm->findById($collectionId);
                if ($collection->getAuthor()->getId() == $userId) {
                    $gm = new GifManager();
                    $gm->removeGifFromCollection($gifId, $collectionId);
                    $this->redirect("index.php?route=collection&collection=$collectionId&action=manage");
                } else {
                    $this->redirect("index.php?route=error&error=Collection is not from connected user");
                }
            } else {
                $this->redirect("index.php?route=error&error=Coudn't delete GIF from collection");
            }
        } else {
            $this->redirect("index.php?route=error&error=Please sign in first");
        }
    }
    public function addGifToCollection(): void
    {
        //check if connected
        if (isset($_SESSION['email'])) {
            $userId = $_SESSION['id'];
            //get collection id and gif id from params
            if (isset($_GET['gif']) && isset($_GET['collection'])) {
                $gifId = $_GET['gif'];
                $collectionId = $_GET['collection'];
                //check if collection is from connected user
                $cm = new CollectionManager();
                $collection = $cm->findById($collectionId);
                if ($collection->getAuthor()->getId() == $userId) {
                    $gm = new GifManager();
                    $gm->addGifToCollection($gifId, $collectionId);
                    $this->redirect("index.php?route=collection&collection=$collectionId&action=add");
                } else {
                    $this->redirect("index.php?route=error&error=Collection is not from connected user");
                }
            } else {
                $this->redirect("index.php?route=error&error=Coudn't delete GIF from collection");
            }
        } else {
            $this->redirect("index.php?route=error&error=Please sign in first");
        }
    }
    public function toggleCollectionPrivacy(): void
    {
        //check if connected
        if (isset($_SESSION['email'])) {
            $userId = $_SESSION['id'];
            //get collection id from params
            if (isset($_GET['collection'])) {
                $id = $_GET['collection'];
                //check if collection is from connected user
                $cm = new CollectionManager();
                $collection = $cm->findById($id);
                if ($collection->getAuthor()->getId() == $userId) {
                    $cm->toggleCollectionPrivacy($id);
                    $this->redirect("index.php?route=collection&collection=$id");
                } else {
                    $this->redirect("index.php?route=error&error=Collection is not from connected user");
                }
            } else {
                $this->redirect("index.php?route=error&error=Coudn't toggle collection privacy");
            }
        } else {

            $this->redirect("index.php?route=error&error=Please sign in first");
        }
    }
    public function upload(): void
    {
        if (isset($_POST["formName"])) {
            // check CSRF token
            $tokenManager = new CSRFTokenManager();
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                //upload GIF
                $uploader = new Uploader();
                $gif = $uploader->upload($_FILES, "image");
                //add new GIF to DB
                $gm = new GifManager();
                $gm->createGIF($gif);
                //add new GIF to uploads collection
                if (isset($_POST["hashtags"]) && !is_null($_POST["hashtags"])) {
                    $hm = new HashtagManager();
                    //if hashtags have been added, enter them in db
                    $hashtagsNames = explode(" ", $_POST['hashtags']);
                    foreach ($hashtagsNames as $hashtagName) {
                        //for each hashtag added, check if it exists in DB
                        $hashtag = $hm->findByName($hashtagName);
                        //if hashtag is found then add link gif-hahtags in link table
                        if ($hashtag) {
                            $gm->addHashtag($gif->getId(), $hashtag->getId());
                        } else {
                            //else create hashtag and then add link in gifs_hashtags table
                            $hashtag = new Hashtag($hashtagName);
                            $hm->createHashtag($hashtag, $gif->getId());
                        }
                    }
                }

                $this->render("upload.html.twig", ["gif" => $gif]);
            } else {
                $this->redirect("index.php?route=error&error=Invalid CSRF token");
            }
        } else {
            $this->render("upload.html.twig", []);
        }
    }
    public function createCollection(): void
    {
        //check if connected
        if (isset($_SESSION['email'])) {
            $userId = $_SESSION['id'];
            //get form info from post data
            if (isset($_POST['name']) && isset($_POST['private'])) {
                $name = $_POST['name'];
                $private = $_POST['private'];
                $cm = new CollectionManager();
                //check if collection with this name allready exits
                if ($cm->findByName($name)) {
                    $this->redirect("index.php?route=error&error=You allready have a collection with this name");
                } else {
                    //create new collection in db
                    $cm->createCollection($userId, $name, $private);
                    $this->redirect("index.php?route=my-collections");
                }
            } else {
                $this->redirect("index.php?route=error&error=Coudn't retrieve from data");
            }
        } else {

            $this->redirect("index.php?route=error&error=Please sign in first");
        }
    }
}
