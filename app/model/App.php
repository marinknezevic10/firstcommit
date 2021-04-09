<?php

class App
{
    public static function start()
    {
        //prvo zelim shvatit sto je korisnik napisao, pa si dohvatim rutu
        $ruta = Request::getRuta();
       //echo $ruta;
       //tu rutu podijelim na dijelove
       $djelovi=explode('/',$ruta);
       //print_r($djelovi);
       $klasa='';
       //ako nisi postavio prvi dio rute ili je prvi dio prazan ponda ce klasa biti index(indexcontroller)
       //kada covjek napise ime pune domene bez ijednog kontrolera ili funkcije
       if(!isset($djelovi[1]) || $djelovi[1]==''){
           $klasa='Index';
       }else{
           $klasa=ucfirst($djelovi[1]);
       }
       $klasa.='Controller';

       //echo $klasa;
       
       $funkcija='';
       //ako nisi postavio drugi dio rute onda ides na index funkciju unutar indexcontrollera
       if(!isset($djelovi[2]) || $djelovi[2]==''){
           $funkcija='index';
       }else{
           $funkcija=$djelovi[2];
       }

       //echo 'Izvodim ' . $klasa . '->' . $funkcija;
       //shvatili smo sta covjek zeli postoji ta klasa i funkcija, tada pravis instancu klase i
       //pozoves funkciju na instanci
       if(class_exists($klasa) && method_exists($klasa,$funkcija)){
           $instanca=new $klasa();
           $instanca->$funkcija();
       }else{
           echo 'Čak niti HGSS ne može naći ' . $klasa . '->' . $funkcija;
       }
    }

    public static function config($kljuc)//ucitava config
    {
        $config=include BP_APP . 'config.php';//dobiti ce vrijednosti koji su zapisane u datoteci config.php
        return $config[$kljuc];
    }
}