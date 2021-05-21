<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session_turn;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        Session_turn::where('session_turns.idUser', $idPrimerJugador->idUser)
        ->update([
          'turn' => true,
        ]);
        return response()->json([
            'first' => $idPrimerJugador->idUser
          ]);
    }

    public function changeTurn(Request $request){


      //cambio el estado del turn del idUser enviado a 0
       Session_turn::where('session_turns.idUser', $request->idUser)
       ->update([
        'turn' => false,
      ]);

      //obtengo el orderTurn del idUser enviado
      $orderTurn = DB::table('session_turns')
      ->select('session_turns.orderTurn')
      ->where('session_turns.idUser', $request->idUser)
      ->first();
  
      //consulto el siguiente jugador en turno y almaceno en orderTurnActual
      $orderTurnActual = Session_turn::where('session_turns.idSessionGame', $request->idSessionGame)
      ->where('session_turns.orderTurn', $orderTurn->orderTurn + 1)
      ->first();

      //pregunto si existe o no este turno ya que si me devuelve null entonces estaria tocandole al que tiene el turno nro 1
      if ($orderTurnActual == null) {
       Session_turn::where('session_turns.idSessionGame', $request->idSessionGame)
       ->where('session_turns.orderTurn', 1)
       ->update([
        'turn' => true,
      ]);

      $nextTurn = Session_turn::where('session_turns.idSessionGame', $request->idSessionGame)
      ->where('session_turns.orderTurn', 1)->first();

      return response()->json([
        'nextTurn' => $nextTurn->idUser
      ]);

      }
      else{
        
        Session_turn::where('session_turns.idSessionGame', $request->idSessionGame)
        ->where('session_turns.orderTurn', $orderTurnActual->orderTurn)
        ->update([
         'turn' => true,
       ]);
        
        $nextTurn = DB::table('session_turns')
      ->select('session_turns.idUser')
      ->where('session_turns.idSessionGame', $request->idSessionGame)
      ->where('session_turns.orderTurn', $orderTurnActual->orderTurn)
      ->first();
      }

      return response()->json([
          'nextTurn' => $nextTurn
        ]);
    }
}
