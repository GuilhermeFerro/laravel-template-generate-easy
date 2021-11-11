<?php

namespace Gsferro\TemplateGenerateEasy\Providers;

use Gsferro\TemplateGenerateEasy\Commands\TemplateGenerateEasyCommand;
//use Gsferro\TemplateGenerateEasy\Services\ReversoTranslation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;

class TemplateGenerateEasyServiceProvider extends ServiceProvider
{
    public function register() { }

    public function boot()
    {
        /*
        |---------------------------------------------------
        | setando os serviços
        |---------------------------------------------------
        */
//        app()->bind('reversotranslation', function ($langFrom, $langTo) {
//            return new ReversoTranslation($langFrom, $langTo);
//        });

        /*
        |---------------------------------------------------
        | command
        |---------------------------------------------------
        */
        if ($this->app->runningInConsole()) {
            $this->commands([
                TemplateGenerateEasyCommand::class,
            ]);
        }

        /*
        |---------------------------------------------------
        | Middleware package mcamara/laravel-localization
        |---------------------------------------------------
        */

        /*
        |---------------------------------------------------
        | Publish
        |---------------------------------------------------
        */
        /*$this->publishes([
            __DIR__ . '/../config/translationsolutioneasy.php' => config_path('translationsolutioneasy.php'),
            __DIR__ . '/../config/laravellocalization.php'     => config_path('laravellocalization.php'),
            __DIR__ . '/../config/translation-loader.php'      => config_path('translation-loader.php'),
        ], 'config');*/

        // stubs
        if (! is_dir($stubsPath = base_path('stubs'))) {
            (new Filesystem)->makeDirectory($stubsPath);
        }
        $this->publishes([
            __DIR__ . '/../stubs' => $stubsPath,
        ], 'stubs');

        /*if (! class_exists('CreateLanguageLinesTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/create_language_lines_table.php.stub' => database_path('migrations/translation/'.$timestamp.'_create_language_lines_table.php'),
            ], 'migrations');
        }*/

        // flags
//        $this->loadTranslationsFrom(__DIR__.'/../resouces/lang', 'gsferro/translationsolutioneasy/lang');
//        $this->loadTranslationsFrom(__DIR__.'/../resouces/views', 'gsferro/translationsolutioneasy/flags');
        $this->publishes([
            __DIR__ . '/../resouces/lang'  => resource_path('lang/vendor/gsferro/translationsolutioneasy/lang'),
            __DIR__ . '/../resouces/views' => resource_path('views/vendor/gsferro/translationsolutioneasy/views/flags'),
        ], 'resouces');

//        $this->publishes([
//            __DIR__ . '/../public' => public_path('vendor/gsferro/translationsolutioneasy'),
//        ], 'public');

//        Blade::directive("translationsolutioneasyCss", function(){
//            return "<link href='/vendor/gsferro/translationsolutioneasy/css/flags.css' rel='stylesheet' type='text/css'/>";
//        });
//        Blade::include("vendor.gsferro.translationsolutioneasy.views.flags.flags", 'translationsolutioneasyFlags');

    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    /*protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, mergeConfig(require $path, $config));
    }*/

    
    private function publishStubs()
    {
        if (! is_dir($stubsPath = base_path('stubs'))) {
            (new Filesystem)->makeDirectory($stubsPath);
        }

        file_put_contents(
            $stubsPath.'/livewire.stub',
            file_get_contents(__DIR__.'/livewire.stub')
        );

        file_put_contents(
            $stubsPath.'/livewire.inline.stub',
            file_get_contents(__DIR__.'/livewire.inline.stub')
        );

        file_put_contents(
            $stubsPath.'/livewire.view.stub',
            file_get_contents(__DIR__.'/livewire.view.stub')
        );

        file_put_contents(
            $stubsPath.'/livewire.test.stub',
            file_get_contents(__DIR__.'/livewire.test.stub')
        );

        $this->info('Stubs published successfully.');
    }
}
