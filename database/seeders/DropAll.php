<?php
//
//namespace Database\Seeders;
//
//use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Schema;
//
//class DropAll extends Seeder
//{
//    public function run()
//    {
//        $database = env('DB_DATABASE');
//
//        // Get all tables in the current database
//        $tables = DB::select('SHOW TABLES');
//
//        foreach ($tables as $table) {
//            // Get the table name dynamically
//            $tableName = $table->{'Tables_in_' . $database};
//
//            // Skip the 'roles' table
//            if ($tableName !== 'roles') {
//                // Drop the table if it exists
//                Schema::dropIfExists($tableName);
//                $this->command->info("Dropped table: {$tableName}");
//            } else {
//                $this->command->info("Skipped table: {$tableName}");
//            }
//        }
//    }
//}
