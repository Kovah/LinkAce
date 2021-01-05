<?php

// Check for a path in the environment variable $name_FILE.
// If it exist, return its contents - otherwise use the
// environment variable $name or the specified default
if(!function_exists('env_file')) {
  function env_file(string $name, ?string $defaultValue = null): ?string {
    $fileKey = $name . '_FILE';
    $filePath = env($fileKey);
    if (is_null($filePath)) {
      return env($name, $defaultValue);
    } else {
      return file_get_contents($filePath);
    }
  }
}

?>
