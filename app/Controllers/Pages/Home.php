<?php
    namespace App\Controllers\Pages;

    use \App\Utils\View;
    use \App\Utils\Component;
    use \App\Helpers\Slugify;
    use \App\Services\Games as GamesService;

    class Home extends Page {
        public static function getPage($req, $res) {
            $gamesService = new GamesService();

            $games = $gamesService->getGames([
                "sort-by" => "popularity"
            ]);

            if($games['count'] == 0 || !$games)
                return $req->getRouter()->redirect('/');

            $gameCards = "";
            foreach($games['list'] as $game) {
                $game['slug'] = Slugify::create($game['title']);
                $gameCard = Component::render('game-card', $game);
                $gameCards .= $gameCard;
            }
            
            $content = View::render('pages/home', [
                'cards' => $gameCards,
            ]);
            
            $content = parent::getPage("Luna Games", $content);
            
            return $res->send(200, $content);
        }
    }