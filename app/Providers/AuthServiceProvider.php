<?php

namespace App\Providers;

use App\Policies\RolePermissionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use App\Models\Settings;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('role-permission', [RolePermissionPolicy::class, 'rolePermissionPolicy']);

        // Check auth hidepayment
        Gate::define('hide-payment', function ($user) {
            $list_allow_user = Settings::where('name', 'allow_hide_payment_user')->get();
            if(in_array($user->email, json_decode($list_allow_user[0]['value'], true))) {
                return true;
            }
            return false;
        });

        // Check id Icon check data
        // Settings::where('name', 'icon_management_check_role')->get();
        Gate::define('icon-check-data-permission', function($user) {
            $icon_check_roles = Settings::where('name', 'icon_management_check_role')->get();
            $role = Auth::user()->role_id;
            return in_array(Auth::user()->id, json_decode(@$icon_check_roles[0]['value']));
        });
        Gate::define('icon-approve-data-permission', function($user) {
            $icon_approved_roles = Settings::where('name', 'icon_management_approved_role')->get();
            $role = Auth::user()->role_id;
            // return $role === 7;
            return in_array(Auth::user()->id, json_decode(@$icon_approved_roles[0]['value']));
        });

        // Implicitly grant "Super-Admin" role all permission checks using can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });
    }
}
