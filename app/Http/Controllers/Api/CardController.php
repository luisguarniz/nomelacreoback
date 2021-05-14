<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function getCard()
    {

      $card = Card::all()->random(2); // con este metodo traigo un registro random

      for ($i=0; $i < count($card); $i++) {
        Card::where('cards.cardName', $card[$i]->cardName)
        ->update([
          'statusCard' => "1"
        ]);
    }
      return response()->json([
        'card'  => $card
      ]);
    }
}
