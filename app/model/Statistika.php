<?php
class Statistika
{
    public static function ucitaj($sifra)
    {
        
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('

        select * from statistika where sifra=:sifra;
        
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

        select a.*, count(b.sifra) as ukupnoigraca
        from statistika a 
        left join igrac b on a.sifra=b.statistika
        group by a.nastupi,a.odigranominuta,a.golovi,a.asistencije,a.zutikartoni,a.crvenikartoni;
        
        ');
        //izvodi upit
        $izraz->execute();

        //vraća nazad sve rezultate
        return $izraz->fetchAll();
    }

    public static function dodajNovi($statistika)
    {
        //spajanje na bazu(nalazi se u indexcontrolleru)
        $veza = DB::getInstanca();

        //priprema upit
        $izraz=$veza->prepare('

        insert into statistika (nastupi,odigranominuta,golovi,asistencije,zutikartoni,crvenikartoni)
        values (:nastupi,:odigranominuta,:golovi,:asistencije,:zutikartoni,:crvenikartoni)
        ');
        
        //izvodi upit
        //execute prima array indeksni niz, pošto smo $smjer mijenjali u object,ovdje ga vraćamo u array
        $izraz->execute((array)$statistika);
    }

    public static function promjeniPostojeci($statistika)
    {
        $veza = DB::getInstanca();

        $izraz=$veza->prepare('

        update statistika set 
        nastupi=:nastupi,odigranominuta=:odigranominuta,golovi=:golovi,
        asistencije=:asistencije,zutikartoni=:zutikartoni,crvenikartoni=:crvenikartoni
        where sifra=:sifra
        ');
     
        $izraz->execute((array)$statistika);
    }

    public static function obrisiPostojeci($sifra)
    {
        
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('

        delete from statistika where sifra=:sifra
        
        ');
      
        $izraz->execute(['sifra'=>$sifra]);
    }
}