<?php
    namespace App\Controllers\Pages;

    use \App\Utils\View;
    use \App\Utils\Component;
    use \App\Helpers\Slugify;
    use \App\Services\Games as GamesService;

    class Search extends Page {
        public static function getPage($req, $res) {
            $queryParams = $req->getQueryParams();

            if(!$queryParams["platform"] && !$queryParams["category"])
                return $req->getRouter()->redirect('/');

            $gamesService = new GamesService();

            if($queryParams["platform"]) $query["platform"] = $queryParams["platform"];
            if($queryParams["category"]) $query["category"] = $queryParams["category"];
            
            $page  = $queryParams["page"] ? $queryParams["page"] : 1;
            $limit = 20;

            $query["sort-by"] = "popularity";

            $games = $gamesService->getGames($query, $limit, $page);

            if($games['count'] == 0 || !$games)
                return $req->getRouter()->redirect('/');

            $gameCards = "";
            foreach($games['list'] as $game) {
                $game['slug'] = Slugify::create($game['title']);
                $gameCard = Component::render('game-card', $game);
                $gameCards .= $gameCard;
            }

            $nextPage = '#';
            if($games['page'] < $games['pages']) {
                $uri = $req->getUri();
                $nextPage = $uri . '?' . http_build_query(array_merge($queryParams, ['page' => $page + 1]));
            }

            $previousPage = '#';
            if($games['page'] > 1) {
                $uri = $req->getUri();
                $previousPage = $uri . '?' . http_build_query(array_merge($queryParams, ['page' => $page - 1]));
            }

            $content = View::render('pages/search', [
                'cards' => $gameCards,
                'value' => $query["platform"] ? $query["platform"] : $query["category"],
                'type' => $query["platform"] ? "Plataform" : "Category",
                'nextpage' => $nextPage,
                'previouspage' => $previousPage
            ]);

            
            
            $content = parent::getPage("Luna Games - Search", $content);
            
            return $res->send(200, $content);
        }
    }