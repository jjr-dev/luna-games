<?php
    namespace App\Controllers\Pages;

    use \App\Utils\Pagination;
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
            $query["sort-by"] = "popularity";

            $games = $gamesService->getGames($query);

            $page  = $queryParams["page"] ? $queryParams["page"] : 1;
            $limit = 20;

            $pagination = new Pagination($games, $page, $limit);
            $paginationRender = $pagination->render($req);
            $games = $pagination->get()['list'];

            if(!$games)
                return $req->getRouter()->redirect('/');

            $gameCards = "";
            foreach($games as $game) {
                $game['slug'] = Slugify::create($game['title']);
                $gameCard = Component::render('game-card', $game);
                $gameCards .= $gameCard;
            }

            $content = View::render('pages/search', [
                'cards'      => $gameCards,
                'value'      => $query["platform"] ? $query["platform"] : $query["category"],
                'type'       => $query["platform"] ? "Plataform" : "Category",
                'pagination' => $paginationRender
            ]);

            $content = parent::getPage("Luna Games - Search", $content);
            
            return $res->send(200, $content);
        }
    }