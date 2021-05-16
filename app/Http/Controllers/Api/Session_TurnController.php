<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session_turn;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class Session_TurnController extends Controller
{
    public function makeSessionTurn(Request $request){
         
        $alluser = array();
        $tunrInicial = false;
        $orderTurnInicial = 1;
        $alluser = $request->idsParticipantes;
        for ($i=0; $i < count($alluser); $i++) { 
        $sessionTurn = new Session_turn;
        $sessionTurn["idTurn"] = Uuid::uuid();
        $sessionTurn["idUser"] = $alluser[$i];
        $sessionTurn["idSessionGame"] = $request->idSessionGame;
        $sessionTurn["turn"] = $tunrInicial;
        $sessionTurn["orderTurn"] = $i + 1;
        $sessionTurn->save();
        }
        $idPrimerJugador = Session_turn::where('session_turns.idSessionGame', $request->idSessionGame)->get()->random();
        return response()->json([
            'el primero en jugar es el id' => $idPrimerJugador->idUser
          ]);
    }
}
