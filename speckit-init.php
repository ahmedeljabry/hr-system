<?php

/**
 * Minimal speckit init script — records initialization and confirms AI provider.
 */

$path = __DIR__ . DIRECTORY_SEPARATOR . '.speckit.json';
if (! file_exists($path)) {
    echo "Error: .speckit.json not found.\n";
    exit(1);
}

$json = json_decode(file_get_contents($path), true);
if (! $json) {
    echo "Error: invalid .speckit.json\n";
    exit(1);
}

echo "Speckit initialized here with AI provider: " . ($json['specify']['ai']['provider'] ?? 'unknown') . "\n";
echo "Run 'composer speckit:init' to re-run this check.\n";

exit(0);
