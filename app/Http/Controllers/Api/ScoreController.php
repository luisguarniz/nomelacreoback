<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Score;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    public function makeScore(Request $request){

      $idsJugando = array();
      $idsEntrantes = array();
      $idsParticipantes = $request->idsParticipantes;

        $existe = array();
        $existe = DB::table('scores')
        ->select('idUser')
        ->where('roomID', $request->roomID)
        ->get();
 

        if (sizeof($existe) == 0) {
            
            $alluser = array();
            $alluser = $request->idsParticipantes;
            for ($i=0; $i < count($alluser); $i++) { 
            $score = new Score;
            $score["idScore"] = Uuid::uuid();
            $score["roomID"] = $request->roomID;
            $score["idUser"] = $alluser[$i];
            $score["score"] = 0;
            $score["isActive"] = true;
            $score->save();
            }
    
            return response()->json([
                'message'  => "se inicializaron los campos en score"
              ]);
        }else if (sizeof($existe) !== 0) 
        {
          for ($i=0; $i < count($existe); $i++) { 
            array_push($idsJugando,$existe[$i]->idUser);
          }

          //buscamos ids diferentes entre los dos array para insertarlo a la tabla de scores
          //con array_values quitamos los indices por que aparecian apartir de 1 o 2
          $idsEntrantes = array_values(array_diff($idsParticipantes, $idsJugando));


          if (count($idsEntrantes) > 0) {

            for ($i=0; $i < count($idsEntrantes) ; $i++) { 
              $score = new Score;
              $score["idScore"] = Uuid::uuid();
              $score["roomID"] = $request->roomID;
              $score["idUser"] = $idsEntrantes[$i];
              $score["score"] = 0;
              $score["isActive"] = true;
              $score->save();
            }
          return response()->json([
            'message'  => "se inserto un nuevo jugador",
            '$idEntrante' => $idsEntrantes
          ]);

          }

          if (count($idsEntrantes) == 0) {
            return response()->json([
              'message'  => "ya existe un registro",
              '$idsParticipantes' => $idsJugando,
              '$existe' => $existe,
              '$request->idsParticipantes' =>$idsParticipantes
            ]);
          }
        }
}

public function getScore(Request $request){

    $scors = DB::table('scores')
    ->join('users', 'users.id', '=', 'scores.idUser')
    ->select('users.customName','scores.score','scores.idUser')
    ->where('scores.roomID', $request->roomID)
    ->orderBy('scores.score', 'desc')
    ->get();
    return response()->json([
    'scors' => $scors
    ]);
}

public function getIdUserScore(Request $request){

  $scors = DB::table('scores')
  ->join('users', 'users.id', '=', 'scores.idUser')
  ->select('users.id')
  ->where('scores.roomID', $request->roomID)
  ->get();
  return response()->json([
  'scors' => $scors
  ]);
}


}
