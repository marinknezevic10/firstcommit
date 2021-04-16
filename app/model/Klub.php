<?php
class Klub
{
    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from klub where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);

        $klub= $izraz->fetch();

        $izraz=$veza->prepare('
        
        select * from igrac
        where klub =:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        $klub->igraci = $izraz->fetchAll();
        

        return $klub;
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

        select a.naziv as klub,a.brojigracauklubu as brojigracauklubu,a.prosjekgodina, a.naziv,
       concat(c.nazivstadiona,\' \',c.kapacitet) as zanimljivosti,
            concat(b.ime, \' \', b.prezime) as trener,
            a.sifra, count(b.sifra) as trenera
            from klub a inner join trener b
            on a.trener=b.sifra 
            inner join zanimljivosti c 
            on a.zanimljivosti=c.sifra
            group by a.naziv;
        
        ');
        //izvodi upit
        $izraz->execute();

        //vraća nazad sve rezultate
        return $izraz->fetchAll();
    }

    public static function dodajNovi($klub)
    {
        //spajanje na bazu(nalazi se u indexcontrolleru)
        $veza = DB::getInstanca();

        //priprema upit
        $izraz=$veza->prepare('

        insert into klub (naziv,brojigracauklubu,zanimljivosti,trener)
        values (:naziv,:brojigracauklubu,:zanimljivosti,:trener)
        ');
        
        //print_r($klub);
        //stdClass Object ( [naziv] => dsad [brojigracauklubu] => [zanimljivosti] => 3 [trener] => 1 )
        //izvodi upit
        //execute prima array indeksni niz, pošto smo $smjer mijenjali u object,ovdje ga vraćamo u array
        $izraz->execute((array)$klub);
       
    }

    public static function promjeniPostojeci($klub)
    {
        $veza = DB::getInstanca();

        $izraz=$veza->prepare('

        update klub set 
        naziv=:naziv,brojigracauklubu=:brojigracauklubu,
        zanimljivosti=:zanimljivosti,trener=:trener
        where sifra=:sifra
        ');
     
        $izraz->execute((array)$klub);
    }

    public static function obrisiPostojeci($sifra)
    {
        
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('

        delete from klub where sifra=:sifra
        
        ');
      
        $izraz->execute(['sifra'=>$sifra]);
    }




}
