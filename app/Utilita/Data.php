<?php

namespace App\Utilita;

use App\GiornoSettimana;

class Data
{
	private static function check($date, $format = 'd/m/Y', $strict = true)
	{
		$dateTime = \DateTime::createFromFormat($format, $date);
		if ($strict) {
			$errors = \DateTime::getLastErrors();
			if (!empty($errors['warning_count'])) {
				return false;
			}
		}
		return $dateTime !== false;
	}

	public static function create($datestring, $format = 'd/m/Y')
	{
		if (is_null($datestring) || empty($datestring) || !self::check($datestring, $format)) {
			return "!";
		} else {
			return \DateTime::createFromFormat($format, $datestring);
		}
	}

	public static function dataToArray($data)
	{
		if ($data instanceof \DateTime) {
			$numeroGiorno = $data->format("N");
			switch($numeroGiorno) {
				case 1: $giornosettimana = GiornoSettimana::Lunedì; break;
				case 2: $giornosettimana = GiornoSettimana::Martedì; break;
				case 3: $giornosettimana = GiornoSettimana::Mercoledì; break;
				case 4: $giornosettimana = GiornoSettimana::Giovedì; break;
				case 5: $giornosettimana = GiornoSettimana::Venerdì; break;
				case 6: $giornosettimana = GiornoSettimana::Sabato; break;
				case 7: $giornosettimana = GiornoSettimana::Domenica; break;
			}
			return [
				"giornosettimana" => $giornosettimana,
				"giorno" => $data->format("d"),
				"mese" => $data->format("m"),
				"anno" => $data->format("Y")				
			];
		} else {
			throw new \Exception("Errore conversione data in array");
		}
	}
}
