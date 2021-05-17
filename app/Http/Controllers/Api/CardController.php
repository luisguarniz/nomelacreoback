<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Game;
use App\Models\Order_card;
use App\Models\Session_turn;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class CardController extends Controller
{
  public function getCard(Request $request)
  {


    $cardsNoElegidas = false;
    
    $game = Game::where('games.idSessionGame', $request->idSessionGame)
      ->where('games.statusCards', $cardsNoElegidas)
      ->get();

    if (!$game->isEmpty()) {
      $game = $game->random();
      Game::where('games.idCard', $game->idCard)
        ->update([
          'statusCards' => '1'
        ]);

      $card = Card::where('cards.idCard', $game->idCard)->first();
        return response()->json([
          'card' => $card
        ]);
    } else {
      return response()->json([
        'mensage'  => "ya no hay mas cartas"
      ]);
    }
  }


  public function moveCard(Request $request){
    //eliminar las cartas actuales

    $newOrderCard = array();
    $newOrderCard = $request->cards;

    $deletedRows = Order_card::where('idSessionGame', $request->idSessionGame)->delete();

  //insertar las nuevas posiciones de las cartas
    
   // $newOrderCard = $request->cards;
  for ($i=0; $i < count($newOrderCard); $i++) { 
    $sessionTurn = new Order_card;
    $sessionTurn["idOrderCard"] = Uuid::uuid();
    $sessionTurn["roomID"] = $request->roomID;
    $sessionTurn["idSessionGame"] = $request->idSessionGame;
    $sessionTurn["idCard"] = $request->cards[$i];
    $sessionTurn["position"] = $i;
    $sessionTurn->save();
    }

  //devolver un mensaje que indique que se creo el nuevo orden de cartas
  return response()->json([
    'mensage'  => "se creo un nuevo orden de cartas"
  ]);
  }
}
