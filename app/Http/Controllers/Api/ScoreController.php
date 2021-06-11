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
         
        $existe = Score::where('roomID', $request->roomID)->first();

        if ($existe == null) {
            
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
        }
          return response()->json([
            'message'  => "ya existe un registro"
          ]);
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



}
