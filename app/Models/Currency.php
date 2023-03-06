<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    const BASE_URL = 'https://economia.awesomeapi.com.br/json';



    /**
     * Função calcula 2 moedas 
     * @param {currencyA} - Primeira moeda.
     * @param {currencyB} - Segunda moeda.
     * @returns array
     */
    public function consultingCurrency($currencyA, $currencyB)
    {

        return $this->get('/last/' . $currencyA . '-' . $currencyB);
    }

    public function get($resourse)
    {
        $endpoint = self::BASE_URL . $resourse;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
}
