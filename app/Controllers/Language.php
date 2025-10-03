<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Language extends Controller
{
   public function switch($locale)
   {
      $supportedLocales = ['en', 'id'];

      if (!in_array($locale, $supportedLocales)) {
         $locale = 'en';
      }

      // Set language in session
      session()->set('language', $locale);

      // Also set the locale for the current request
      service('request')->setLocale($locale);

      // Redirect back to previous page
      return redirect()->back();
   }
}