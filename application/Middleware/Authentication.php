<?php

namespace Middleware;

use App\League\Models\Accounts;
use Mira\Render\Render;

class Authentication
{
      public function getCurrentUser(){
            $authcode = $_SESSION['authcode'];

            if ($authcode) {
                  return (new Accounts)->find("authcode = '$authcode'");
            }
      }

      public function login($username, $password)
      {
            $password = sha1($password);
            $find = (new Accounts)->find("username = '$username' AND password = '$password' ");
            if ($find->id) {
                  $unique_id = uniqid('', true).uniqid('', true);
                  $find->authcode = $unique_id;
                  $find->save();
                  $_SESSION['authcode'] = $unique_id;
                  return true;
            }
            return false;
      }

      public function logout()
      {
            session_destroy();

            Render::redirect('/login/');
      }
}
