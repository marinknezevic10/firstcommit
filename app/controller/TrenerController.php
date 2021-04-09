<?php

class TrenerController extends AutorizacijaController
{
    
    private $viewDir='privatno'
                .DIRECTORY_SEPARATOR
                .'trener'
                .DIRECTORY_SEPARATOR;

    
    private $trener=null;
    private $poruka='';

    public function index()
    {
        
        $this->view->render($this->viewDir. 'index',[
        
            'trener'=>Trener::ucitajSve()
        ]);
    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->noviTrener();
            return;
        }
        
        $this->trener = (object) $_POST;
        
        if(!$this->kontrolaIme()){return;}
        if(!$this->kontrolaPrezime()){return;}
        if(!$this->kontrolaPrethodniKlub()){return;}
        if(!$this->kontrolaNacionalnost()){return;}
        
        
        Trener::dodajNovi($this->trener);

        
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
           $this->trener= Trener::ucitaj($_GET['sifra']);//u klasi smjer trebamo ucitati podatke o smjeru
           $this->poruka='Promijenite željene podatke';
           $this->promjenaView();//zatim pozovi promjenaView
           return;
        }

        $this->trener = (object) $_POST;
        if(!$this->kontrolaIme()){return;}
        if(!$this->kontrolaPrezime()){return;}
        if(!$this->kontrolaPrethodniKlub()){return;}
        if(!$this->kontrolaNacionalnost()){return;}
        Trener::promjeniPostojeci($this->trener);
        $this->index();

    }

    public function brisanje()
    {
        if(!isset($_GET['sifra'])){//ako nemaš šifre ide odjava
                $ic= new IndexController();
                $ic->logout();
                return;
            }
            Trener::obrisiPostojeci(($_GET['sifra']));
            header('location: ' . APP::config('url') .'trener/index');
    }

    private function noviTrener()
    {
        $this->trener = new stdClass();
            $this->trener->ime='';
            $this->trener->prezime='';
            $this->trener->prethodniklub='';
            $this->trener->nacionalnost='';
            $this->poruka='Unesite tražene podatke';
            $this->novoView();
    }

    private function novoView()
    {
        $this->view->render($this->viewDir. 'novo',[
            'trener'=>$this->trener,
            'poruka'=>$this->poruka
        
            ]);
    }

    private function promjenaView()//ne prima paramatre, salje parametre sa razine klase
    {
        $this->view->render($this->viewDir. 'promjena',[
            'trener'=>$this->trener,
            'poruka'=>$this->poruka
        
            ]);
    }

    private function kontrolaIme()
    {
        
        if(strlen(trim($this->trener->ime))===0){
            $this->poruka='Unesite ime'; 
            $this->novoView();
                 return;
         }
          
         if(strlen(trim($this->trener->ime))>59){
            $this->poruka='Ime ne može imati više od 59 znakova';  
            $this->novoView();
                 return false;
         }
         return true;
    }

    private function kontrolaPrezime()
    {
        
        if(strlen(trim($this->trener->prezime))===0){
            $this->poruka='Unesite prezime'; 
            $this->novoView();
                 return;
         }
           
         if(strlen(trim($this->trener->prezime))>59){
            $this->poruka='Prezime ne može imati više od 59 znakova';  
            $this->novoView();
                 return false;
         }
         return true;
         
    }

    private function kontrolaPrethodniklub()
    {
        
        if(strlen(trim($this->trener->prethodniklub))===0){
            $this->poruka='Unesite prethodni klub'; 
            $this->novoView();
                 return;
         }
 
         if(strlen(trim($this->trener->prethodniklub))>50){
            $this->poruka='Prethodni klub ne može imati više od 50 znakova';  
            $this->novoView();
                 return false;
         }
         return true;
    }

    private function kontrolaNacionalnost()
    {
        
        if(strlen(trim($this->trener->nacionalnost))===0){
            $this->poruka='Unesite nacionalnost'; 
            $this->novoView();
                 return;
         }

         if(is_numeric($this->trener->nacionalnost)
        || ((int)$this->trener->nacionalnost)<=>0){
            $this->poruka='Nacionalnost nema brojeva!';
        $this->novoView();
        return false;
    }
    return true;  
 
         if(strlen(trim($this->trener->nacionalnost))>50){
            $this->poruka='Nacionalnost ne može imati više od 50 znakova';  
            $this->novoView();
                 return false;
         }
         return true;
    }
}