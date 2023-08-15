<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use File;

class R0D0 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'r0d0';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('up');
        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
        Schema::disableForeignKeyConstraints();
        $sql = "";
        foreach ($tableNames as $name) {
            $sql.="drop table ".$name."; ";
        }
        if($sql != ""){
            DB::unprepared($sql);
        }
        $path = storage_path('app/backup.sql');
        DB::unprepared(file_get_contents($path));
        Schema::enableForeignKeyConstraints();
        File::copyDirectory(storage_path('app/uploads'), public_path('/uploads'));
        File::copyDirectory(storage_path('app/uploads_demo'), public_path('/uploads_demo'));
        echo now()."\n";
    }
}
