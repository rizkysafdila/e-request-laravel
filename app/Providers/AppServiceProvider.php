<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Request as RequestModel;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Can manage request (edit, delete) — only admin or owner while still draft
         */
        Gate::define('request.manage', fn(User $user, RequestModel $req) =>
            $user->isAdmin() || ($user->id === $req->created_by && $req->status === 'draft')
        );

        /**
         * Can submit draft request — only creator when draft
         */
        Gate::define('request.submit', fn(User $user, RequestModel $req) =>
            $user->id === $req->created_by && $req->status === 'draft'
        );

        /**
         * Can approve or reject — approver or admin
         */
        Gate::define('request.approve', fn(User $user) =>
            $user->isApprover() || $user->isAdmin()
        );

        /**
         * Can view deleted requests — admin only
         */
        Gate::define('request.trash.view', fn(User $user) =>
            $user->isAdmin()
        );

        /**
         * Can restore deleted requests — admin only
         */
        Gate::define('request.restore', fn(User $user, RequestModel $req) =>
            $user->isAdmin() && $req->trashed()
        );
    }
}
