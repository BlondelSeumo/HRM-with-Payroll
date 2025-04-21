<?php

namespace App\Http\Middleware;

use App\Models\LandingPageSection;
use App\Models\Utility;
use App\Models\GenerateOfferLetter;
use Illuminate\Support\Facades\Config;
use Closure;
use Illuminate\Support\Facades\Schema;

class XSS
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::check()) {

            $settings = Utility::settings();
            if (!empty($settings['timezone'])) {
                Config::set('app.timezone', $settings['timezone']);
                date_default_timezone_set(Config::get('app.timezone', 'UTC'));
            }

            \App::setLocale(\Auth::user()->lang);

            if (\Auth::user()->type == 'super admin') {
                if (Schema::hasTable('ch_messages')) {

                    if (Schema::hasColumn('ch_messages', 'type') == false) {

                        Schema::drop('ch_messages');
                        \DB::table('migrations')->where('migration', 'like', '%ch_messages%')->delete();
                    }
                }

                // $migrations = $this->getMigrations();
                // $dbMigrations           = $this->getExecutedMigrations();
                // // $numberOfUpdatesPending = (count($migrations) + 6) - count($dbMigrations);
                // $numberOfUpdatesPending = (count($migrations)) - count($dbMigrations);

                $migrations             = $this->getMigrations();
                $messengerMigration     = Utility::get_messenger_packages_migration();
                $dbMigrations           = $this->getExecutedMigrations();
                $Modulemigrations = glob(base_path() . '/Modules/LandingPage/Database' . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR . '*.php');
                $numberOfUpdatesPending = (count($migrations) + count($Modulemigrations) + $messengerMigration) - count($dbMigrations);

                if ($numberOfUpdatesPending > 0) {
                    // run code like seeder only when new migration
                    Utility::addNewData();
                    return redirect()->route('LaravelUpdater::welcome');
                }
            }
        }

        return $next($request);
    }
}
