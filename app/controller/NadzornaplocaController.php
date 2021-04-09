<?php

class NadzornaplocaController extends AutorizacijaController//na nadzornu mogu doci samo oni koji su autorizirani
{
    public function index()
    {
        $this->view->render('privatno' . DIRECTORY_SEPARATOR. 'nadzornaploca');
    }

    

}