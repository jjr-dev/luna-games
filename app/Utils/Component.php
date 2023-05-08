<?php
    namespace App\Utils;

    use \App\Utils\View;

    class Component {
        private static $vars = [];
        
        private static function getContentComponent($view) {
            $file = __DIR__ . '/../../resources/components/' . $view . '.html';
            return file_exists($file) ? file_get_contents($file) : '';
        }

        public static function render($view, $vars = []) {
            if(empty(self::$vars))
                self::$vars = View::getInitVars();

            $contentView = self::getContentComponent($view);

            $vars = array_merge(self::$vars, $vars);

            $keys = array_keys($vars);
            $keys = array_map(function($item) {
                return '{{' . $item . '}}';
            }, $keys);

            return str_replace($keys, array_values($vars), $contentView);
        }
    }