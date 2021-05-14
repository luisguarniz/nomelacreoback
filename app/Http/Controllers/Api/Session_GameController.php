<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Session_game;
use Illuminate\Http\Request;

class Session_GameController extends Controller
{
  //este metodo crea una sesion con todas las cartas disponibles en la tabla cards
  //modifica el estatus de de dos cartas de "0" a "1" y esas dos cartas son devueltas al front
    public function makeSession(Request $request){
        $statusInicial = false;
        $idRoom = $request->idRoom;
        $allcard = Card::all();
        $Sessiongames = array();
        $card = Card::all()->random(2); // con este metodo traigo un registro random
        for ($i=0; $i < count($allcard); $i++) { 
            $Sessiongame = array();
            $Sessiongame = new Session_game;
            $Sessiongame['idRoom'] = $idRoom;
            $Sessiongame['idCard'] = $allcard[$i]['id'];
            $Sessiongame['statusCards'] = $statusInicial;

            $Sessiongames[$i] = $Sessiongame;
            $Sessiongame->save();
        }

        for ($i=0; $i < count($card); $i++) {
          Session_game::where('session_games.idRoom', $idRoom)
          ->where('session_games.idCard', $card[$i]->id)
          ->update([
            'statusCards' => "1"
          ]);
      }
        return response()->json([
          'card'  => $card
        ]);
    }
}
