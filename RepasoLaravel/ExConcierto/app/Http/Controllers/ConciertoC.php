<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConciertoC extends Controller
{
    function inicioM(){
        return 'Página de inicio';
    }

    function entradasM($idC){
        return 'Página de entradas del concierto  ' .$idC;
    }

    function venderM($idC){
        return 'Página de ventas de entrada del concierto  ' .$idC;
    }
    function borrarM($idC){
        return 'Página de borrar entradas del concierto  ' .$idC;
    }
}
