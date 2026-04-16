<?php

namespace App\Providers;

use App\Models\Auth\Permission;
use App\Providers\RegisterService\RegisterAuthService;
use App\Providers\RegisterService\RegisterPermissionService;
use App\Providers\RegisterService\RegisterRoleService;
use App\Providers\RegisterService\RegisterSystemService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Fluent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->register(\App\Providers\RegisterService\RegisterRoleService::class);

        $this->app->register(RegisterRoleService::class);
        $this->app->register(RegisterAuthService::class);
        $this->app->register(RegisterPermissionService::class);
        $this->app->register(RegisterSystemService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom macro for created_at and updated_at as Epoch
        Blueprint::macro('epochTimestamps', function () {
            $this->unsignedBigInteger('created_at')->nullable();
            $this->unsignedBigInteger('updated_at')->nullable();
        });

        // Custom macro for deleted_at as Epoch
        Blueprint::macro('epochSoftDeletes', function () {
            $this->unsignedBigInteger('deleted_at')->nullable();
        });

        // Custom macro for user footprints
        Blueprint::macro('userFootprints', function () {
            $this->integer('created_by')->nullable();
            $this->integer('updated_by')->nullable();
            $this->integer('deleted_by')->nullable();
        });

        // Custom macro for unique constraint that ignores soft-deleted records
        Blueprint::macro('uniqueSoftDelete', function ($columns) {
            $table = $this->getTable();

            $columnsArray = (array) $columns;

            $columnStringForName = implode('_', $columnsArray);
            $indexName = "{$table}_{$columnStringForName}_unique_active";

            $columnStringForSql = implode(', ', $columnsArray);

            return $this->addCommand('uniqueSoftDelete', compact('indexName', 'table', 'columnStringForSql'));
        });

        PostgresGrammar::macro('compileUniqueSoftDelete', function (Blueprint $blueprint, Fluent $command) {
            return "CREATE UNIQUE INDEX {$command->indexName} ON {$command->table} ({$command->columnStringForSql}) WHERE deleted_at IS NULL";
        });

        // Permission System
        $this->registerPermissionsToGates();
    }

    protected function registerPermissionsToGates(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        if (! Schema::hasTable('auth_permissions')) {
            return;
        }

        try {
            Permission::get(['code'])->each(function ($permission) {
                Gate::define($permission->code, function ($user) use ($permission) {
                    return $user->roleUser->role && $user->roleUser->role->permissions->contains('code', $permission->code);
                });
            });
        } catch (\Exception $e) {
        }
    }

    protected function registerService($serviceName, $className)
    {
        $this->app->singleton($serviceName, function () use ($className) {
            return new $className;
        });
    }
}
