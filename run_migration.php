<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Run migrations
$migrator = $app->make('migrator');
$migrator->run([database_path('migrations')]);

echo "Migrations completed!\n";
