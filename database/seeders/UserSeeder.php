<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'dashboard', 'description' => 'จัดการหน้า Dashboard']);
        Permission::create(['name' => 'banner', 'description' => 'จัดการหน้าแบนเนอร์']);
        Permission::create(['name' => 'news', 'description' => 'จัดการหน้าข่าวสาร']);
        Permission::create(['name' => 'promotion', 'description' => 'จัดการหน้าโปรโมชั่น']);
        Permission::create(['name' => 'service', 'description' => 'จัดการหน้าบริการของเรา']);
        Permission::create(['name' => 'product', 'description' => 'จัดการหน้าสินค้า']);
        Permission::create(['name' => 'faq', 'description' => 'จัดการหน้าถาม-ตอบ']);
        Permission::create(['name' => 'settings', 'description' => 'จัดการหน้าตั้งค่าทั่วไป']);
        Permission::create(['name' => 'website', 'description' => 'จัดการหน้าเว็บไซต์']);
        Permission::create(['name' => 'user', 'description' => 'จัดการหน้าผู้ใช้งาน']);
        Permission::create(['name' => 'role', 'description' => 'จัดการหน้าบทบาท']);
        Permission::create(['name' => 'permission', 'description' => 'จัดการหน้าสิทธิ์การใช้งาน']);
        Permission::create(['name' => 'english-language', 'description' => 'ภาษาอังกฤษ']);

        $superadmin_role = Role::create(['name' => 'superadmin']);
        $superadmin_role->syncPermissions(['dashboard','banner','news','promotion','service','product','faq','settings','website','user', 'role', 'permission','english-language']);

        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->syncPermissions(['dashboard','banner','news','promotion','service','product','faq','settings','english-language']);

        $user_role = Role::create(['name' => 'user']);
        $user_role->syncPermissions(['dashboard','settings']);


        $user1 = \App\Models\User::factory()->create([
            'name' => 'ศุภมิตร',
            'username' => 'supamit',
            'email' => 'supamit.ja@gmail.com',
            'status' => 1,
            'password' => bcrypt('123456789'),
        ]);

        $user2 = \App\Models\User::factory()->create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'status' => 1,
            'password' => bcrypt('password'),
        ]);

        $user3 = \App\Models\User::factory()->create([
            'name' => 'user',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'status' => 1,
            'password' => bcrypt('password'),
        ]);

        $user1->assignRole($superadmin_role);
        $user2->assignRole($admin_role);
        $user3->assignRole($user_role);
    }
}
