<?php

class Request
{
    public static function getRuta()
    {
        $ruta='/';
        if(isset($_SERVER['REDIRECT_PATH_INFO'])){
            $ruta=$_SERVER['REDIRECT_PATH_INFO'];
        }else if(isset($_SERVER['REQUESR_URI'])){
            $ruta=$_SERVER['REQUEST_URI'];
        }
        //dodati jos kljuceva koji sadrze rutu
        return $ruta;
    }
}