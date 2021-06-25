<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Game;
use App\Models\Order_card;
use App\Models\Score;
use App\Models\Session_turn;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


  //recibe arreglo de solo ids de las cartas
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

  public function getOrderCards(Request $request){

    $orderCards = DB::table('order_cards')
    ->join('cards', 'cards.idCard', '=', 'order_cards.idCard')
    ->select('cards.*')
    ->where('order_cards.idSessionGame', $request->idSessionGame)
    ->orderBy('order_cards.position', 'asc')
    ->get();
    return response()->json([
      'orderCards'  => $orderCards
    ]);
  }

  public function makeOrderCard(Request $request)
  {

    for ($i = 0; $i < count($request->card); $i++) {

      $orderCard = new Order_card;
      $orderCard['idOrderCard'] = Uuid::uuid();
      $orderCard["roomID"] = $request->roomID;
      $orderCard["idSessionGame"] = $request->idSessionGame;
      $orderCard["idCard"] = $request->card[$i]["idCard"];
      $orderCard["position"] = $i; // la primera posicion sera el cero
      $orderCard->save();
    }

    return response()->json([
      'message'  => "se creo el orden inicial con las dos cartas"
    ]);
  }

  public function deleteOrderCards(Request $request){
   
    $respuesta = Order_card::where('idSessionGame', $request->idSessionGame)->delete();
    return response()->json([
      'respuesta'  => "respuesta al eliminar deleteOrderCards",
      '$respuesta' => $respuesta
    ]);
  }

  public function compareCards(Request $request){


    $ordenNomelaCreo = DB::table('order_cards')
    ->join('cards', 'cards.idCard', '=', 'order_cards.idCard')
    ->select('order_cards.*')
    ->where('order_cards.idSessionGame', $request->idSessionGame)
    ->orderBy('order_cards.position', 'asc')
    ->get();

    //este es si se esta jugando con el color azul
    if ($request->colorRandom == "Celeste") {
      $ordenNomelaCreoCorrecto = DB::table('order_cards')
      ->join('cards', 'cards.idCard', '=', 'order_cards.idCard')
      ->select('order_cards.*')
      ->where('order_cards.idSessionGame', $request->idSessionGame)
      ->orderBy('cards.valueBlue', 'asc')
      ->get();
    }else if ($request->colorRandom == "Rojo") {
      $ordenNomelaCreoCorrecto = DB::table('order_cards')
      ->join('cards', 'cards.idCard', '=', 'order_cards.idCard')
      ->select('order_cards.*')
      ->where('order_cards.idSessionGame', $request->idSessionGame)
      ->orderBy('cards.valueRed', 'asc')
      ->get();
    }else if ($request->colorRandom == "Verde") {
      $ordenNomelaCreoCorrecto = DB::table('order_cards')
      ->join('cards', 'cards.idCard', '=', 'order_cards.idCard')
      ->select('order_cards.*')
      ->where('order_cards.idSessionGame', $request->idSessionGame)
      ->orderBy('cards.valueGreen', 'asc')
      ->get();
    }else if ($request->colorRandom == "Amarillo") {
      $ordenNomelaCreoCorrecto = DB::table('order_cards')
      ->join('cards', 'cards.idCard', '=', 'order_cards.idCard')
      ->select('order_cards.*')
      ->where('order_cards.idSessionGame', $request->idSessionGame)
      ->orderBy('cards.valueYellow', 'asc')
      ->get();
    }

  
 

    if ($ordenNomelaCreo == $ordenNomelaCreoCorrecto) {

      //esto quiere decir que el jugador no adivino entonces se le sumara un punto al jugador anterior
      //ubico al jugador anterior

            //consulto el turno del jugador que a presionado
            $orderTurnActual = DB::table('session_turns')
            ->select('session_turns.orderTurn')
            ->where('session_turns.idSessionGame', $request->idSessionGame)
            ->where('session_turns.idUser', $request->idUser)
            ->first();
      

            //consulto el jugador anterior y almaceno en la variable orderTurnAnterior
            $orderTurnAnterior = Session_turn::where('session_turns.orderTurn', $orderTurnActual->orderTurn - 1)->first();

            //pregunto si existe o no este turno ya que si me devuelve null entonces estaria tocandole al que tiene el turno mas alto
            if ($orderTurnAnterior == null) {

              //ubico el turno mas alto
              $MAXturn = Session_turn::where('session_turns.idSessionGame', $request->idSessionGame)
              ->max('orderTurn');



              $idUserMAX = Session_turn::where('idSessionGame', $request->idSessionGame)
              ->where('orderTurn', $MAXturn)
              ->first();
        

                    //traemos el score del usuario que acerto
               $scoredeusuario = DB::table('scores')
              ->select('scores.score')
              ->where('scores.roomID', $request->roomID)
              ->where('scores.idUser', $idUserMAX->idUser)
              ->first();

                  //le sumamos uno al usuario anterior
              Score::where('scores.roomID', $request->roomID)
               ->where('scores.idUser', $idUserMAX->idUser)
               ->update([
               'score' => $scoredeusuario->score + 1
                       ]);

      
    }else{
      //el el turno anterior no es nulo

      $idAnterior = DB::table('session_turns')
      ->select('session_turns.idUser')
      ->where('session_turns.idSessionGame', $request->idSessionGame)
      ->where('session_turns.orderTurn', $orderTurnAnterior->orderTurn)
      ->first();

      //traemos el score del usuario anterior
      $scoredeusuarioAnterior = DB::table('scores')
      ->select('scores.score')
      ->where('scores.roomID', $request->roomID)
      ->where('scores.idUser', $idAnterior->idUser)
      ->first();

      Score::where('scores.roomID', $request->roomID)
      ->where('scores.idUser', $idAnterior->idUser)
      ->update([
        'score' => $scoredeusuarioAnterior->score + 1
      ]);

    }

    return response()->json([
      'message'  => "el orden es correcto",
      '$ordenNomelaCreo' => $ordenNomelaCreo,
      'ordenNomelaCreoCorrecto' => $ordenNomelaCreoCorrecto
    ]);
  }
    else {

    //esto quiere decir que el jugador adivino que estaba mal el orden entonces se le sumara un punto


      
      //traemos el score del usuario que acerto
      $scoredeusuario = DB::table('scores')
      ->select('scores.score')
      ->where('scores.roomID', $request->roomID)
      ->where('scores.idUser', $request->idUser)
      ->first();

    //le sumamos uno al usuario que acerto
      Score::where('scores.roomID', $request->roomID)
      ->where('scores.idUser', $request->idUser)
      ->update([
        'score' => $scoredeusuario->score + 1
      ]);

      //devolvemos los dos arreglos comparados
      return response()->json([
        'message'  => "el orden es incorrecto",
        '$ordenNomelaCreo' => $ordenNomelaCreo,
        'ordenNomelaCreoCorrecto' => $ordenNomelaCreoCorrecto
      ]);
    }
  }
}
