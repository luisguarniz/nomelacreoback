<?php

namespace App\Http\Controllers\Api;

use App\Events\GameEvent;
use App\Events\messageChangeName;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function unblock(Request $request){
        // $data = $request->only(['msgUnblock','codigoSesion','to']);
 
         event(new GameEvent($request));
      
         return response()->json([
             'ok'  => true,
             'message' => 'mensaje enviado correctamente',
         ]);
     }
 
     public function changeIcon(Request $request){
  
          event(new GameEvent($request));
       
          return response()->json([
              'ok'  => true,
              'message' => 'mensaje para cambiar icono enviado correctamente',
              'msgvoto' => $request->msgvoto,
              'to' => $request->to
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


