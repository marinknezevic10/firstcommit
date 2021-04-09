<?php

class Controller
{
    //protected se odnosni na mene i na sve nasljedene
    protected $view;

    public function __construct()//ova funkcija je dio oop-a
    {
        //lokalnoj varijabli view dodijeli novu instancu klase view, poziva liniju 7 u modelu view
        $this->view=new View();
    }
}