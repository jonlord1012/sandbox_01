<?php

namespace App\Validation;

use CodeIgniter\Shield\Validation\ValidationRules as ShieldValidation;

class AuthValidation extends ShieldValidation
{
   public function getLoginRules(): array
   {
      return setting('Validation.login') ?? [
         // Your custom login rules - username only, no email
         'username' => $this->config->usernameValidationRules,
         'password' => $this->getPasswordRules(),
      ];
   }
}