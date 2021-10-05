<?php

use App\Calendario;
use App\GiornoSettimana;
use App\IntervalloFormato;

require_once __DIR__ . '/vendor/autoload.php';

define("CALENDARIO_DATA_INIZIALE", "25/11/1977");
define("CALENDARIO_DURATA_ANNI", 110);

$c = new Calendario(CALENDARIO_DATA_INIZIALE, CALENDARIO_DURATA_ANNI);
echo "Data iniziale: ". $c->getDataIniziale()->format('d/m/Y')."\n";
echo "Data finale: ". $c->getDataFinale()->format('d/m/Y')."\n";
echo "Numero giorni: ". $c->getNumeroGiorni()."\n";

$c->makeCalendar();

var_dump($c->getGiornoByIntervallo(0, 20, IntervalloFormato::GiornoDellaSettimana, GiornoSettimana::Lunedì));
// var_dump($c->getGiornoByIntervallo(10, 20, IntervalloFormato::TuttiIGiorni));
