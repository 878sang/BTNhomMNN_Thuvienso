<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$table = 'contact_messages';
if (Schema::hasTable($table)) {
    $columns = Schema::getColumnListing($table);
    echo "Columns in $table: " . implode(', ', $columns) . "\n";
} else {
    echo "Table $table does not exist.\n";
}
