<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;


class ViewController extends Controller
{


    

    public function bukuPanduan(Request $request)
    {
        $header  = "";
        $pathToFile = "{{public_path('files/E-book Penyusunan SKP.pdf') }}";
        return response()->file($pathToFile, $headers);
    }




}
