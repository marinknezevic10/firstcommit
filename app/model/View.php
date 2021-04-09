<?php

class View
{
    //ide private zato sto nitko nece naslijedit view
    private $predlozak;

    public function __construct($predlozak='predlozak')
    {
        $this->predlozak=$predlozak;
    }

    //parametar stranica za render je obavezna, a parametri su opcionalni
    public function render($stranicaZaRender,$parametri=[])
    {
        //print_r($parametri);
        ob_start();//output buffer
        extract($parametri);//pravimo varijable(kljucevi asocijativnog niza postaju varijable)
        //vrijednosti kljuceva asocijativnog niza postaju vrijednosti novonastalih varijabli
        // koje ce biti dostupne u view pomocu extract funkcije
        
        //ucitaj stranicu za render
        include BP_APP . 'view' . DIRECTORY_SEPARATOR . 
        $stranicaZaRender . '.phtml';
        //u varijablu sadrzaj ucitaj sve sto je bufferirano
        $sadrzaj = ob_get_clean();
        $podnozjePodaci=$this->podnozjePodaci();
        include BP_APP . 'view' . DIRECTORY_SEPARATOR . 
        $this->predlozak . '.phtml';//predlozak je ono sto je zajednicko za sve dijelove aplikacije

    }

    private function podnozjePodaci()
    {
        if($_SERVER['SERVER_ADDR']==='127.0.0.1'){
            return '2020-' . date('Y') . ' - LOKALNO';
        }
        return '2020-' . date('Y');
    }
}

//extract metodu koristimo kada ce se dijelovi koda pojavljivati na vise mjesta
//ili ako cemo postojecu metodu lakse razumijeti ako ju podijelimo na dijelove