<?php
    namespace App\Controllers\Pages;

    use \App\Utils\View;
    use \App\Utils\Component;
    use \App\Helpers\Slugify;
    use \App\Services\Games as GamesService;

    class Search extends Page {
        public static function getPage($req, $res) {
            $queryParams = $req->getQueryParams();
            
            $gamesService = new GamesService();

            if($queryParams["platform"]) $query["platform"] = $queryParams["platform"];
            if($queryParams["category"]) $query["category"] = $queryParams["category"];
            
            $page  = $queryParams["page"] ? $queryParams["page"] : 1;
            $limit = 20;

            $query["sort-by"] = "popularity";

            $games = $gamesService->getGames($query, $limit, $page);

            $gameCards = "";
            foreach($games as $game) {
                $game['slug'] = Slugify::create($game['title']);
                $gameCard = Component::render('game-card', $game);
                $gameCards .= $gameCard;
            }

            $nextPage = '#';
            if(count($games) >= $limit) {
                $uri = $req->getUri();
                $uri = explode("&page=", $uri)[0];
                $nextPage = $uri . '&page=' . ($page + 1);
            }
            
            $content = View::render('pages/search', [
                'cards' => $gameCards,
                'value' => $query["platform"] ? $query["platform"] : $query["category"],
                'type' => $query["platform"] ? "Plataform" : "Category",
                'nextpage' => $nextPage
            ]);

            
            
            $content = parent::getPage("Luna Games - Search", $content);
            
            return $res->send(200, $content);
        }
    }