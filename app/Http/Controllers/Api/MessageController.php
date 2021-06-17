<?php

namespace App\Http\Controllers\Api;

use App\Events\GameEvent;
use App\Events\messageChangeName;
use App\Events\noMelacreo;
use App\Events\observador;
use App\Events\siMelacreo;
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

     public function siMelacreo(Request $request){
        // $data = $request->only(['msgUnblock','codigoSesion','to']);
 
         event(new siMelacreo($request));
      
         return response()->json([
             'ok'  => true,
             'message' => 'mensaje enviado correctamente noMelacreo Web socket',
         ]);
     }

     public function noMelacreo(Request $request){
 
         event(new noMelacreo($request));
      
         return response()->json([
             'ok'  => true,
             'message' => 'mensaje enviado correctamente siMelacreo Web socket',
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

      public function observador(Request $request){
 
         event(new observador($request));
      
         return response()->json([
             'ok'  => true,
             'message' => 'mensaje enviado correctamente observador',
         ]);
     }
}


