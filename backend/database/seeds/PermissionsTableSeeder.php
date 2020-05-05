<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('DELETE FROM permissions');
        \DB::statement('ALTER TABLE permissions AUTO_INCREMENT = 1;');

        \DB::table('permissions')->insert([
            'name'=>"DASHBOARD_SHOW",
            'guard_name'=>'api',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);

        $modules = [             
            'SPMB-PMB',     
            'SYSTEM-SETTING-PERMISSIONS',
            'SYSTEM-SETTING-ROLES',
            'SYSTEM-USERS-SUPERADMIN',
            'SYSTEM-USERS-AKADEMIK',
            'SYSTEM-USERS-PMB',
            'SYSTEM-USERS-KEUANGAN',
            'SYSTEM-USERS-PERPUSTAKAAN',
            'SYSTEM-USERS-LPPM',
            'SYSTEM-USERS-PUSLAHTA',
            'SYSTEM-USERS-DOSEN',
            'SYSTEM-USERS-DOSEN WALI',
            'SYSTEM-USERS-MAHASISWA',
            'SYSTEM-USERS-MAHASISWA BARU',
            'SYSTEM-USERS-ALUMNI',
            'SYSTEM-USERS-ORANG TUA WALI',            
        ];
        $records=[];
        foreach($modules as $v)
        {
            $records=array(
                ['name'=>"{$v}_BROWSE",'guard_name'=>'api','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
                ['name'=>"{$v}_SHOW",'guard_name'=>'api','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
                ['name'=>"{$v}_STORE",'guard_name'=>'api','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
                ['name'=>"{$v}_UPDATE",'guard_name'=>'api','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
                ['name'=>"{$v}_DESTROY",'guard_name'=>'api','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]                
            );            
            \DB::table('permissions')->insert($records);
        }        
        
        \DB::table('permissions')->insert([
            'name'=>"SPMB-GROUP",
            'guard_name'=>'api',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);

        \DB::table('permissions')->insert([
            'name'=>"SYSTEM-SETTING-GROUP",
            'guard_name'=>'api',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);

        \DB::table('permissions')->insert([
            'name'=>"SYSTEM-USERS-GROUP",
            'guard_name'=>'api',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);                

        \DB::table('permissions')->insert([
            'name'=>"USER_STOREPERMISSIONS",
            'guard_name'=>'api',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);                
        \DB::table('permissions')->insert([
            'name'=>"USER_REVOKEPERMISSIONS",
            'guard_name'=>'api',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();                
    }
}