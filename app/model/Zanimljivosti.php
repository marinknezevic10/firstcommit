<?php
class Zanimljivosti
{
    public static function ucitaj($sifra)
    {
        
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('

        select * from zanimljivosti where sifra=:sifra;
        
        ');
      
        $izraz->execute(['sifra'=>$sifra]);

        return $izraz->fetch();
    }


    public static function ucitajSve()
    {
        //način učitavanja smjerova iz baze
        //te podatke ce funkcija ucitajSve vratiti onome tko traži, a tražio je SmjerController
        //SmjerController to šalje index viewu
        
        //spajanje na bazu(nalazi se u indexcontrolleru)
        $veza = DB::getInstanca();

        //priprema upit
        $izraz=$veza->prepare('

        select a.*, count(b.sifra) as ukupnoklubova 
        from zanimljivosti a 
        left join klub b on a.sifra=b.zanimljivosti
        group by a.osnivanje,a.nazivstadiona,a.kapacitet;
        
        ');
        //izvodi upit
        $izraz->execute();

        //vraća nazad sve rezultate
        return $izraz->fetchAll();
    }

    public static function dodajNovi($zanimljivosti)
    {
        //spajanje na bazu(nalazi se u indexcontrolleru)
        $veza = DB::getInstanca();

        //priprema upit
        $izraz=$veza->prepare('

        insert into zanimljivosti (osnivanje,nazivstadiona,kapacitet)
        values (:osnivanje,:nazivstadiona,:kapacitet)
        ');
        
        //izvodi upit
        //execute prima array indeksni niz, pošto smo $smjer mijenjali u object,ovdje ga vraćamo u array
        $izraz->execute((array)$zanimljivosti);
    }

    public static function promjeniPostojeci($zanimljivosti)
    {
        $veza = DB::getInstanca();

        $izraz=$veza->prepare('

        update zanimljivosti set 
        osnivanje=:osnivanje,nazivstadiona=:nazivstadiona,kapacitet=:kapacitet
        where sifra=:sifra
        ');
     
        $izraz->execute((array)$zanimljivosti);
    }

    public static function obrisiPostojeci($sifra)
    {
        
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('

        delete from zanimljivosti where sifra=:sifra
        
        ');
      
        $izraz->execute(['sifra'=>$sifra]);
    }
}