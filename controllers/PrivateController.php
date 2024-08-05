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
            foreach ($hashtags as $index => $hashtag) {
                $gif = $gm->findLatestWithHashtag($hashtag->getId());
                //if no GIF is found for a latest hashtag, remove hashtag from list
                if ($gif) {
                    array_push($hashtagsLatestGifs, $gif);
                } else {
                    array_splice($hashtags, $index, 1);
                }
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
            $gm = new GifManager;
            $gifs = $gm->findRandom10();
            $this->render("home.html.twig", ["gifs" => $gifs]);
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
                                $gifId = $gm->createGIF($gif);
                                //add new GIF to collection
                                $gm->addGifToCollection($gifId, $id);
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
                        } else if (isset($_POST["gifUrl"])) {
                            // check CSRF token
                            $tokenManager = new CSRFTokenManager();
                            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                                //check if user is connected
                                if (isset($_SESSION['id'])) {
                                    $userId = $_SESSION['id'];
                                    //fetch user 
                                    $um = new UserManager;
                                    $user = $um->findById($userId);
                                    //create gif with url
                                    $gif = new Gif($_POST["gifUrl"], $user);
                                    //add new GIF to DB
                                    $gm = new GifManager();
                                    $gifId = $gm->createGIF($gif);
                                    //add new GIF to collection
                                    $gm->addGifToCollection($gifId, $id);
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
                                    $this->redirect("index.php?route=error&error=You need to be signed in to upload");
                                }
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
        } else {
            $this->redirect("index.php?route=error&error=Please sign in to access your collections");
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
                //find collection
                $cm = new CollectionManager();
                $collection = $cm->findById($collectionId);
                //check if collection is not "uploads"
                if ($collection->getName() == "uploads") {
                    $this->redirect("index.php?route=error&error=You can't add GIFs to uploads collection");
                } else {
                    //check if collection is from connected user
                    if ($collection->getAuthor()->getId() == $userId) {
                        $gm = new GifManager();
                        $gm->addGifToCollection($gifId, $collectionId);
                        $this->redirect("index.php?route=collection&collection=$collectionId&action=add");
                    } else {
                        $this->redirect("index.php?route=error&error=Collection is not from connected user");
                    }
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
                //get collection
                $cm = new CollectionManager();
                $collection = $cm->findById($id);
                //check if collection is not uploads or favorites
                if ($collection->getName() == "uploads" || $collection->getName() == "favorites") {
                    $this->redirect("index.php?route=error&error=You can't publish favorites or uploads collections");
                } else {
                    //check if collection is from connected user
                    if ($collection->getAuthor()->getId() == $userId) {
                        $cm->toggleCollectionPrivacy($id);
                        $this->redirect("index.php?route=collection&collection=$id");
                    } else {
                        $this->redirect("index.php?route=error&error=Collection is not from connected user");
                    }
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

                if (isset($_SESSION['id'])) {

                    //upload GIF
                    $uploader = new Uploader();
                    $gif = $uploader->upload($_FILES, "image");
                    //add new GIF to DB
                    $gm = new GifManager();
                    $gm->createGIF($gif);
                    //add new GIF to uploads collection
                    if (isset($_POST["hashtags"]) && $_POST["hashtags"] != "") {
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
                } else {
                    $this->redirect("index.php?route=error&error=Please sign in to upload GIFs");
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
                if ($cm->findByNameFromUser($name, $userId)) {
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
    public function deleteCollection(): void
    {
        //check if connected
        if (isset($_SESSION['email'])) {
            $userId = $_SESSION['id'];
            //get collection Id from get param
            if (isset($_GET['collection'])) {
                $collectionId = $_GET['collection'];
                $cm = new CollectionManager();
                //check if collection is from connected user
                if ($cm->isCollectionFromUser($collectionId, $userId)) {

                    //delete collection
                    $cm->deleteCollection($collectionId);
                    $this->redirect("index.php?route=my-collections");
                } else {
                    $this->redirect("index.php?route=error&error=This collection is not yours, you cant delete it");
                }
            } else {
                $this->redirect("index.php?route=error&error=Coudn't retrieve collection id");
            }
        } else {

            $this->redirect("index.php?route=error&error=Please sign in first");
        }
    }

    public function uploadWithUrl(): void
    {
        if (isset($_POST["gifUrl"])) {
            // check CSRF token
            $tokenManager = new CSRFTokenManager();
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                //check if user is connected
                if (isset($_SESSION['id'])) {
                    $userId = $_SESSION['id'];
                    //fetch user 
                    $um = new UserManager;
                    $user = $um->findById($userId);
                    //create gif with url
                    $gif = new Gif($_POST["gifUrl"], $user);

                    //create new GIF in DB and add to uploads collection
                    $gm = new GifManager();
                    $gm->createGIF($gif);
                    //if hashtags have been added, enter them in db
                    if (isset($_POST["hashtags"]) && $_POST["hashtags"] != "") {
                        $hm = new HashtagManager();
                        //take each hashtag separated by spaces
                        $hashtagsNames = explode(" ", $_POST['hashtags']);
                        foreach ($hashtagsNames as $hashtagName) {

                            //for each hashtag added, check if it exists in DB
                            $hashtag = $hm->findByExactName($hashtagName);
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
                    $this->redirect("index.php?route=error&error=You need to be signed in to upload");
                }
            } else {
                $this->redirect("index.php?route=error&error=Invalid CSRF token");
            }
        } else {
            $this->redirect("index.php?route=error&error=No URL found");
        }
    }
}
