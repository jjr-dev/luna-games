<?php
    namespace App\Controllers\Pages;

    use \App\Utils\Pagination;
    use \App\Utils\View;
    use \App\Utils\Component;
    use \App\Helpers\Slugify;
    use \App\Services\Games as GamesService;

    class Home extends Page {
        public static function getPage($req, $res) {
            $queryParams = $req->getQueryParams();
            
            $page  = $queryParams["page"] ? $queryParams["page"] : 1;
            $limit = 12;

            $gamesService = new GamesService();
            $games = $gamesService->getGames(["sort-by" => "popularity"]);
            
            $pagination = new Pagination($games, $page, $limit);
            $paginationRender = $pagination->render($req);
            $games = $pagination->get()['list'];

            $gameCards = "";
            foreach($games as $game) {
                $game['slug'] = Slugify::create($game['title']);
                $gameCard = Component::render('game-card', $game);
                $gameCards .= $gameCard;
            }
            
            $content = View::render('pages/home', [
                'cards' => $gameCards,
                'pagination' => $paginationRender
            ]);
            
            $content = parent::getPage("Luna Games", $content);
            
            return $res->send(200, $content);
        }
    }