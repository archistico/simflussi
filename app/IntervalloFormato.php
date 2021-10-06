<?php

namespace App;

abstract class IntervalloFormato extends \App\Utilita\BasicEnum
{
    const Singolo = "Singolo";
    const TuttiIGiorni = "Tutti i giorni";
    const GiornoDellaSettimana = "Specifico giorno della settimana";
    const Ogni1Mese = "Ogni mese";
    const Ogni2Mesi = "Ogni 2 mesi";
    const Ogni3Mesi = "Ogni 3 mesi";
    const Ogni4Mesi = "Ogni 4 mesi";
    const Ogni6Mesi = "Ogni 6 mesi";
    const Ogni1Anno = "Ogni anno";
    const Ogni2Anni = "Ogni 2 anni";
    const Ogni3Anni = "Ogni 3 anni";
    const Ogni5Anni = "Ogni 5 anni";
    const Ogni10Anni = "Ogni 10 anni";
    const Ogni20Anni = "Ogni 20 anni";
    const Ogni30Anni = "Ogni 30 anni";
    const Ogni50Anni = "Ogni 50 anni";  
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