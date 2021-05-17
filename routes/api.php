<?php

use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\Session_GameController;
use App\Http\Controllers\Api\Session_TurnController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Rutas del Host
Route::get("User/makeUser",[UserController::class, 'makeUser'])->name('User.makeUser');
Route::post("User/loginHost",[UserController::class, 'loginHost'])->name('api.auth.login');
Route::get('User/me',[UserController::class, 'me'])
->name('UserController.me')
->middleware('auth:api');
Route::put("User/editNameUser",[UserController::class, 'editNameUser'])->name('user.editNameUser');

//Rutas del Invited
Route::get("User/makeInvited",[UserController::class, 'makeInvited'])->name('User.makeInvited');

//Rutas del Room
Route::post("Room/makeRoom",[RoomController::class, 'makeRoom'])->name('Room.makeRoom');
Route::put("Room/desactivateRoom",[RoomController::class, 'desactivateRoom'])->name('Room.desactivateRoom');
Route::get("Room/getRoomInvited/{roomCode}",[RoomController::class, 'getRoomInvited'])->name('Room.getRoomInvited');
Route::get("Room/getRoomhost/{roomCode}",[RoomController::class, 'getRoomhost'])->name('Room.getRoomhost');
Route::post("Room/makeStatus",[RoomController::class, 'makeStatus'])->name('Room.makeStatus');

Route::put("Room/changeStatusCartas",[RoomController::class, 'changeStatusCartas'])->name('Room.changeStatusCartas');
Route::get("Room/getStatusCartas/{roomCode}",[RoomController::class, 'getStatusCartas'])->name('Room.getStatusCartas');

Route::put("Room/changeStatusbtnVoting",[RoomController::class, 'changeStatusbtnVoting'])->name('Room.changeStatusbtnVoting');
Route::get("Room/getStatusbtnVoting/{roomCode}",[RoomController::class, 'getStatusbtnVoting'])->name('Room.getStatusbtnVoting');

Route::put("Room/changeStatusbtnStopVoting",[RoomController::class, 'changeStatusbtnStopVoting'])->name('Room.changeStatusbtnStopVoting');
Route::get("Room/getStatusbtnStopVoting/{roomCode}",[RoomController::class, 'getStatusbtnStopVoting'])->name('Room.getStatusbtnStopVoting');


//Rutas de las cartas
Route::get("Card/getCard/{idSessionGame}",[CardController::class, 'getCard'])->name('Card.getCard');
Route::post("Card/moveCard",[CardController::class, 'moveCard'])->name('Card.moveCard');

//Rutas de la session_game
Route::get("Session_game/makeSession/{roomID}",[Session_GameController::class, 'makeSession'])->name('Session_game.makeSession');

//Rutas de Session_Turn
Route::post("Session_turn/makeSessionTurn",[Session_TurnController::class, 'makeSessionTurn'])->name('Session_turn.makeSessionTurn');

//ruta para enviar true cuando se mueve una carta
Route::post('Message/unblock',[MessageController::class, 'unblock'])
->name('MessageController.unblock')
->middleware('auth:api');