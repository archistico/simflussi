<?php

namespace App;

abstract class IntervalloFormato extends \App\Utilita\BasicEnum
{
    /*
    Risultati compresi tra inizio e fine
    - un solo giorno
    - tutti i giorni 
    - specifico giorno della settimana
    - specifico giorno del mese
    - ogni 1 mese partendo da inizio
    - ogni 2 mesi
    - ogni 3 mesi
    - ogni 4 mesi
    - ogni 6 mesi
    - ogni 1 anno
    - ogni 2 anni
    - ogni 3 anni
    - ogni 5 anni
    - ogni 10 anni
    - ogni 20 anni
    - ogni 30 anni
    */
    
    const Singolo = "Singolo";
    const TuttiIGiorni = "Tutti i giorni";
    const GiornoDellaSettimana = "Specifico giorno della settimana";
    const GiornoDelMese = "Specifico giorno del mese";

}

abstract class BasicEnum {
    private static $constCacheArray = NULL;

    private static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function isValidName($name, $strict = false) {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value, $strict = true) {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }
}