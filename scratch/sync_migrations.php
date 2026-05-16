<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

$migrationFiles = File::files(database_path('migrations'));
$ranMigrations = DB::table('migrations')->pluck('migration')->toArray();

$batch = DB::table('migrations')->max('batch') ?: 0;
$batch++;

foreach ($migrationFiles as $file) {
    $name = $file->getBasename('.php');
    if (in_array($name, $ranMigrations)) continue;

    echo "Checking $name...\n";
    $shouldFake = false;

    // Logic to determine if migration is already applied
    if (str_contains($name, 'create_users_table')) $shouldFake = Schema::hasTable('users');
    else if (str_contains($name, 'create_cache_table')) $shouldFake = Schema::hasTable('cache');
    else if (str_contains($name, 'create_jobs_table')) $shouldFake = Schema::hasTable('jobs');
    else if (str_contains($name, 'create_authors_table')) $shouldFake = Schema::hasTable('authors');
    else if (str_contains($name, 'create_categories_table')) $shouldFake = Schema::hasTable('categories');
    else if (str_contains($name, 'create_publishers_table')) $shouldFake = Schema::hasTable('publishers');
    else if (str_contains($name, 'create_books_table')) $shouldFake = Schema::hasTable('books');
    else if (str_contains($name, 'create_comments_table')) $shouldFake = Schema::hasTable('comments');
    else if (str_contains($name, 'create_points_transactions_table')) $shouldFake = Schema::hasTable('points_transactions');
    else if (str_contains($name, 'create_ratings_table')) $shouldFake = Schema::hasTable('ratings');
    else if (str_contains($name, 'create_settings_table')) $shouldFake = Schema::hasTable('settings');
    else if (str_contains($name, 'create_sliders_table')) $shouldFake = Schema::hasTable('sliders');
    else if (str_contains($name, 'add_fields_to_users_table')) $shouldFake = Schema::hasColumn('users', 'points'); // example field
    else if (str_contains($name, 'create_book_user_table')) $shouldFake = Schema::hasTable('book_user');
    else if (str_contains($name, 'create_favorites_table')) $shouldFake = Schema::hasTable('favorites');
    else if (str_contains($name, 'create_activity_logs_table')) $shouldFake = Schema::hasTable('activity_logs');
    else if (str_contains($name, 'create_book_interactions_tables')) $shouldFake = Schema::hasTable('book_downloads'); // example from that file
    else if (str_contains($name, 'create_contact_messages_table')) $shouldFake = Schema::hasTable('contact_messages');
    else if (str_contains($name, 'add_comment_to_ratings_table')) $shouldFake = Schema::hasColumn('ratings', 'comment');
    else if (str_contains($name, 'add_subject_to_contact_messages_table')) $shouldFake = Schema::hasColumn('contact_messages', 'subject');
    else if (str_contains($name, 'add_page_count_to_books_table')) $shouldFake = Schema::hasColumn('books', 'page_count');

    if ($shouldFake) {
        DB::table('migrations')->insert(['migration' => $name, 'batch' => $batch]);
        echo "Faked $name (Already applied to DB)\n";
    } else {
        echo "Migration $name is genuinely pending.\n";
    }
}
