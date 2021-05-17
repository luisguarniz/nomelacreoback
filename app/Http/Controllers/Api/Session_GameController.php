<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Game;
use App\Models\Order_card;
use App\Models\Session_game;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class Session_GameController extends Controller
{
  //este metodo crea una sesion con todas las cartas disponibles en la tabla cards
  //modifica el estatus de de dos cartas de "0" a "1" y esas dos cartas son devueltas al front
    public function makeSession(Request $request){
        $statusInicial = false;
        $idSessionGame="";
        $isActive = true;
        $roomID = $request->roomID;
        $allcard = Card::all();
       // $Sessiongames = array();
        $card = Card::all()->random(2); // con este metodo traigo un registro random
        $Sessiongame = array();
        $Sessiongame = new Session_game;
        $idSessionGame  = Uuid::uuid();
        $Sessiongame['idSessionGame'] = $idSessionGame;
        $Sessiongame['roomID'] = $roomID;
        $Sessiongame['isActive'] = $isActive;
        //$Sessiongames = $Sessiongame;
        $Sessiongame->save();

        for ($i=0; $i < count($allcard); $i++) { 
            $games = array();
            $game = new Game;
            $game['idGame'] = Uuid::uuid();
            $game['idSessionGame'] = $idSessionGame;
            $game['idCard'] = $allcard[$i]['idCard'];
            $game['statusCards'] = $statusInicial;
            $games[$i] = $game;
            $game->save();
        }

        for ($i=0; $i < count($card); $i++) {
          Game::where('games.idSessionGame', $idSessionGame)
          ->where('games.idCard', $card[$i]->idCard)
          ->update([
            'statusCards' => "1"
          ]);
      }

      for ($i=0; $i < count($card); $i++) {

        $orderCard = new Order_card;
        $orderCard['idOrderCard'] = Uuid::uuid();
        $orderCard["roomID"] = $roomID;
        $orderCard["idSessionGame"] = $idSessionGame;
        $orderCard["idCard"] = $card[$i]->idCard;
        $orderCard["position"] = $i; // la primera posicion sera el cero
        $orderCard->save();
       
    }
        return response()->json([
          'card'  => $card,
          'idSessionGame' => $idSessionGame
        ]);
    }
}
