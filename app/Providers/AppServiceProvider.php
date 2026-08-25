<?php

namespace App\Providers;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Tenant\Tenant;
use App\Models\Transaction\Subscription;
use App\Models\Transaction\Payment;
use App\Observers\PaymentObserver;
use App\Observers\TenantObserver;

use App\Policies\AccessControl\RolePolicy;
use App\Policies\AccessControl\UserPolicy;
use App\Policies\Tenant\TenantPolicy;
use App\Policies\Transaction\SubscriptionPolicy;

use App\Providers\RegisterService\RegisterAuthService;
use App\Providers\RegisterService\RegisterPackageService;
use App\Providers\RegisterService\RegisterPermissionService;
use App\Providers\RegisterService\RegisterRoleService;
use App\Providers\RegisterService\RegisterSystemService;
use App\Providers\RegisterService\RegisterTenantCategoryService;
use App\Providers\RegisterService\RegisterTenantService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Fluent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->register(RegisterRoleService::class);
        $this->app->register(RegisterAuthService::class);
        $this->app->register(RegisterPermissionService::class);
        $this->app->register(RegisterSystemService::class);
        $this->app->register(RegisterTenantService::class);
        $this->app->register(RegisterTenantCategoryService::class);
        $this->app->register(RegisterPackageService::class);
    }

    public function boot(): void
    {

        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Tenant::class, TenantPolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);

        Payment::observe(PaymentObserver::class);
        Tenant::observe(TenantObserver::class);

        Blueprint::macro('epochTimestamps', function () {
            $this->bigInteger('created_at')->nullable();
            $this->bigInteger('updated_at')->nullable();
        });

        Blueprint::macro('epochSoftDeletes', function () {
            $this->bigInteger('deleted_at')->nullable();
        });

        Blueprint::macro('userFootprints', function () {
            $this->integer('created_by')->nullable();
            $this->integer('updated_by')->nullable();
            $this->integer('deleted_by')->nullable();
        });

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

    protected function registerService(string $serviceName, string $className)
    {
        $this->app->singleton($serviceName, function () use ($className) {
            return new $className;
        });
    }
}
