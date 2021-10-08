<?php
 namespace App\Http\Controllers\Admin;
 use Illuminate\Support\Facades\Artisan;
 use Backpack\CRUD\app\Http\Controllers\CrudController;

 class BackupController extends CrudController{

    public function downloadDb(){
        Artisan::call('backup:run');
        // $path = storage_path('app/laravel-backup/*');
        // $latest_ctime = 0;
        // $latest_filename = '';
        // $files = glob($path);
        // foreach($files as $file)
        // {
        //         if (is_file($file) && filectime($file) > $latest_ctime)
        //         {
        //                 $latest_ctime = filectime($file);
        //                 $latest_filename = $file;
        //         }
        // }
        // return response()->download($latest_filename);
    }
}