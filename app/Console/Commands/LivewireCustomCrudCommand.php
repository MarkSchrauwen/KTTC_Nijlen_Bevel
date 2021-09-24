<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class LivewireCustomCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:livewire:crud 
        {nameOfTheClass? : The name of the Class }, 
        {nameOfTheModelClass? : The name of the Model Class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a custom livewire CRUD';

    /**
     * Our custom class properties here!
     *
     */
    
    protected $nameOfTheClass;
    protected $nameOfTheModelClass;
    protected $file;

    /**
     * Create a new command instance.
     *
     * @return void
     */    

    public function __construct()
    {
        parent::__construct();
        $this->file = new Filesystem();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Gathers all parameters
        $this->gatherParameters();

        // Generates the Livewire Class File
        $this->generateLivewireCrudClassfile();

        // Generates the Livewire View File
        $this->generateLivewireCrudViewfile();
    }

    protected function gatherParameters(){
        $this->nameOfTheClass = $this->argument('nameOfTheClass');
        $this->nameOfTheModelClass = $this->argument('nameOfTheModelClass');

        // If you didn't input the name of the class
        if(!$this->nameOfTheClass){
            $this->nameOfTheClass = $this->ask('Enter class name');
        }
        if(!$this->nameOfTheModelClass){
            $this->nameOfTheModelClass = $this->ask('Enter model class name');
        }

        $this->nameOfTheClass = Str::studly($this->nameOfTheClass);
        $this->nameOfTheModelClass = Str::studly($this->nameOfTheModelClass);
    }
    
    /**
     * generates the Crud Class File
     *
     * @return void
     */
    protected function generateLivewireCrudClassfile(){
        // Set the origin and destination for the livewire class file
        $fileOrigin = base_path('/stubs/custom.livewire.crud.stub');
        $fileDestination = base_path('/app/Http/Livewire/'.$this->nameOfTheClass.'.php');

        if ($this->file->exists($fileDestination)){
            $this->info('This class file already exists: '.$this->nameOfTheClass.'.php');
            $this->info('Aborting class file creation.');
            return false;
        }

        // Get the original string content of the file and replace
        $fileOriginalString = $this->file->get($fileOrigin);
        $replaceFileOriginalString = Str::replaceArray('{{}}',[
            $this->nameOfTheModelClass,
            $this->nameOfTheClass,
            $this->nameOfTheModelClass,
            $this->nameOfTheModelClass,
            $this->nameOfTheModelClass,
            $this->nameOfTheModelClass,
            $this->nameOfTheModelClass,
            Str::kebab($this->nameOfTheClass),
        ],$fileOriginalString);

        // Put the content into the destination directory
        $this->file->put($fileDestination, $replaceFileOriginalString);
        $this->info('Livewire class file created: '.$fileDestination);
    }
    
    /**
     * Generates the Livewire Crud View file
     *
     * @return void
     */
    protected function generateLivewireCrudViewfile(){
        // Set the origin and destination for the livewire view file
        $fileOrigin = base_path('/stubs/custom.livewire.crud.view.stub');
        $fileDestination = base_path('/resources/views/livewire/'.Str::kebab($this->nameOfTheClass).'.blade.php');

        if ($this->file->exists($fileDestination)){
            $this->info('This livewire view file already exists: '.Str::kebab($this->nameOfTheClass).'.blade.php');
            $this->info('Aborting view file creation.');
            return false;
        }

        // Copy file to destination
        $this->file->copy($fileOrigin, $fileDestination);
        $this->info('Livewire view file created: '.$fileDestination);
    }
}

