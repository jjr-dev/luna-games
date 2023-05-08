<?php

    namespace App\Services;

    class Games {
        private $url;

        function __construct() {
            $this->url = 'https://www.freetogame.com/api';
        }

        private function sendRequest($url) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true
            ]);

            $response = curl_exec($curl);

            curl_close($curl);

            return json_decode($response, true);
        }

        public function getGames($query = [], $limit = 12, $page = 1) {
            $url = $this->url . '/games' . (empty($query) ? '' : '?' . http_build_query($query));

            $games = $this->sendRequest($url);

            return array_splice($games, ($page - 1) * $limit, $limit);
        }

        public function getGame($id) {
            $url = $this->url . '/game?id=' . $id;
            return $this->sendRequest($url);
        }
    }