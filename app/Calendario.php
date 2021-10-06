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

        for ($contatore = 0; $contatore < $this->numeroGiorni; $contatore++) {
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
        if (($inizio >= 0) && ($inizio < $fine) && ($fine <= count($this->getLista()))) {
            return array_slice($this->lista, $inizio, $fine - $inizio);
        } else {
            throw new \Exception("Valori slice non compatibili");
        }
    }

    private function searchGiornoDellaSettimana($arr, $day)
    {
        $risultato = [];
        foreach ($arr as $a) {
            if ($a->giornoSettimana == $day) {
                $risultato[] = $a;
            }
        }
        return $risultato;
    }

    private function searchOgni1Mese($arr, $idDay)
    {
        $g = $this->getGiornoById($idDay);

        $risultato = [];
        foreach ($arr as $a) {
            if ($a->giorno == $g->giorno) {
                $risultato[] = $a;
            }
        }
        return $risultato;
    }

    private function searchOgniXMesi($arr, $idDay, $xMesi)
    {
        $g = $this->getGiornoById($idDay);
        $giorno = $g->giorno;

        $risultato = [];
        $cicloMese = 1;
        $mese = $arr[0]->mese;
        $contatoreMese = 0;
        $checkPrimoMese = false;
        $checkMeseInCorso = false;
        
        foreach ($arr as $a) {
            if ($a->mese != $mese) {
                $mese = $a->mese;
                
                $contatoreMese++;
                // Non cicla se non ho fatto un'inserimento del mese con cliclo == 1
                if($contatoreMese == 1 && $checkPrimoMese == false) {
                    $cicloMese == 1;
                } else {
                    $cicloMese++;
                    $checkMeseInCorso = false;
                }                
            }

            if ($cicloMese > $xMesi) {
                $cicloMese = 1;
            }

            if (($a->giorno == $giorno) && ($a->mese == $mese) && ($cicloMese == 1)) {
                $risultato[] = $a;
                $checkPrimoMese = true;
                $checkMeseInCorso = true;
            }

            // Se ultimo giorno del mese con contatore == 1 allora inserisci il risultato (a parte il primo mese)
            if(($cicloMese == 1) && ($contatoreMese > 0) && ($checkMeseInCorso == false)) {
                if(($a->giorno == 31) && (($mese == "01") || ($mese == "03") || ($mese == "05") || ($mese == "07") || ($mese == "08") || ($mese == "10") || ($mese == "12"))) {
                    $risultato[] = $a;
                }
    
                if(($a->giorno == 28) && ($mese == "02")) {
                    $risultato[] = $a;
                }
    
                if(($a->giorno == 30) && (($mese == "04") || ($mese == "06") || ($mese == "09") || ($mese == "11"))) {
                    $risultato[] = $a;
                }
            }            
        }
        return $risultato;
    }

    public function getGiornoByIntervallo(int $inizio, int $fine, string $intervallo, $parametro = "")
    {
        /*
        const Ogni1Anno = "Ogni anno";
        const Ogni2Anni = "Ogni 2 anni";
        const Ogni3Anni = "Ogni 3 anni";
        const Ogni5Anni = "Ogni 5 anni";
        const Ogni10Anni = "Ogni 10 anni";
        const Ogni20Anni = "Ogni 20 anni";
        const Ogni30Anni = "Ogni 30 anni";
        const Ogni50Anni = "Ogni 50 anni";  
        */
        if (IntervalloFormato::isValidValue($intervallo)) {
            switch ($intervallo) {
                case IntervalloFormato::Singolo:
                    return $this->getListaSlice($inizio, $inizio + 1);
                case IntervalloFormato::TuttiIGiorni:
                    return $this->getListaSlice($inizio, $fine);
                case IntervalloFormato::GiornoDellaSettimana:
                    return $this->searchGiornoDellaSettimana($this->getListaSlice($inizio, $fine), $parametro);
                    break;
                case IntervalloFormato::Ogni1Mese:
                    return $this->searchOgni1Mese($this->getListaSlice($inizio, $fine), $parametro);
                    break;
                case IntervalloFormato::Ogni2Mesi:
                    return $this->searchOgniXMesi($this->getListaSlice($inizio, $fine), $parametro, 2);
                    break;
                case IntervalloFormato::Ogni3Mesi:
                    return $this->searchOgniXMesi($this->getListaSlice($inizio, $fine), $parametro, 3);
                    break;
                case IntervalloFormato::Ogni4Mesi:
                    return $this->searchOgniXMesi($this->getListaSlice($inizio, $fine), $parametro, 4);
                    break;
                case IntervalloFormato::Ogni6Mesi:
                    return $this->searchOgniXMesi($this->getListaSlice($inizio, $fine), $parametro, 6);
                    break;
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
