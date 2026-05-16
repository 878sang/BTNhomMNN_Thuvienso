<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$table = 'contact_messages';
if (Schema::hasTable($table)) {
    if (!Schema::hasColumn($table, 'subject')) {
        Schema::table($table, function (Blueprint $table) {
            $table->string('subject')->after('email')->nullable();
        });
        echo "Column 'subject' added successfully.\n";
    } else {
        echo "Column 'subject' already exists.\n";
    }
} else {
    echo "Table $table does not exist.\n";
}
