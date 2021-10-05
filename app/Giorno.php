<?php

namespace App;

class Giorno
{
    public $id;
    public $giornoSettimana;
    public $giorno;
    public $mese;
    public $anno;
    
    public function __construct($id, $giornoSettimana, $giorno, $mese, $anno)
    {
        $this->id = $id;
        $this->giornoSettimana = $giornoSettimana;
        $this->giorno = $giorno;
        $this->mese = $mese;
        $this->anno = $anno;
    }
}
