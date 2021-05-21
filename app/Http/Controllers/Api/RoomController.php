<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Room;
use Illuminate\Http\Request;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
      //este metodo espera el codigo y el token que devuelve la tabla user
  public function makeRoom(Request $request)
  {

    $ciudad = City::all()->random(); // con este metodo traigo un registro random
    $nomCiudad = $ciudad->cityName;
    $permitted_chars2 = '0123456789';
    $roomName = $nomCiudad . '-' . substr(str_shuffle($permitted_chars2), 0, 4);


    $permitted_chars3 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $roomCodeLetras = substr(str_shuffle($permitted_chars3), 0, 3);
    $permitted_chars4 = '0123456789';
    $roomCodeNumeros = substr(str_shuffle($permitted_chars4), 0, 3); //guardamos los caracteres aleatorios


    $newRoom = new Room;
    $newRoom->roomID = Uuid::uuid();
    $newRoom->idAdmin = $request->idAdmin; // es lo que traigo en los parametros de la funcion
    $newRoom->roomName = $roomName;
    $newRoom->roomCode = $roomCodeLetras . $roomCodeNumeros;
    $newRoom->save();


    //una ves creada la sala con save() pasamos a retornar un objeto a la vista
    $response['id'] = $newRoom->id;
    $response['roomID'] = $newRoom->roomID;
    $response['roomName'] = $newRoom->roomName;
    $response['roomCode'] = $newRoom->roomCode;
    $response['idAdmin'] = $newRoom->idAdmin;
    return $response;
  }


  public function desactivateRoom(Request $request)
  {

    $room = Room::where('idAdmin', $request->idAdmin)->update([
      'IsActive' => '0'
    ]);
  }

  public function getRoomInvited(Request $request)
  {
    $message = null;
    $room = Room::where('RoomCode',$request->roomCode)->first();

    if ($room == null) {
      return $message;
    }
    return response()->json([
      'roomID' => $room->roomID,
      'roomNameI' => $room->roomName,
      'roomCodeI' => $room->roomCode
    ]);
  }

  public function getRoomhost(Request $request)
  {
    $message = null;
    $room = Room::where('roomCode',$request->roomCode)->first();

    if ($room == null) {
      return $message;
    }
    return response()->json([

      'roomID'=>$room->roomID,
      'roomName' => $room->roomName,
      'roomCode' => $room->roomCode,
      'idAdmin'=> $room->idAdmin
    ]);
  }

/*
  public function makeStatus(Request $request)
  {
      $this->newStatu = new Statu();
      $this->newStatu->RoomCode = $request->RoomCode;
      $this->newStatu->save();

      return response()->json([
          'message' => "Se creo un registro para estado"
      ]);
  }
  */

/*  public function changeStatusCartas(Request $request)
  {


    $newName = Statu::where('status.RoomCode', $request->RoomCode)
      ->update([
        'bloquear' => $request->bloquear
      ]);

    return response()->json([
      'messagge' => "se modifico el estado"
    ]);
  }*/

 /* public function getStatusCartas(Request $request)
  {

        $query = DB::table('status')
            ->select('status.bloquear')
            ->where('status.RoomCode', $request->RoomCode)
            ->get();
         //   return $query;
            return response()->json([
              'bloquear' => $query
            ]);
  }*/


 /* public function changeStatusbtnVoting(Request $request)
  {


    $newName = Statu::where('status.RoomCode', $request->RoomCode)
      ->update([
        'StarVotingStatus' => $request->StarVotingStatus,
        'StopVotingStatus' => $request->StopVotingStatus
      ]);

    return response()->json([
      'messagge' => "se modifico el estado del boton star voting"
    ]);
  }
*/
 /* public function getStatusbtnVoting(Request $request)
  {

        $query = DB::table('status')
            ->select('status.StarVotingStatus')
            ->where('status.RoomCode', $request->RoomCode)
            ->get();
         //   return $query;
            return response()->json([
              'StarVotingStatus' => $query
            ]);
  }
*/
/*
  public function changeStatusbtnStopVoting(Request $request)
  {


    $newName = Statu::where('status.RoomCode', $request->RoomCode)
      ->update([
        'StopVotingStatus' => $request->StopVotingStatus,
        'StarVotingStatus' => $request->StarVotingStatus 
      ]);

    return response()->json([
      'messagge' => "se modifico el estado del boton Stop Voting"
    ]);
  }
*/
/*
  public function getStatusbtnStopVoting(Request $request)
  {

        $query = DB::table('status')
            ->select('status.StopVotingStatus')
            ->where('status.RoomCode', $request->RoomCode)
            ->get();
         //   return $query;
            return response()->json([
              'StopVotingStatus' => $query
            ]);
  }
  */
}
