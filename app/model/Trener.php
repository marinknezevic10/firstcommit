<?php
class Trener
{
    public static function ucitaj($sifra)
    {
        
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('

        select * from trener where sifra=:sifra;
        
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
        from trener a 
        left join klub b on a.sifra=b.trener
        group by a.sifra,a.ime,a.prezime,
        a.prethodniklub ,a.nacionalnost ;
        
        ');
        //izvodi upit
        $izraz->execute();

        //vraća nazad sve rezultate
        return $izraz->fetchAll();
    }

    public static function dodajNovi($trener)
    {
        //spajanje na bazu(nalazi se u indexcontrolleru)
        $veza = DB::getInstanca();

        //priprema upit
        $izraz=$veza->prepare('

        insert into trener (ime,prezime,prethodniklub,nacionalnost)
        values (:ime,:prezime,:prethodniklub,:nacionalnost);
        ');
        
        //izvodi upit
        //execute prima array indeksni niz, pošto smo $smjer mijenjali u object,ovdje ga vraćamo u array
        $izraz->execute((array)$trener);
    }

    public static function promjeniPostojeci($trener)
    {
        $veza = DB::getInstanca();

        $izraz=$veza->prepare('

        update trener set 
        ime=:ime,prezime=:prezime,prethodniklub=:prethodniklub,nacionalnost=:nacionalnost
        where sifra=:sifra
        ');
     
        $izraz->execute((array)$trener);
    }

    public static function obrisiPostojeci($sifra)
    {
        
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('

        delete from trener where sifra=:sifra
        
        ');
      
        $izraz->execute(['sifra'=>$sifra]);
    }
}
