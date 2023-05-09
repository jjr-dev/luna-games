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

            $response = json_decode($response, true);

            if($response['status_message'])
                return false;

            return $response; 
        }

        public function getGames($query = []) {
            $url = $this->url . '/games' . (empty($query) ? '' : '?' . http_build_query($query));

            $games = $this->sendRequest($url);

            return $games;
        }

        public function getGame($id) {
            $url = $this->url . '/game?id=' . $id;
            $game = $this->sendRequest($url);

            return $game;
        }
    }