<?php

namespace App\Providers\AI;

use App\Models\AIProfile;

interface AIProviderInterface
{
    public function scan(AIProfile $profile, string $prompt): array;
}
