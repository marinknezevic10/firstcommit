<?php

class StatistikaController extends AutorizacijaController
{
    
    private $viewDir='privatno'
                .DIRECTORY_SEPARATOR
                .'statistika'
                .DIRECTORY_SEPARATOR;

    
    private $statistika=null;
    private $poruka='';

    public function index()
    {
        
        $this->view->render($this->viewDir. 'index',[
        
            'statistika'=>Statistika::ucitajSve()
        ]);
    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->noviStatistika();
            return;
        }
        
        $this->statistika = (object) $_POST;
        
        if(!$this->kontrolaNastupi()){return;}
        if(!$this->kontrolaOdigranominuta()){return;}
        if(!$this->kontrolaGolovi()){return;}
        if(!$this->kontrolaAsistencije()){return;}
        if(!$this->kontrolaZutikartoni()){return;}
        if(!$this->kontrolaCrvenikartoni()){return;}
        
        
        
        Statistika::dodajNovi($this->statistika);

        
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
           $this->statistika=Statistika::ucitaj($_GET['sifra']);//u klasi smjer trebamo ucitati podatke o smjeru
           $this->poruka='Promijenite željene podatke';
           $this->promjenaView();//zatim pozovi promjenaView
           return;
        }

        $this->statistika = (object) $_POST;
        if(!$this->kontrolaNastupi()){return;}
        if(!$this->kontrolaOdigranominuta()){return;}
        if(!$this->kontrolaGolovi()){return;}
        if(!$this->kontrolaAsistencije()){return;}
        if(!$this->kontrolaZutikartoni()){return;}
        if(!$this->kontrolaCrvenikartoni()){return;}
        Statistika::promjeniPostojeci($this->statistika);
        $this->index();

    }

    public function brisanje()
    {
        if(!isset($_GET['sifra'])){//ako nemaš šifre ide odjava
                $ic= new IndexController();
                $ic->logout();
                return;
            }
            Statistika::obrisiPostojeci(($_GET['sifra']));
            header('location: ' . APP::config('url') .'statistika/index');
    }

    private function noviStatistika()
    {
        $this->statistika = new stdClass();
            $this->statistika->nastupi='';
            $this->statistika->odigranominuta='';
            $this->statistika->golovi='';
            $this->statistika->asistencije='';
            $this->statistika->zutikartoni='';
            $this->statistika->crvenikartoni='';
            $this->poruka='Unesite tražene podatke';
            $this->novoView();
    }

    private function novoView()
    {
        $this->view->render($this->viewDir. 'novo',[
            'statistika'=>$this->statistika,
            'poruka'=>$this->poruka
        
            ]);
    }

    private function promjenaView()//ne prima paramatre, salje parametre sa razine klase
    {
        $this->view->render($this->viewDir. 'promjena',[
            'statistika'=>$this->statistika,
            'poruka'=>$this->poruka
        
            ]);
    }

    private function kontrolaNastupi()
    {
        
        if(strlen(trim($this->statistika->nastupi))===0){
            $this->poruka='Unesite nastupe'; 
            $this->novoView();
                 return;
         }
         if(!is_numeric($this->statistika->nastupi)
            || ((int)$this->statistika->nastupi)<0){
                $this->poruka='Nastupi moraju biti cijeli pozitivni broj';
            $this->novoView();
            return false;
      }
         return true;
         
    }

    private function kontrolaOdigranominuta()
    {
        
        if(strlen(trim($this->statistika->odigranominuta))===0){
            $this->poruka='Unesite odigrane minute'; 
            $this->novoView();
                 return;
         }
         if(!is_numeric($this->statistika->odigranominuta)
            || ((int)$this->statistika->odigranominuta)<0){
                $this->poruka='Odigrane minute moraju biti cijeli pozitivni broj';
            $this->novoView();
            return false;
      }
         return true;
         
    }

    private function kontrolaGolovi()
    {
        
        if(strlen(trim($this->statistika->golovi))===0){
            $this->poruka='Unesite golove'; 
            $this->novoView();
                 return;
         }
         if(!is_numeric($this->statistika->golovi)
            || ((int)$this->statistika->golovi)<0){
                $this->poruka='Golovi moraju biti cijeli pozitivni broj';
            $this->novoView();
            return false;
      }
         return true;
         
    }

    private function kontrolaAsistencije()
    {
        
        if(strlen(trim($this->statistika->asistencije))===0){
            $this->poruka='Unesite asistencije'; 
            $this->novoView();
                 return;
         }
         if(!is_numeric($this->statistika->asistencije)
            || ((int)$this->statistika->asistencije)<0){
                $this->poruka='Asistencije moraju biti cijeli pozitivni broj';
            $this->novoView();
            return false;
      }
         return true;
         
    }

    private function kontrolaZutikartoni()
    {
        
        if(strlen(trim($this->statistika->zutikartoni))===0){
            $this->poruka='Unesite žute kartone'; 
            $this->novoView();
                 return;
         }
         if(!is_numeric($this->statistika->zutikartoni)
            || ((int)$this->statistika->zutikartoni)<0){
                $this->poruka='Žuti kartoni moraju biti cijeli pozitivni broj';
            $this->novoView();
            return false;
      }
         return true;
         
    }

    private function kontrolaCrvenikartoni()
    {
        
        if(strlen(trim($this->statistika->crvenikartoni))===0){
            $this->poruka='Unesite crvene kartone'; 
            $this->novoView();
                 return;
         }
         if(!is_numeric($this->statistika->crvenikartoni)
            || ((int)$this->statistika->crvenikartoni)<0){
                $this->poruka='Crveni kartoni moraju biti cijeli pozitivni broj';
            $this->novoView();
            return false;
      }
         return true;
         
    }

    

}