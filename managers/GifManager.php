<?php

class GifManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
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
            $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']));
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
            $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']));
            $gif->setId($item['gif_id']);
            array_push($gifs, $gif);
        };
        return $gifs;
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
        $gif = new Gif($item['link'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $item['created_at']));
        $gif->setId($item['gif_id']);
        return $gif;
    }
}
