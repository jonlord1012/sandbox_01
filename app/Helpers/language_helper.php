<?php
if (!function_exists('lang')) {
   /**
    * Get translated string with fallback
    */
   function lang(string $line, array $args = [], string $locale = null): string
   {
      $language = service('language');
      $locale = $locale ?? $language->getLocale();

      // Try to get the translation
      $translation = lang($line, $args, $locale);

      // If translation not found, return the original key
      if ($translation === $line) {
         // Log missing translations in development
         if (ENVIRONMENT === 'development') {
            log_message('debug', "Missing translation: {$line} for locale: {$locale}");
         }
         return $line;
      }

      return $translation;
   }
}

if (!function_exists('current_language')) {
   /**
    * Get current language code
    */
   function current_language(): string
   {
      return service('language')->getLocale();
   }
}

if (!function_exists('available_languages')) {
   /**
    * Get available languages
    */
   function available_languages(): array
   {
      return [
         'en' => ['name' => 'English', 'flag' => 'us'],
         'id' => ['name' => 'Bahasa Indonesia', 'flag' => 'id']
      ];
   }
}