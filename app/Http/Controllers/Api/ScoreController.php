<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Score;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function makeScore(Request $request){
         
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

}
