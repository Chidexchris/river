<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Throwable;

class CreatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds predefined permissions into the database to ensure proper setup for the Role & Permission module.';

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle(): void
    {
        $permissions = $this->definePermissions();

        DB::table('permissions')->delete();
        DB::statement('ALTER TABLE permissions AUTO_INCREMENT = 1');

        DB::beginTransaction();

        try {
            foreach ($permissions as $permission) Permission::create($permission);

            DB::commit();

            $this->info('Permissions have been seeded.');
        } catch (Throwable $exception) {
            DB::rollBack();

            $this->error("An error occurred while seeding permissions: {$exception->getMessage()}");
        }
    }

    /**
     * Defines the permissions based on registered routes.
     * Excludes certain controllers and route names to avoid unnecessary permissions.
     *
     * @return array
     */
    protected function definePermissions(): array
    {
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

        return collect(Route::getRoutes())
            ->filter(fn($route) => str_starts_with($route->getName(), 'admin.') &&
                !in_array(class_basename($route->getControllerClass()), $excludedControllers) &&
                !in_array($route->getName(), $excludedRouteNames)
            )
            ->map(fn($route) => $this->formatRoute($route))
            ->values()
            ->toArray();
    }

    /**
     * Formats a given route into a permission structure.
     *
     * @param RoutingRoute $route
     * @return array
     */
    protected function formatRoute(RoutingRoute $route): array
    {
        $controller = class_basename($route->getControllerClass());
        $module     = str_replace('Controller', '', $controller);

        return [
            'name'       => Str::random(5),
            'module'     => Str::headline($module),
            'route_name' => $route->getName(),
        ];
    }
}
