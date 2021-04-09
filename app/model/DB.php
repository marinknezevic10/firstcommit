<?php

// singleton pattern https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php

Class DB extends PDO
{
    private static $instanca=null;

    private function __construct($baza)//prima podatke za bazu
    {
        $dsn='mysql:host=' . $baza['server'] . 
        ';dbname=' . $baza['baza'] . ';charset=utf8';

        //pravimo PDO(PHP Data Object)
        parent::__construct($dsn,$baza['korisnik'],$baza['lozinka']);

        //setAttribute je metoda od PDO-a
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);//ugradili smo da uvijek zelimo nazad fetch object
    }

    public static function getInstanca()//staticna metoda i zelimo pristupiti klasi u kojoj se nalazimo
    //pošto smo u staticnoj metodi moramo reci self, a ne this
    {
        if(self::$instanca==null){//ako je self::instanca null, tada ona postaje novi self,a novi self je magicna metoda construct
            //posto ta magicna metoda prima parametar, onda u novi self salje parametar
            self::$instanca =new self(App::config('baza'));
        }
        return self::$instanca;
    }
}