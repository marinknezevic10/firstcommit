<?php


class IgracController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'igrac'
                        . DIRECTORY_SEPARATOR;
    
    private $entitet=null;
    private $poruka='';
    private $klubovi=null;
    private $statistike=null;

    public function __construct()
    {
        parent::__construct();
        $this->klubovi=Klub::ucitajSve();
        
        $s=new stdClass();
        $s->sifra=-1;
        $s->naziv='Odaberite klub';
        array_unshift($this->klubovi,$s);


        $this->statistike=Statistika::ucitajSve();
        $s=new stdClass();
        $s->sifra=-1;
        $s->nastupi='';
        $s->golovi='';
        $s->asistencije='';
        array_unshift($this->statistike,$s);
    }

    public function index()
    {
        if(isset($_GET['uvjet'])){
            $uvjet='%' . $_GET['uvjet'] . '%';
        }else{
            $uvjet='%';
            $_GET['uvjet']='';
        }

        if(isset($_GET['stranica'])){
            $stranica = $_GET['stranica'];
            if($stranica==0){
                $stranica=1;
            }
        }else{
            $stranica=1;
        }

        $brojIgraca=Igrac::ukupnoIgraca($uvjet);
        $ukupnoStranica=ceil($brojIgraca/App::config('rezultataPoStranici'));


        if($stranica>$ukupnoStranica){
            $stranica=$ukupnoStranica;
        }

        $igraci = Igrac::ucitajSve($stranica,$uvjet);
        
        foreach($igraci as $red){
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR .
            'img' . DIRECTORY_SEPARATOR . 'igrac' . 
            DIRECTORY_SEPARATOR . $red->sifra . '.png')){
                $red->slika = App::config('url') . 
                'public/img/igrac/' . $red->sifra . '.png';
            }else{
                $red->slika = App::config('url') . 
                'public/img/igrac/nepoznato.png';
            }
        }

        $this->view->render($this->viewDir . 'index',[
            'entiteti'=>$igraci,
            'uvjet'=>$_GET['uvjet'],
            'stranica'=>$stranica,
            'ukupnoStranica'=>$ukupnoStranica
        ]);


    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->noviEntitet();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrola();
            $zadnjaSifraIgraca=Igrac::dodajNovi($this->entitet);
            header('location: ' . App::config('url') . 
            'igrac/novo?sifra=' . $zadnjaSifraIgraca);
        } catch (Exception $e) {
            $this->poruka=$e->getMessage();
            $this->novoView();
        }       
    }

    public function promjena()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            if(!isset($_GET['sifra'])){
               $ic = new IndexController();
               $ic->logout();
               return;
            }
            $this->entitet = Igrac::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite željene podatke';
            $this->promjenaView();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrola();
            Igrac::promjeniPostojeci($this->entitet);
            $this->index();
        } catch (Exception $e) {
            $this->poruka=$e->getMessage();
            $this->promjenaView();
        }       
    }


    public function brisanje()
    {
        if(!isset($_GET['sifra'])){
            $ic = new IndexController();
            $ic->logout();
            return;
        }
        Igrac::obrisiPostojeci($_GET['sifra']);
        header('location: ' . App::config('url') . 'igrac/index');
       
    }







    

    private function noviEntitet()
    {
        $this->entitet = new stdClass();
        $this->entitet->ime='';
        $this->entitet->prezime='';
        $this->entitet->mjestorodenja='';
        $this->entitet->klub=-1;
        $this->entitet->statistika=-1;
        
        
        $this->poruka='Unesite tražene podatke';
        $this->novoView();
    }

    private function promjenaView()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka,
            'klubovi'=>$this->klubovi,
            'statistike'=>$this->statistike
        ]);
    }


    private function novoView()
    {
        $this->view->render($this->viewDir . 'novo',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka,
            'klubovi'=>$this->klubovi,
            'statistike'=>$this->statistike
        ]);
    }

    private function kontrola()
    {
        $this->kontrolaIme();
        $this->kontrolaPrezime();
        $this->kontrolaKlub();
        $this->kontrolaStatistika();
    }

    private function kontrolaIme()
    {
        if(strlen(trim($this->entitet->ime))==0){
            throw new Exception('Ime obavezno');
        }

        if(strlen(trim($this->entitet->ime))>40){
            throw new Exception('Ime predugačko');
        }
    }
    private function kontrolaPrezime()
    {
        if(strlen(trim($this->entitet->prezime))==0){
            throw new Exception('Prezime obavezno');
        }

        if(strlen(trim($this->entitet->prezime))>40){
            throw new Exception('Prezime predugačko');
        }
    }
    private function kontrolaKlub()
    {
        if($this->entitet->klub==-1){
            throw new Exception('Klub obavezno');
        }
    }

    private function kontrolaStatistika()
    {
        if($this->entitet->statistika==-1){
            throw new Exception('Statistika obavezno');
        }
    }



}