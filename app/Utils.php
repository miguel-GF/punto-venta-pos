<?php

namespace App;

class Utils
{
  public static function getUser()
  {
    $session = Session();
    $user = $session->get('user', null);
    return $user;
  }

  public static function getUserId()
  {
    $session = Session();
    $user = $session->get('user', null);
    return $user->usuario_id;
  }
}
