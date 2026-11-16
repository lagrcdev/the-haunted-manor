<?php

class Reponse {
    public static function json($donnees, int $code = 200): void {
        http_response_code($code);
        echo json_encode($donnees);
        exit;
    }
}
