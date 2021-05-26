<?php

use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\Api\Session_GameController;
use App\Http\Controllers\Api\Session_TurnController;
use App\Http\Controllers\Api\Status_GameController;
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
Route::get("User/isAdmin/{id}",[UserController::class, 'isAdmin'])->name('User.isAdmin');
Route::post("User/loginHost",[UserController::class, 'loginHost'])->name('api.auth.login');
Route::get("User/getAdmin/{roomID}",[UserController::class, 'getAdmin'])->name('User.getAdmin');
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
Route::get("Card/getOrderCards/{idSessionGame}",[CardController::class, 'getOrderCards'])->name('Card.getOrderCards');
Route::post("Card/makeOrderCard",[CardController::class, 'makeOrderCard'])->name('Card.makeOrderCard');
Route::put("Card/compareCards",[CardController::class, 'compareCards'])->name('Card.compareCards');

//Rutas de la session_game 
Route::get("Session_game/makeSession/{roomID}",[Session_GameController::class, 'makeSession'])->name('Session_game.makeSession');
Route::get("Session_game/makeGame/{idSessionGame}",[Session_GameController::class, 'makeGame'])->name('Session_game.makeGame');
Route::get("Session_game/updateStatusCardInicio/{idSessionGame}",[Session_GameController::class, 'updateStatusCardInicio'])->name('Session_game.updateStatusCardInicio');


//Rutas de Session_Turn
Route::post("Session_turn/makeSessionTurn",[Session_TurnController::class, 'makeSessionTurn'])->name('Session_turn.makeSessionTurn');
Route::put("Session_turn/changeTurn",[Session_TurnController::class, 'changeTurn'])->name('Session_turn.changeTurn');
Route::get("Session_turn/getTurn/{idSessionGame}",[Session_TurnController::class, 'getTurn'])->name('Session_turn.getTurn');

//Rutas de Status_game
Route::post("Status_game/makeStatus",[Status_GameController::class, 'makeStatus'])->name('Status_game.makeStatus');
Route::post("Status_game/getStatus",[Status_GameController::class, 'getStatus'])->name('Status_game.getStatus');
Route::put("Status_game/PressNomelacreo",[Status_GameController::class, 'PressNomelacreo'])->name('Status_game.PressNomelacreo');
Route::put("Status_game/PressSimelacreo",[Status_GameController::class, 'PressSimelacreo'])->name('Status_game.PressSimelacreo');
Route::put("Status_game/PressMasoCartas",[Status_GameController::class, 'PressMasoCartas'])->name('Status_game.PressMasoCartas');
Route::put("Status_game/PressElegirColor",[Status_GameController::class, 'PressElegirColor'])->name('Status_game.PressElegirColor');
Route::put("Status_game/nextTurn",[Status_GameController::class, 'nextTurn'])->name('Status_game.nextTurn');

//rutas de Score
Route::post("Score/makeScore",[ScoreController::class, 'makeScore'])->name('Score.makeScore');
Route::get("Score/getScore/{roomID}",[ScoreController::class, 'getScore'])->name('Score.getScore');

//rutas para difundir mensajes en Web sockets
Route::post('Message/moveCard',[MessageController::class, 'moveCard'])
->name('MessageController.moveCard')
->middleware('auth:api');

Route::post('Message/startGame',[MessageController::class, 'startGame'])
->name('MessageController.startGame')
->middleware('auth:api');

Route::post('Message/siMelacreo',[MessageController::class, 'siMelacreo'])
->name('MessageController.siMelacreo')
->middleware('auth:api');

Route::post('Message/noMelacreo',[MessageController::class, 'noMelacreo'])
->name('MessageController.noMelacreo')
->middleware('auth:api');