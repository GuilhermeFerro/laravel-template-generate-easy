<?php

namespace Gsferro\TemplateGenerateEasy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use function Livewire\str;

class TemplateGenerateEasyCommand extends Command
{
    private string $entite;
    private bool   $createDash   = false;
    private bool   $createModal  = false;
    private bool   $createImport = false;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gsferro:template-generate {entite : Entite name} '; # {--fields=}

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all files for new Entite!';

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

        try {
            $this->call("make:model", [
                "name" => "{$this->entite}",
                "-m"   => "-m"
            ]); # ser executada via blueprint

            $this->call("make:datatable", [
                "name"  => "{$this->entite}/Table",
                "model" => "{$this->entite}"
            ]);

            $this->call("livewire:make", [
                "name"   => "{$this->entite}/Index",
                "--test" => "--test",
                "--stub" => "vendor/gsferro/template-generate-easy/src/stubs/livewire/index"
            ]); # com o stub modificado

            $this->call("livewire:make", [
                "name"   => "{$this->entite}/Create",
                "--test" => "--test",
                "--stub" => "vendor/gsferro/template-generate-easy/src/stubs/livewire/create"
            ]); # com o stub modificado # todo unificar

            $this->call("livewire:make", [
                "name"   => "{$this->entite}/Edit",
                "--test" => "--test",
                "--stub" => "vendor/gsferro/template-generate-easy/src/stubs/livewire/edit"
            ]); # todo unificar

            $this->call("pest:test", [
                "name"   => "{$this->entite}ModelTest",
                "--unit" => "--unit"
            ]); # test model # Todo criar stub

            $this->call("dusk:make", [
                "name" => "{$this->entite}DuskTest"
            ]); # Todo criar stub

            /*
            |---------------------------------------------------
            | alterar os arquivos apos a criação colocando a Model
            |---------------------------------------------------
            */
            $this->applyModelInView();
            $this->applyModelInClass();

            /*
            |---------------------------------------------------
            | criar import
            |---------------------------------------------------
            */
            if ($this->createImport) {
                // todo
            }

            /*
            |---------------------------------------------------
            | criar dashboard
            |---------------------------------------------------
            */
            if ($this->createDash) {
                //            $this->call("livewire:make",    ["name" => "Dashboard/{$this->entite}/Edit",   "--test" => "--test", "--stub" => "vendor/gsferro/template-generate-easy/src/stubs/livewire/dashboard"]);
            }

            /*
            |---------------------------------------------------
            | criar modal
            |---------------------------------------------------
            */
            if ($this->createModal) {
                //            $this->call("livewire:make",    ["name" => "{$this->entite}/Modal", "--test" => "--test", "--stub" => "vendor/gsferro/template-generate-easy/src/stubs/livewire/modal"]);
            }

        } catch (\Exception $e) {
            dump('Ops:', $e->getMessage());
        }
    }

    private function applyModelInView()
    {
        $pathViews = resource_path('views/livewire');

        $acoes = ["index", "create", "edit"];
        foreach ($acoes as $file) {
            $file = "{$pathViews}\\".str($this->entite)->kebab()."\\$file.blade.php";

            $this->applyInClass($file);
        }
    }

    private function applyModelInClass()
    {
        $pathLivewire = app_path('Http/Livewire');

        $acoes = ["Index", "Create", "Edit"];
        foreach ($acoes as $file) {
            $file = "{$pathLivewire}\\$this->entite\\$file.php";

            $this->applyInClass($file);
        }
    }

    private function applyInClass($file)
    {
        return File::exists($file) ? File::put($file, $this->applyReplace($file)) : false;
    }

    private function applyReplace($file)
    {
        $search  = ['/\[Model\]/', /*'/\[model\]/', */'/\{Model\}/', '/\[pathLivewire\]/'];
        $replace = [$this->entite, /*strtolower($this->entite),*/ $this->entite, str($this->entite)->kebab()];

        return preg_replace(
            $search,
            $replace,
            file_get_contents("{$file}")
        );
    }
}
