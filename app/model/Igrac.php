<?php

class Igrac
{

    public static function traziigrace()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.ime, a.prezime,
        from igrac a
        inner join klub b on a.klub=b.sifra
        where concat(a.ime,\'\',a.prezime) like :uvjet
        group by a.sifra,a.ime,a.prezime,b.naziv limit 10
        ');
       
        $izraz->execute([
            'uvjet'=>'%' . $_GET['uvjet'] . '%',
            
        ]);
        return $izraz->fetchAll();


    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from igrac where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);

        $igrac= $izraz->fetch();

        $izraz=$veza->prepare('
        
        select a.sifra,a.ime,a.prezime,b.naziv 
       from igrac a inner join klub b on a.klub=b.sifra
       where a.klub=:sifra;
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        

        return $igrac;
    }

    public static function ucitajSve($stranica,$uvjet)
    {
        $rps=App::config('rezultataPoStranici'); 
        $od = $stranica * $rps - $rps;

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.ime, a.prezime,a.mjestorodenja as mjestorodenja,
        b.naziv as klub,c.nastupi as nastupi,
        c.golovi as golovi,c.asistencije as asistencije,
        concat(c.nastupi,\'\',c.golovi,\'\',c.asistencije) as statistika
        from igrac a inner join klub b
        on a.klub =b.sifra 
        inner join statistika c on
        a.statistika =c.sifra
        where concat(a.ime,\'\',a.prezime) like :uvjet
        group by a.sifra,a.ime,a.prezime,b.naziv limit :od,:rps;
        
        ');

        $izraz->bindParam('uvjet',$uvjet);
        $izraz->bindValue('od',$od, PDO::PARAM_INT);
        $izraz->bindValue('rps',$rps, PDO::PARAM_INT);
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ukupnoIgraca($uvjet)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select count(a.sifra) from igrac a 
        inner join klub b on a.klub =b.sifra 
        where concat(a.ime,\'\',a.prezime) like :uvjet
        ');
       
        $izraz->bindParam('uvjet',$uvjet);
        $izraz->execute();
        return $izraz->fetchColumn();


    }



    public static function dodajNovi($igrac)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        insert into igrac (ime,prezime,mjestorodenja,klub,statistika)
        values (:ime,:prezime,:mjestorodenja,:klub,:statistika)

        ');
        $izraz->execute((array)$igrac);
        
    }

    public static function promjeniPostojeci($igrac)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
           update igrac set 
           ime=:ime,prezime=:prezime,mjestorodenja=:mjestorodenja,
           klub=:klub,statistika=:statistika 
           where sifra=:sifra
        
        ');
        $izraz->execute((array)$igrac);
        
    }

    public static function obrisiPostojeci($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            delete from igrac where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
    }


}