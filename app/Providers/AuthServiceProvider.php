<?php

namespace App\Providers;

use App\Models\Users;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Fortify\Fortify;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Fortify::loginView(function () {
            return file_exists(resource_path('views/auth/login.blade.php'))
                ? view('auth.login')
                : view('orchid-fortify::auth.login');
        });

        Gate::define(\App\Models\Permissions::PERMISSION_ADD_FILMS, function (Users $user) {
            return (new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS)
                ? Response::allow()
                : Response::denyAsNotFound();
        });
    }
}
