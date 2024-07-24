<?php

class GifManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }
    public function findById($id): ?Gif
    {
        $um = new UserManager;
        $query = $this->db->prepare('SELECT * FROM gifs WHERE gif_id = :id ');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);
        $user = $um->findById($item['user_id']);
        $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']), $item['reported']);
        $gif->setId($item['gif_id']);
        return $gif;
    }
    public function findLatest10(): array
    {
        $um = new UserManager;
        $query = $this->db->prepare('SELECT * FROM gifs LIMIT 10');
        $parameters = [];
        $query->execute($parameters);
        $fetchedResults = $query->fetchAll(PDO::FETCH_ASSOC);
        $gifs = [];
        //enter fetched users from DB into instances array
        foreach ($fetchedResults as $item) {
            $user = $um->findById($item['user_id']);
            $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']), $item['reported']);
            $gif->setId($item['gif_id']);
            array_push($gifs, $gif);
        };
        return $gifs;
    }
    public function findRandom10(): array
    {
        $um = new UserManager;
        $query = $this->db->prepare('SELECT * FROM gifs ORDER BY RAND() LIMIT 10');
        $parameters = [];
        $query->execute($parameters);
        $fetchedResults = $query->fetchAll(PDO::FETCH_ASSOC);
        $gifs = [];
        //enter fetched users from DB into instances array
        foreach ($fetchedResults as $item) {
            $user = $um->findById($item['user_id']);
            $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']), $item['reported']);
            $gif->setId($item['gif_id']);
            array_push($gifs, $gif);
        };
        return $gifs;
    }
    public function getRandom1(): Gif
    {
        $um = new UserManager;
        $query = $this->db->prepare('SELECT * FROM gifs ORDER BY RAND() LIMIT 1');
        $parameters = [];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);
        $user = $um->findById($item['user_id']);
        $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']), $item['reported']);
        $gif->setId($item['gif_id']);
        return $gif;
    }
    public function findLatestWithHashtag($hashtag_id): Gif
    {
        $um = new UserManager;
        $query = $this->db->prepare('SELECT * FROM gifs_hashtags JOIN gifs ON gifs_hashtags.gif_id = gifs.gif_id JOIN hashtags ON gifs_hashtags.hashtag_id = hashtags.hashtag_id WHERE hashtags.hashtag_id = :id ORDER BY gifs.created_at DESC LIMIT 1');
        $parameters = [
            "id" => $hashtag_id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);
        $user = $um->findById($item['user_id']);
        $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']), $item['reported']);
        $gif->setId($item['gif_id']);
        return $gif;
    }
    public function findByHashtag($hashtag_id): ?array
    {
        $um = new UserManager;
        $query = $this->db->prepare('SELECT * FROM gifs_hashtags JOIN gifs ON gifs_hashtags.gif_id = gifs.gif_id JOIN hashtags ON gifs_hashtags.hashtag_id = hashtags.hashtag_id WHERE hashtags.hashtag_id = :id ORDER BY gifs.created_at DESC');
        $parameters = [
            "id" => $hashtag_id
        ];
        $query->execute($parameters);
        $fetchedResults = $query->fetchAll(PDO::FETCH_ASSOC);
        $gifs = [];
        //enter fetched gifs from DB into instances array
        foreach ($fetchedResults as $item) {
            $user = $um->findById($item['user_id']);
            $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']), $item['reported']);
            $gif->setId($item['gif_id']);
            array_push($gifs, $gif);
        };
        return $gifs;
    }
    public function findByCollection($collection_id): ?array
    {
        $um = new UserManager;
        $query = $this->db->prepare('SELECT * FROM collections_gifs JOIN gifs ON collections_gifs.gif_id = gifs.gif_id JOIN collections ON collections_gifs.collection_id = collections.collection_id WHERE collections.collection_id = :id ORDER BY gifs.created_at DESC');
        $parameters = [
            "id" => $collection_id
        ];
        $query->execute($parameters);
        $fetchedResults = $query->fetchAll(PDO::FETCH_ASSOC);
        $gifs = [];
        //enter fetched gifs from DB into instances array
        foreach ($fetchedResults as $item) {
            $user = $um->findById($item['user_id']);
            $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']), $item['reported']);
            $gif->setId($item['gif_id']);
            array_push($gifs, $gif);
        };
        return $gifs;
    }
    public function findHashtags($gif_id): ?array
    {

        $query = $this->db->prepare('SELECT * FROM gifs_hashtags JOIN gifs ON gifs_hashtags.gif_id = gifs.gif_id JOIN hashtags ON gifs_hashtags.hashtag_id = hashtags.hashtag_id WHERE gifs.gif_id = :id');
        $parameters = [
            "id" => $gif_id
        ];
        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($results) {
            $hashtags = [];
            foreach ($results as $result) {
                $hashtag = new Hashtag($result["name"], DateTime::createFromFormat('Y-m-d H:i:s', $result["created_at"]));
                $hashtag->setId($result["hashtag_id"]);
                array_push($hashtags, $hashtag);
            }
            return $hashtags;
        }
        return null;
    }
    public function findReported(): ?array
    {
        $um = new UserManager;
        $query = $this->db->prepare('SELECT * FROM gifs WHERE reported = true ORDER BY gifs.created_at DESC');
        $parameters = [];
        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($results) {

            $gifs = [];
            //enter fetched gifs from DB into instances array
            foreach ($results as $item) {
                $user = $um->findById($item['user_id']);
                $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']), $item['reported']);
                $gif->setId($item['gif_id']);
                array_push($gifs, $gif);
            };
            return $gifs;
        }
        return null;
    }
    public function deleteGif(int $id): void
    {
        $query = $this->db->prepare('DELETE FROM gifs WHERE gif_id=:id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
    }
    public function toggleReported(int $id)
    {
        $parameters = [
            "id" => $id
        ];
        $query = $this->db->prepare("UPDATE gifs SET reported = !reported WHERE gif_id =:id");
        $query->execute($parameters);
    }
    public function findLatestInCollection($collection_id): Gif
    {
        $um = new UserManager;
        $query = $this->db->prepare('SELECT * FROM collections_gifs JOIN gifs ON collections_gifs.gif_id = gifs.gif_id JOIN collections ON collections_gifs.collection_id = collections.collection_id WHERE collections.collection_id = :id ORDER BY gifs.created_at DESC LIMIT 1');
        $parameters = [
            "id" => $collection_id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);
        $user = $um->findById($item['user_id']);
        $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']), $item['reported']);
        $gif->setId($item['gif_id']);
        return $gif;
    }
}
