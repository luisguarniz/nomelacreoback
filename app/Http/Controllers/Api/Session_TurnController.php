<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session_turn;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Session_TurnController extends Controller
{
    public function makeSessionTurn(Request $request){
         
      //creamos y asignamos un numero de posicion para cada jugador
      //no quiere decir que el jugador con posicion uno va ser el primero en 
      //esto quiere decir que el admin tendra el numero menor siempre pero no sera el primero en jugar siempre
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
        
        //elejimos un numero de los jugadores para que empiece a jugar
        //luego seguira el numero siguiente en jugar
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

      $nextTurn = DB::table('session_turns')
      ->select('session_turns.idUser')
      ->where('session_turns.idSessionGame', $request->idSessionGame)
      ->where('session_turns.orderTurn', 1)
      ->first();
      
     $customName = DB::table('users')
     ->select('users.customName')
     ->where('users.id', $nextTurn->idUser)
     ->first();

      return response()->json([
        'nextTurn' => $nextTurn,
        'customName'=> $customName
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

      $customName = DB::table('users')
      ->select('users.customName')
      ->where('users.id', $nextTurn->idUser)
      ->first();
 
       return response()->json([
         'nextTurn' => $nextTurn,
         'customName'=> $customName
       ]);
 
    }

    public function getTurn(Request $request){

       $inTurn = Session_turn::where('session_turns.idSessionGame', $request->idSessionGame)
       ->where('session_turns.turn', true)->first();

       return response()->json([
        'turn' => $inTurn
      ]);
    }


    public function makeSessionTurnSolo(Request $request){
         
      //creamos y asignamos un numero de posicion para cada jugador
      //no quiere decir que el jugador con posicion uno va ser el primero en 
      //esto quiere decir que el admin tendra el numero menor siempre pero no sera el primero en jugar siempre
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
        
        //elejimos un numero de los jugadores para que empiece a jugar
        //luego seguira el numero siguiente en jugar
        $idPrimerJugador = Session_turn::where('session_turns.idSessionGame', $request->idSessionGame)->get()->random();

        //consultar con $idPrimerJugador->idUser el nombre del usuario para mostrar quien esta en turno 
        $firsTurn = User::where('users.id', $idPrimerJugador->idUser)->first();

        Session_turn::where('session_turns.idUser', $idPrimerJugador->idUser)
        ->update([
          'turn' => true,
        ]);
        return response()->json([
            'firstid' => $firsTurn->id,
            'customName' => $firsTurn->customName
          ]);
    }

    public function getUserTurnNow(Request $request)
    {
      $customName = DB::table('session_turns')
      ->join('users', 'users.id', '=', 'session_turns.idUser')
      ->select('users.customName')
      ->where('session_turns.idSessionGame', $request->idSessionGame)
      ->where('session_turns.turn', 1)
      ->first();

      return response()->json([
        'customName' => $customName
      ]);

    }


}
