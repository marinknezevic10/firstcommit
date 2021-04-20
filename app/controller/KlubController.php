<?php


class KlubController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'klub'
                        . DIRECTORY_SEPARATOR;
    
    private $entitet=null;
    private $poruka='';
    private $zanimljivo=null;
    private $treneri=null;
    

    public function __construct()
    {
        parent::__construct();
        $this->zanimljivo=Zanimljivosti::ucitajSve();
        
        $s=new stdClass();
        $s->sifra=-1;
        $s->nazivstadiona='Odaberite';
        $s->kapacitet=' zanimljivost';
        array_unshift($this->zanimljivo,$s);


        $this->treneri=Trener::ucitajSve();
        $s=new stdClass();
        $s->sifra=-1;
        $s->ime='Odaberite';
        $s->prezime='trenera';
        array_unshift($this->treneri,$s);
    }

    public function index()
    {

        $klubovi=Klub::ucitajSve();
        
        foreach($klubovi as $k){
            if($k->trener==null){
                $k->trener='[nije postavljeno]';
            }
        }

        $this->view->render($this->viewDir . 'index',[
            'entiteti'=>$klubovi
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
           Klub::dodajNovi($this->entitet);
           $this->index();
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
            $this->entitet = Klub::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite željene podatke';
            $this->promjenaView();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrola();
            Klub::promjeniPostojeci($this->entitet);
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
        Klub::obrisiPostojeci($_GET['sifra']);
        header('location: ' . App::config('url') . 'klub/index');
       
    }







    

    private function noviEntitet()
    {
        $this->entitet = new stdClass();
        $this->entitet->naziv='';
        $this->entitet->brojigracauklubu='';
        $this->entitet->prosjekgodina='';
        $this->entitet->zanimljivosti=-1;
        $this->entitet->trener=-1;
        $this->poruka='Unesite tražene podatke';
        $this->novoView();
    }

    private function promjenaView()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka,
            'zanimljivo'=>$this->zanimljivo,
            'treneri'=>$this->treneri,
            'css'=>'<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">',
            'js'=>'<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <script src="' . App::config('url') . 'public/js/grupa/promjena.js"></script>'
        ]);
    }


    private function novoView()
    {
        $this->view->render($this->viewDir . 'novo',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka,
            'zanimljivo'=>$this->zanimljivo,
            'treneri'=>$this->treneri
        ]);
    }

    private function kontrola()
    {
        $this->kontrolaNaziv();
        $this->kontrolaZanimljivosti();
        $this->kontrolaTrener();
    }

    private function kontrolaNaziv()
    {
        if(strlen(trim($this->entitet->naziv))==0){
            throw new Exception('Naziv obavezno');
        }

        if(strlen(trim($this->entitet->naziv))>40){
            throw new Exception('Naziv predugačak');
        }
    }

    private function kontrolaZanimljivosti()
    {
        if($this->entitet->zanimljivosti==-1){
            throw new Exception('Zanimljivosti obavezno');
        }
    }

    private function kontrolaTrener()
    {
        if($this->entitet->trener==-1){
            throw new Exception('Trener obavezno');
        }
    }



}