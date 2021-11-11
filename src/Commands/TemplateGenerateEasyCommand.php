<?php

namespace Gsferro\TemplateGenerateEasy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TemplateGenerateEasyCommand extends Command
{
    private string $entite;
    private bool   $createDash = false;
    private bool   $createModal = false;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected string $signature = 'gsferro:template-generate {entite : Entite name} '; # {--fields=}

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Generate all files for new Entite!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $arguments = $this->arguments();
        $this->entite = ucfirst($this->argument('entite'));

        $this->line("");
        $this->comment("Preparando a criação da entidade [ {$this->entite} ]");

        /*
        |---------------------------------------------------
        | v0.1
        |---------------------------------------------------
        |
        | CRUD:
        |   - model
        |   - datatable (grid)
        |   - index
        |   - create
        |   - edit
        |   - todo:
        |       - preenchimento model e migration with fields
        |       - criar a pasta da entidade com o form pegando os fields
        |
        */
        $this->call("make:model",     ["name" => "{$this->entite}", "-m" => "-m"]);  # ser executada via blueprint
        $this->call("make:datatable", ["name" => "{$this->entite}/Table", "model" => "{$this->entite}"]);
        $this->call("livewire:make",  ["name" => "{$this->entite}/Index",  "--test" => "--test", "--stub" => "vendor/gsferro/template-generate-easy/src/stubs/livewire/index"]); # com o stub modificado
        $this->call("livewire:make",  ["name" => "{$this->entite}/Create", "--test" => "--test", "--stub" => "vendor/gsferro/template-generate-easy/src/stubs/livewire/create"]); # com o stub modificado # todo unificar
        $this->call("livewire:make",  ["name" => "{$this->entite}/Edit",   "--test" => "--test", "--stub" => "vendor/gsferro/template-generate-easy/src/stubs/livewire/edit"]); # todo unificar
        $this->call("pest:test",      ["name" => "{$this->entite}ModelTest", "--unit" => "--unit"]); # test model # Todo criar stub
        $this->call("dusk:make",      ["name" => "{$this->entite}DuskTest"]); # Todo criar stub

        /*
        |---------------------------------------------------
        | alterar os arquivos apos a criação colocando a Model
        |---------------------------------------------------
        */
        $this->applyModelInView();
        $this->applyModelInClass();

        /*
        |---------------------------------------------------
        | criar dashboard
        |---------------------------------------------------
        |
        | TODO
        */
        if ($this->createDash) {
//            $this->call("livewire:make",    ["name" => "Dashboard/{$this->entite}/Edit",   "--test" => "--test", "--stub" => "vendor/gsferro/template-generate-easy/src/stubs/livewire/dashboard"]);
        }

        /*
        |---------------------------------------------------
        | criar modal
        |---------------------------------------------------
        |
        | TODO
        */
        if ($this->createModal) {
//            $this->call("livewire:make",    ["name" => "{$this->entite}/Modal", "--test" => "--test", "--stub" => "vendor/gsferro/template-generate-easy/src/stubs/livewire/modal"]);
        }
    }

    private function applyModelInView()
    {
        $pathViews = resource_path('views');

        $acoes = ["index", "create", "edit"];
        foreach ($acoes as $view) {
            $paht = "{$pathViews}\livewire\\".Str::snake($this->entite)."\\{$view}.blade.php";
            File::put($paht, $this->applyGrid($paht));
        }
    }

    private function applyModelInClass()
    {
        $pathLivewire = app_path('Http/Livewire');

        $acoes = ["Index", "Create", "Edit"];
        foreach ($acoes as $file) {
            $paht = "{$pathLivewire}\\$this->entite\\$file.php";
            File::put($paht, $this->applyGrid($paht));
        }
    }

    private function applyGrid($file)
    {
        return preg_replace(
            ['/\[Model\]/', '/\[model\]/','/\{Model\}/', '/\[pathLivewire\]/' ],
            [$this->entite, strtolower($this->entite), $this->entite, Str::snake($this->entite) ],
            file_get_contents("{$file}")
        );
    }
}
