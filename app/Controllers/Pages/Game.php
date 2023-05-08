<?php
    namespace App\Controllers\Pages;

    use \App\Utils\View;
    use \App\Utils\Component;
    use \App\Helpers\Slugify;
    use \App\Services\Games as GamesService;

    class Game extends Page {
        public static function getPage($req, $res) {
            $pathParams = $req->getPathParams();
            
            $gamesService = new GamesService();

            $game = $gamesService->getGame($pathParams['id']);

            $minimumSystemRequirements = [];
            foreach($game["minimum_system_requirements"] as $item => $value) {
                $minimumSystemRequirements[$item] = $value;
            }

            if(!empty($minimumSystemRequirements)) {
                $gameRequirements = Component::render('game-requirements', $minimumSystemRequirements);
                $game['requirements'] = $gameRequirements;
            } else {
                $game['requirements'] = "";
            }

            $gameScreenshots = "";
            foreach($game['screenshots'] as $screenshot) {
                $gameScreenshot = Component::render('game-screenshot', $screenshot);
                $gameScreenshots .= $gameScreenshot;
            }

            $game['screenshots'] = $gameScreenshots;

            $similarGames = $gamesService->getGames([
                "category" => $game['genre']
            ], 5);

            $similarGamesCards = "";
            if(!isset($similarGames['status']) || $similarGames['status'] !== 0) {
                $count = 0;
                
                foreach($similarGames as $key => $similarGame) {
                    if($similarGame['id'] === $game['id'] || $count >= 4)
                        continue;
                        
                    $similarGame['slug'] = Slugify::create($similarGame['title']);
                    $similarGameCard = Component::render('game-card', $similarGame);
                    $similarGamesCards .= $similarGameCard;
    
                    $count ++;
                }
            } else {
                $similarGamesCards = Component::render('no-games-found');
            }

            $game['similar'] = $similarGamesCards;

            $content = View::render('pages/game', $game);
            
            $content = parent::getPage($game['title'] . " - Luna Games", $content);
            
            return $res->send(200, $content);
        }
    }