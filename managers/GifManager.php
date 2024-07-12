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
}
