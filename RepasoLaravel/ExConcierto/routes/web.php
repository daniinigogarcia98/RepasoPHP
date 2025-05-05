<?php

use App\Http\Controllers\ConciertoC;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('rI');
});


Route::controller(ConciertoC::class)->group(
    function(){
        Route::get('inicio','inicioM')-> name('rI');
        Route::get('entradas/{idConciertos}','entradasM')-> name('rE');
        Route::post('entradas/{idConciertos}','venderM')-> name('rV');
        Route::delete('concierto/{idConciertos}','borrarM')-> name('rB');
    }
);