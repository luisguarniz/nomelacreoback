<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status_game;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class Status_GameController extends Controller
{
    public function makeStatus(Request $request)
    {

        $idUser = User::where('users.id', $request->idUser)->first();
        if ($idUser->isAdmin == true) {
            $newStatusAdmin = new Status_game;
            $newStatusAdmin["idStatus"] = Uuid::uuid();
            $newStatusAdmin["idSessionGame"] = $request->idSessionGame;
            $newStatusAdmin["idUser"] = $request->idUser;
            $newStatusAdmin["elegirColor"] = $request->elegirColor;
            $newStatusAdmin["noMelacreo"] = $request->noMelacreo;
            $newStatusAdmin["siMelacreo"] = $request->siMelacreo;
            $newStatusAdmin["masoCartas"] = $request->masoCartas;
            $newStatusAdmin["cartasMesa"] = $request->cartasMesa;
            $newStatusAdmin["resetGame"] = $request->resetGame;
            $newStatusAdmin->save();

            return response()->json([
                'messagge' => "se crearon los estados para un admin"
            ]);
        }
        if ($idUser->isInvited == true) {
            $newStatusInvited = new Status_game;
            $newStatusInvited["idStatus"] = Uuid::uuid();
            $newStatusInvited["idSessionGame"] = $request->idSessionGame;
            $newStatusInvited["idUser"] = $request->idUser;
            $newStatusInvited["elegirColor"] = $request->elegirColor;
            $newStatusInvited["noMelacreo"] = $request->noMelacreo;
            $newStatusInvited["siMelacreo"] = $request->siMelacreo;
            $newStatusInvited["masoCartas"] = $request->masoCartas;
            $newStatusInvited["cartasMesa"] = $request->cartasMesa;
            $newStatusInvited["resetGame"] = $request->resetGame;
            $newStatusInvited->save();

            return response()->json([
                'messagge' => "se crearon los estados para un invitado"
            ]);
        }
    }

    public function getStatus(Request $request)
    {

        $status = Status_game::where('status_games.idUser', $request->idUser)
            //->where('status_games.idSessionGame', $request->idSessionGame)
            ->first();

        return response()->json([
            'status' => $status
        ]);

    }

    public function PressNomelacreo(Request $request)
    {

        Status_game::where('status_games.idSessionGame', $request->idSessionGame)
            ->where('status_games.idUser', $request->idUser)
            ->update([
                'elegirColor' => $request->elegirColor,
                'noMelacreo' => $request->noMelacreo,
                'siMelacreo' => $request->siMelacreo,
                'masoCartas' => $request->masoCartas,
                'cartasMesa' => $request->cartasMesa,
                'resetGame' => $request->resetGame,
            ]);

            return response()->json([
                'messagge' => "se actualizaron los estados despues de presionar Nomelacreo"
            ]);
    }
    public function PressSimelacreo(Request $request)
    {

        Status_game::where('status_games.idSessionGame', $request->idSessionGame)
            ->where('status_games.idUser', $request->idUser)
            ->update([
                'elegirColor' => $request->elegirColor,
                'noMelacreo' => $request->noMelacreo,
                'siMelacreo' => $request->siMelacreo,
                'masoCartas' => $request->masoCartas,
                'cartasMesa' => $request->cartasMesa,
                'resetGame' => $request->resetGame,
            ]);

            return response()->json([
                'messagge' => "se actualizaron los estados despues de presionar Simelacreo"
            ]);
    }
    public function PressMasoCartas(Request $request)
    {

        Status_game::where('status_games.idSessionGame', $request->idSessionGame)
            ->where('status_games.idUser', $request->idUser)
            ->update([
            //    'elegirColor' => $request->elegirColor,
                'siMelacreo' => $request->siMelacreo,
                'masoCartas' => $request->masoCartas,
                'cartasMesa' => $request->cartasMesa,
              //  'resetGame' => $request->resetGame,
            ]);
            return response()->json([
                'messagge' => "se actualizaron los estados despues de presionar el maso de cartas"
            ]);
    }
    public function PressElegirColor(Request $request)
    {
        //la variable $request->masoCartas estaria trayendo un true y elegirColor false
        Status_game::where('status_games.idSessionGame', $request->idSessionGame)
            ->where('status_games.idUser', $request->idUser)
            ->update([
                'masoCartas' => $request->masoCartas,
                
            ]);

            return response()->json([
                'messagge' => "se actualizaron los estados despues de presionar elegir color"
            ]);
    }
}
