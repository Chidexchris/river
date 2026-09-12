<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $pageTitle     = 'All Permissions';
        $dbPermissions = Permission::all()->keyBy('route_name');

        $excludedControllers = [
            'LoginController',
            'ForgotPasswordController',
            'ResetPasswordController',
            'PermissionController',
        ];

        $excludedRouteNames = [
            'admin.cache.clear',
            'admin.password.update',
            'admin.profile',
            'admin.profile.update',
        ];

        $permissions = collect(Route::getRoutes())
            ->filter(fn($route) => str_starts_with($route->getName(), 'admin.') &&
                !in_array(class_basename($route->getControllerClass()), $excludedControllers) &&
                !in_array($route->getName(), $excludedRouteNames)
            )
            ->map(fn($route) => $this->formatRoute($route, $dbPermissions))
            ->groupBy('module')
            ->sortKeys();

        return view('admin.page.permissions', compact('permissions', 'pageTitle'));
    }

    protected function formatRoute(RoutingRoute $route, Collection $dbPermissions): array
    {
        $controller = class_basename($route->getControllerClass());
        $module     = str_replace('Controller', '', $controller);

        return [
            'module'     => Str::headline($module),
            'route_name' => $route->getName(),
            'method'     => $route->methods()[0],
            'permission' => $dbPermissions->firstWhere('route_name', $route->getName())->name ?? 'Undefined',
        ];
    }

    public function update(Request $request)
    {
        $permissions = $request->input('permissions');

        foreach ($permissions as $modulePermissions) {
            foreach ($modulePermissions as $permission => $newValue) {
                Permission::where('name', $permission)->update([
                    'name' => trim($newValue)
                ]);
            }
        }

        $toast[] = ['success', 'Permissions updated successfully'];

        return back()->with('toasts', $toast);
    }
}
