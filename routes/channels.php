<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
                        
Broadcast::channel('channel-test', function ($user){
    return $user;
});


Broadcast::channel('room.{id}', function ($room) {
    return $room;
});

Broadcast::channel('startGame.{id}', function ($startGame) {
    return $startGame;
});
Broadcast::channel('resetGame.{id}', function ($resetGame) {
    return $resetGame;
});

Broadcast::channel('siMelacreo.{id}', function ($siMelacreo) {
    return $siMelacreo;
});

Broadcast::channel('noMelacreo.{id}', function ($noMelacreo) {
    return $noMelacreo;
});

// ruta para cambiar nombre
Broadcast::channel('changeName.{id}', function ($changeName) {
    return $changeName;
});


Broadcast::channel('observador.{id}', function ($observador) {
    return $observador;
});

// TODO: validar existencia del usuario
Broadcast::channel('votation.{id}', function ($user, $id) {
   return (int) $user->AdminUserCode === (int) $id;
       // return $user;
});