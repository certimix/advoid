<?php

return [
    // Provedor de IA. Hoje: "anthropic" (Claude). Estruturado para plugar outros.
    'provider'   => env('AI_PROVIDER', 'anthropic'),
    'api_key'    => env('AI_API_KEY'),
    'model'      => env('AI_MODEL', 'claude-sonnet-4-5'),
    'base_url'   => env('AI_BASE_URL', 'https://api.anthropic.com/v1'),
    'max_tokens' => (int) env('AI_MAX_TOKENS', 2048),
];
