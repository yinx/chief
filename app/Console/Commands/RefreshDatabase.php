<?php

namespace App\Console\Commands;

use Chief\Articles\Article;
use Chief\Authorization\Permission;
use Chief\Authorization\Role;
use Chief\Users\User;
use Chief\Authorization\AuthorizationDefaults;

class RefreshDatabase extends BaseCommand
{
    protected $signature = 'chief:db-refresh {--force}';
    protected $description = 'This will clear the entire database and reseed with development defaults';

    public function handle()
    {
        if(app()->environment() != 'local' && !$this->option('force'))
        {
            throw new \Exception('Aborting. This command is dangerous and only meant for your local environment.');
        }

        if(app()->environment() != 'local' && $this->option('force'))
        {
            if (!$this->confirm('You are about to force refresh the database in the '.app()->environment().' environment! ARE YOU SURE?')) {
                $this->info('aborting.');
                return;
            }

            if (!$this->confirm('ARE YOU REALLY SURE?')) {
                $this->info('pfew.');
                return;
            }
        }

        if($this->option('force')){
            $this->call('migrate:refresh', ['--force' => true]);
        }else{
            $this->call('migrate:refresh');
        }

        $this->settingPermissionsAndRoles();
        $this->settingUsers();

        $this->info('Scaffolding some entries...');
        factory(User::class, 10)->create();
        factory(Article::class, 10)->create();

        $this->info('Great. We\'re done here. NOW START HACKING!');
    }

    private function settingPermissionsAndRoles()
    {
        AuthorizationDefaults::permissions()->each(function($permissionName){
            Permission::firstOrCreate(['name' => $permissionName]);
        });

        AuthorizationDefaults::roles()->each(function($defaultPermissions, $roleName){

            $role = Role::firstOrCreate(['name' => $roleName]);
            $defaultPermissions = collect($defaultPermissions);

            if($defaultPermissions->first() == '*'){
                $role->syncPermissions(Permission::all());
            } else{
                $defaultPermissionPattern = $defaultPermissions->map(function($entry){
                    return str_replace('*', '(.*)', $entry);
                })->implode('|');

                $role->syncPermissions(Permission::whereRaw('name REGEXP "' . $defaultPermissionPattern.'"')->get());
            }
        });

        $this->info('Default permissions and roles');
    }

    private function settingUsers()
    {
        /**
         * The developer who is scaffolding this data is in charge of picking the default
         * password. This password is set for all dev accounts. On a staging or production
         * environment there should be an user invite sent instead.
         */
        $this->info('Now please set one password for all dev accounts.');
        $password = $this->askPassword();

        $admins = collect([
            ['Philippe', 'Damen', 'philippe@thinktomorrow.be', $password],
            ['Bob', 'Dries', 'bob@thinktomorrow.be', $password],
            ['Ben', 'Cavens', 'ben@thinktomorrow.be', $password],
            ['Johnny', 'Berkmans', 'johnny@thinktomorrow.be', $password],
            ['Json', 'Voorhees', 'json@thinktomorrow.be', $password],
        ]);

        $admins->each(function($admin){
            $this->createUser($admin[0], $admin[1], $admin[2], $admin[3], 'developer');
            $this->info('Added '.$admin[0].' as developer role with your provided password.');
        });
    }

}
