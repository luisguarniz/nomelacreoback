<?php

namespace App\Http\Controllers\Api;

use App\Events\GameEvent;
use App\Events\messageChangeName;
use App\Events\startGame;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function moveCard(Request $request){
        // $data = $request->only(['msgUnblock','codigoSesion','to']);
 
         event(new GameEvent($request));
      
         return response()->json([
             'ok'  => true,
             'message' => 'mensaje enviado correctamente moveCard',
         ]);
     }
 
     public function startGame(Request $request){
        // $data = $request->only(['msgUnblock','codigoSesion','to']);
 
         event(new startGame($request));
      
         return response()->json([
             'ok'  => true,
             'message' => 'mensaje enviado correctamente startGame',
         ]);
     }
 
      public function changeName(Request $request){
         // $data = $request->only(['msgUnblock','codigoSesion','to']);
  
          event(new messageChangeName($request));
       
          return response()->json([
              'ok'  => true,
              'message' => 'mensaje para cambiar el nombre enviado correctamente',
          ]);
      }
}


