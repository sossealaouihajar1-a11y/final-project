<?php

return [
    // Stateless API - using Bearer tokens only
    'stateful' => [],
    
    // Sanctum's Guard will check these guards in order for tokens
    // We disable stateful so it won't try to check session
    'guard' => [],
    'expiration' => null,
    'token_prefix' => '',
    
    // No session middleware for stateless API
    'middleware' => [],
];