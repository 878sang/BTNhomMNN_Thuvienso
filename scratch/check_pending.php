<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$checks = [
    'links' => 'table',
    'ratings' => ['column' => 'comment'],
    'books' => ['column' => 'page_count'],
    'contact_messages' => ['column' => 'subject'],
];

foreach ($checks as $item => $check) {
    if ($check === 'table') {
        echo "Table $item exists: " . (Schema::hasTable($item) ? 'YES' : 'NO') . "\n";
    } else {
        echo "$item has column {$check['column']}: " . (Schema::hasColumn($item, $check['column']) ? 'YES' : 'NO') . "\n";
    }
}
