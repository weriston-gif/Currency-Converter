<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $moedas = [

            'DEFALT' => '',
            'AED' => 'AED',
            'AFN' => 'AFN',
            'ALL' => 'ALL',
            'AMD' => 'AMD',
            'ANG' => 'ANG',
            'AOA' => 'AOA',
            'ARS' => 'ARS',
            'AUD' => 'AUD',
            'AWG' => 'AWG',
            'AZN' => 'AZN',
            'BAM' => 'BAM',
            'BBD' => 'BBD',
            'BDT' => 'BDT',
            'BGN' => 'BGN',
            'BHD' => 'BHD',
            'BIF' => 'BIF',
            'BMD' => 'BMD',
            'BND' => 'BND',
            'BOB' => 'BOB',
            'BRL' => 'BRL',
            'BSD' => 'BSD',
            'BTC' => 'BTC',
            'BTN' => 'BTN',
            'BWP' => 'BWP',
            'BYN' => 'BYN',
            'BZD' => 'BZD',
            'CAD' => 'CAD',
            'CDF' => 'CDF',
            'CHF' => 'CHF',
            'CLF' => 'CLF',
            'CLP' => 'CLP',
            'CNH' => 'CNH',
            'CNY' => 'CNY',
            'COP' => 'COP',
            'CRC' => 'CRC',
            'CUC' => 'CUC',
            'CUP' => 'CUP',
            'CVE' => 'CVE',
            'CZK' => 'CZK',
            'DJF' => 'DJF',
            'DKK' => 'DKK',
            'DOP' => 'DOP',
            'DZD' => 'DZD',
            'EGP' => 'EGP',
            'ERN' => 'ERN',
            'ETB' => 'ETB',
            'EUR' => 'EUR',
            'FJD' => 'FJD',
            'FKP' => 'FKP',
            'GBP' => 'GBP',
            'GEL' => 'GEL',
            'GGP' => 'GGP',
            'GHS' => 'GHS',
            'GIP' => 'GIP',
            'GMD' => 'GMD',



        ];



        return view('currency', [
            'moedas' => $moedas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //

        $first = $_POST['firstSelectValue'];
        $second = $_POST['secondSelectValue'];

        $currency = new Currency();
        $datecurrency = $currency->consultingCurrency($first, $second);
        
        if (!empty($datecurrency)) {
            return response()->json($datecurrency);
        } else {
            return response()->json(['error' => 'No results found.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
