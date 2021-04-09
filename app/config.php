<?php

$dev=$_SERVER['REMOTE_ADDR']==='127.0.0.1' ? true : false;

if($dev){//lokalno
    $baza=[//uzima se baza
        'server'=>'localhost',
        'baza'=>'leta_zavrsni',
        'korisnik'=>'edunova',
        'lozinka'=>'edunova'
    ];
    $url='http://mojaapp.hr/';
}else{
    $baza=[
        'server'=>'localhost',
        'baza'=>'leta_zavrsni',
        'korisnik'=>'leta_zavrsni',
        'lozinka'=>'zavrsnilozinka'
    ];
    $url='http://polaznik25.edunova.hr/';
}

return [//moramo jos napraviti funkciju koja ce to dohvacat
    'url'=>$url,
    'nazivApp'=>'PlayerBasee',
    'baza'=>$baza,
    'rezultataPoStranici'=>2
];

