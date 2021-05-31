<?php

class IndexController extends Controller
{
    public function index()
    {
        $this->view->render('pocetna');
    }
    public function process()
    {
        $this->view->render('process');

    }
   

    public function pocetna()
    {
        $this->view->render('pocetna');
    }

    public function novosti()
    {
        $this->view->render('novosti');
    }

    public function transferi()
    {
        $this->view->render('transferi');
    }

    public function petrinja()
    {
        $this->view->render('petrinja');
    }

    public function neymar()
    {
        $this->view->render('neymar');
    }

    public function messi()
    {
        $this->view->render('messi');
    }

    public function modric()
    {
        $this->view->render('modric');
    }

    public function osdin()
    {
        $this->view->render('osdin');
    }

    public function ramos()
    {
        $this->view->render('ramos');
    }

    public function bruyne()
    {
        $this->view->render('bruyne');
    }

    public function nadzornaploca()
    {
        $this->view->render('nadzornaploca');
    }

    public function login()
    {
        $this->loginView('','');
    }

    public function register()
    {
        $this->registerView('','');
    }

    public function logout()
    {
        unset($_SESSION['autoriziran']);
        session_destroy();
        $this->index();
    }

    public function autorizacija()
    {
        if(!isset($_POST['email']) || !isset($_POST['lozinka'])){
            $this->login();
            return; //short curcuiting
        }

        if(strlen(trim($_POST['email']))===0){
            $this->loginView('','Obavezno email');
            return;
        }

        if(strlen(trim($_POST['lozinka']))===0){
            $this->loginView($_POST['email'],'Obavezno lozinka');
            return;
        }
        //siguran sam da su email i lozinka postavljeni
        
        //rad s bazom
        $veza=DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from operater where email=:email
            
        
        ');
        $izraz->execute(['email'=>$_POST['email']]);
        $rezultat=$izraz->fetch();//ide samo fetch jer necemo dobit vise operatera, nego samo jednog, ali i dalje ce bit object
        
        if($rezultat==null){
            //email ne postoji u bazi
            $this->loginView($_POST['email'],'Email ne postoji u bazi');
            return;
        }
        //za provjeru lozinke funkcija password_verify
        if(!password_verify($_POST['lozinka'],$rezultat->lozinka)){
            $this->loginView($_POST['email'],'Kombinacija email i lozinka ne odgovaraju');
            return;
        }




       /* if(!($_POST['email']==='edunova@edunova.hr' &&
        $_POST['lozinka']==='e') ){
            $this->loginView($_POST['email'],'Neispravna kombinacija emaila i lozinke');
            return;
        }*/

        unset($rezultat->lozinka);
        $_SESSION['autoriziran']=$rezultat;
        $np = new NadzornaplocaController();
        $np->index();

    }

    private function loginView($email,$poruka)
    {
        $this->view->render('login',[
            'email'=>$email,
            'poruka'=>$poruka
        ]);
    }

    private function registerView($email,$poruka)
    {
        $this->view->render('register',[
            'email'=>$email,
            'poruka'=>$poruka
        ]);
    }

    public function ajax()
    {
        echo json_encode(Igrac::ucitajSve(1,'%'));
    }

    //public function zbrajanje()
      //  {
        //$this->view->render('zbrajanje',[
          //  'zbroj'=>$_POST['broj1']+ $_POST['broj2']
            
        //]);
        
    //}
   
    //public function test()
    //{
      //  $veza = DB::getInstanca();
        //$izraz = $veza->prepare('select * from smjer');
        //$izraz->execute();
        //$rezultati = $izraz->fetchAll();
        //print_r($rezultati);
    //}
}