<?php

namespace App;

use App\Utilita\Data;
use App\IntervalloFormato;

class Calendario
{
    private $dataIniziale;
    private $anni;
    private $dataFinale;
    private $numeroGiorni;
    private $lista;

    public function __construct($dataIniziale, $anni)
    {
        $this->setDataIniziale($dataIniziale);
        $this->setAnni($anni);
        $this->setDataFinale();
        $this->setNumeroGiorni();
        $this->lista = [];
    }

    private function setDataFinale()
    {
        if (isset($this->dataIniziale) && isset($this->anni)) {
            $this->dataFinale = clone $this->dataIniziale;
            $stringaIntervallo = 'P' . $this->anni . 'Y';
            $interval = new \DateInterval($stringaIntervallo);
            $this->dataFinale->add($interval);
        } else {
            throw new \Exception("Calendario - parametri iniziali non configurati");
        }
    }

    /**
     * Get the value of anni
     */
    public function getAnni()
    {
        return $this->anni;
    }

    /**
     * Set the value of anni
     *
     * @return  self
     */
    public function setAnni($anni)
    {
        if (($anni > 0) && ($anni < 121)) {
            $this->anni = $anni;
        } else {
            throw new \Exception('Gli anni devono essere compresi tra zero e 120.');
        }
        return $this;
    }

    /**
     * Get the value of datainiziale
     */
    public function getDataIniziale()
    {
        return $this->dataIniziale;
    }

    /**
     * Set the value of dataIniziale
     *
     * @return  self
     */
    public function setDataIniziale($dataIniziale)
    {
        $this->dataIniziale = Data::create($dataIniziale);

        return $this;
    }

    /**
     * Get the value of dataFinale
     */
    public function getDataFinale()
    {
        return $this->dataFinale;
    }

    /**
     * Get the value of numeroGiorni
     */
    public function getNumeroGiorni()
    {
        return $this->numeroGiorni;
    }

    /**
     * Set the value of numeroGiorni
     *
     * @return  self
     */
    public function setNumeroGiorni()
    {
        $intervallo = $this->dataIniziale->diff($this->dataFinale);
        $this->numeroGiorni = $intervallo->days;

        return $this;
    }

    public function makeCalendar()
    {
        $giorno = clone $this->dataIniziale;
        $intervallo = new \DateInterval('P1D');
        
        for($contatore = 0; $contatore < $this->numeroGiorni; $contatore++) {
            $giornoArray = \App\Utilita\Data::dataToArray($giorno);
            $this->addGiorno(new Giorno($contatore, $giornoArray["giornosettimana"], $giornoArray["giorno"], $giornoArray["mese"], $giornoArray["anno"]));
            $giorno->add($intervallo);
        }
    }

    public function addGiorno(Giorno $giorno) 
    {
        $this->lista[] = $giorno;
    }

    public function getGiornoById(int $id) 
    {
        return $this->lista[$id];
    }

    public function getListaSlice(int $inizio, int $fine)
    {
        if(($inizio >= 0) && ($inizio<$fine) && ($fine<=count($this->getLista()))) {
            return array_slice($this->lista, $inizio, $fine-$inizio);
        } else {
            throw new \Exception("Valori slice non compatibili");            
        }        
    }

    private function searchDayInArray($arr, $day)
    {
        $risultato = [];
        foreach($arr as $a) {
            if($a->giornoSettimana == $day) {
                $risultato[] = $a;
            }
        }
        return $risultato;
    }

    public function getGiornoByIntervallo(int $inizio, int $fine, string $intervallo, $parametro = "")
    {
        if(IntervalloFormato::isValidValue($intervallo)) {
            switch($intervallo){
                case IntervalloFormato::TuttiIGiorni: return $this->getListaSlice($inizio, $fine);
                case IntervalloFormato::GiornoDellaSettimana: return $this->searchDayInArray($this->getListaSlice($inizio, $fine), $parametro); break;
            }
        } else {
            throw new \Exception("Non è stato richiesto un intervallo valido");            
        }        
    }

    /**
     * Get the value of lista
     */ 
    public function getLista()
    {
        return $this->lista;
    }
}
