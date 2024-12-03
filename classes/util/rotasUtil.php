<?php

namespace util;

class rotasUtil {
    
    public static function getRotas() {

        $urls = self::getURLS();
        $request = [];
        $request['rota'] = strtoupper($urls[0]);
        $request['recurso'] = $urls[1] ?? null;
        if ($request['recurso'] === 'listarAdm') {
            $request['adm'] = $urls[2] ?? null;
        } else {
            $request['id'] = $urls[2] ?? null;
        }
        //$_SERVER var global com info do servidor
        $request['metodo'] = $_SERVER['REQUEST_METHOD'];

        //retorno do array
        return $request;
    }

    //pega info da URL
    public static function getURLS() {
        $uri = str_replace('/' . DIR_PROJETO, '', $_SERVER['REQUEST_URI']);
        return explode('/', trim($uri, '/'));
    }
}

