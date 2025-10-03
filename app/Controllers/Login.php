<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Controllers\LoginController as ShieldLogin;

class Login extends ShieldLogin
{
   public function loginView()
   {
      if (auth()->loggedIn()) {
         return redirect()->to(config('Auth')->loginRedirect());
      }

      /** @var Session $authenticator */
      $authenticator = auth('session')->getAuthenticator();

      // If an action has been defined, start it up.
      if ($authenticator->hasAction()) {
         return redirect()->route('auth-action-show');
      }

      // Return your custom view with your layout
      return view('Shield/login');
   }
   public function loginAction(): RedirectResponse
   {
      // Override validation rules before calling parent
      $validation = service('validation');
      $validation->setRules([
         'username' => 'required|max_length[50]',
         'password' => 'required',
      ]);
      // Validate the request
      if (!$validation->withRequest($this->request)->run()) {
         return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      }

      // This will handle the form submission using Shield's logic
      return parent::loginAction();
   }
}