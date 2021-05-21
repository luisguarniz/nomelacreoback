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
  public function makeSession(Request $request)
  {

    /////////////////////////////creo la Session_game/////////////////////////////////////////////

    $idSessionGame = "";
    $isActive = true;
    $roomID = $request->roomID;

    // $Sessiongames = array();
    $Sessiongame = array();
    $Sessiongame = new Session_game;
    $idSessionGame  = Uuid::uuid();
    $Sessiongame['idSessionGame'] = $idSessionGame;
    $Sessiongame['roomID'] = $roomID;
    $Sessiongame['isActive'] = $isActive;
    //$Sessiongames = $Sessiongame;
    $Sessiongame->save();

    return response()->json([
      'idSessionGame' => $idSessionGame
    ]);
  }

  public function makeGame(Request $request)
  {
    //////////////////////////creo la tabla game////////////////////////////////////////////////////
    $statusInicial = false;
    $allcard = Card::all();
    //$roomID = $request->roomID;
    for ($i = 0; $i < count($allcard); $i++) {
      $games = array();
      $game = new Game;
      $game['idGame'] = Uuid::uuid();
      $game['idSessionGame'] = $request->idSessionGame;
      $game['idCard'] = $allcard[$i]['idCard'];
      $game['statusCards'] = $statusInicial;
      $games[$i] = $game;
      $game->save();
    }
    return response()->json([
      'message' => "se creo un registro en la tabla games"
    ]);
  }

  public function updateStatusCardInicio(Request $request)
  {
    $card = Card::all()->random(2); // con este metodo traigo un registro random
    for ($i = 0; $i < count($card); $i++) {
      Game::where('games.idSessionGame', $request->idSessionGame)
        ->where('games.idCard', $card[$i]->idCard)
        ->update([
          'statusCards' => "1"
        ]);
    }
    
    return response()->json([
      'card'  => $card,
      //'idSessionGame' => $request->idSessionGame
    ]);
  }

  public function makeOrderCard(Request $request)
  {
    for ($i = 0; $i < count($request->card); $i++) {

      $orderCard = new Order_card;
      $orderCard['idOrderCard'] = Uuid::uuid();
      $orderCard["roomID"] = $request->roomID;
      $orderCard["idSessionGame"] = $request->idSessionGame;
      $orderCard["idCard"] = $request->card[$i]->idCard;
      $orderCard["position"] = $i; // la primera posicion sera el cero
      $orderCard->save();
    }
  }
}
