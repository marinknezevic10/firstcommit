<?php

class ZanimljivostiController extends AutorizacijaController
{
    
    private $viewDir='privatno'
                .DIRECTORY_SEPARATOR
                .'zanimljivosti'
                .DIRECTORY_SEPARATOR;

    
    private $zanimljivosti=null;
    private $poruka='';

    

    public function index()
    {
        
        $this->view->render($this->viewDir. 'index',[
        
            'zanimljivosti'=>Zanimljivosti::ucitajSve()
        ]);
        
    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->noviZanimljivosti();
            return;
        }
        
        $this->zanimljivosti = (object) $_POST;
        
        if(!$this->kontrolaOsnivanje()){return;}
        if(!$this->kontrolaNazivStadiona()){return;}
        if(!$this->kontrolaKapacitet()){return;}
        
        
        
        Zanimljivosti::dodajNovi($this->zanimljivosti);

        
        $this->index();
           
    }

    public function promjena()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){//ako si dosao putem get metode
            if(!isset($_GET['sifra'])){//ako sifra nije postavljenja
                $ic= new IndexController();
                $ic->logout();//automatska odjava
                return;
            }
            //ovdje smo sigurni da je sifra postavljena, te ucitamo za sifru zeljeni smjer
           $this->zanimljivosti=Zanimljivosti::ucitaj($_GET['sifra']);//u klasi smjer trebamo ucitati podatke o smjeru
           $this->poruka='Promijenite željene podatke';
           $this->promjenaView();//zatim pozovi promjenaView
           return;
        }

        $this->zanimljivosti = (object) $_POST;
        if(!$this->kontrolaOsnivanje()){return;}
        if(!$this->kontrolaNazivstadiona()){return;}
        if(!$this->kontrolaKapacitet()){return;}
        Zanimljivosti::promjeniPostojeci($this->zanimljivosti);
        $this->index();

    }

    public function brisanje()
    {
        if(!isset($_GET['sifra'])){//ako nemaš šifre ide odjava
                $ic= new IndexController();
                $ic->logout();
                return;
            }
            Zanimljivosti::obrisiPostojeci(($_GET['sifra']));
            header('location: ' . APP::config('url') .'zanimljivosti/index');
    }

    private function noviZanimljivosti()
    {
            $this->zanimljivosti = new stdClass();
            $this->zanimljivosti->osnivanje=date('Y-m-d\TH:i');
            $this->zanimljivosti->nazivstadiona='';
            $this->zanimljivosti->kapacitet='';
            $this->poruka='Unesite tražene podatke';
            $this->novoView();
    }

    private function novoView()
    {
        $this->view->render($this->viewDir. 'novo',[
            'zanimljivosti'=>$this->zanimljivosti,
            'poruka'=>$this->poruka
        
            ]);
    }

    private function promjenaView()//ne prima paramatre, salje parametre sa razine klase
    {
        $this->view->render($this->viewDir. 'promjena',[
            'zanimljivosti'=>$this->zanimljivosti,
            'poruka'=>$this->poruka
        
            ]);
    }

    private function kontrolaOsnivanje()
    {
        
        if(strlen(trim($this->zanimljivosti->osnivanje))===0){
            $this->poruka='Unesite datum osnivanja'; 
            $this->novoView();
                 return;
         }
         return true;
         
    }

    private function kontrolaNazivstadiona()
    {
        
        if(strlen(trim($this->zanimljivosti->nazivstadiona))===0){
            $this->poruka='Unesite naziv'; 
            $this->novoView();
                 return;
         }
          
         if(strlen(trim($this->zanimljivosti->nazivstadiona))>90){
            $this->poruka='Naziv ne može imati više od 90 znakova';  
            $this->novoView();
                 return false;
         }
         return true;
    }
 

    private function kontrolaKapacitet()
    {
        
        if(strlen(trim($this->zanimljivosti->kapacitet))===0){
            $this->poruka='Unesite kapacitet stadiona'; 
            $this->novoView();
                 return;
         }
         if(!is_numeric($this->zanimljivosti->kapacitet)
            || ((int)$this->zanimljivosti->kapacitet)<0){
                $this->poruka='Kapacitet mora biti cijeli pozitivni broj';
            $this->novoView();
            return false;
      }
         return true;
    }

}