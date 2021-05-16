<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Game;
use App\Models\Session_turn;
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
}
