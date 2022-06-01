<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'crud:all')]
class CRUDCommand extends Command
{
    protected $signature = 'crud:all {name}';

    protected $description = 'CRUD Command Description';

    public function handle()
    {
        $name = ucfirst(trim($this->argument('name')));

        $this->call('make:model', [
            'name' => $name,
            '-f' => true,
            '-m' => true,
            '-s' => true,
        ]);

        $this->call('make:controller', [
            'name' => "Dashboard/{$name}Controller",
            '-R' => true,
            '--model' => "App/Models/$name"
        ]);

        $this->call('make:controller', [
            'name' => "Api/{$name}Controller",
            '--api' => true,
            '-R' => true,
            '--model' => "App/Models/$name"
        ]);

        $this->call('make:repository', [
            'name' => $name,
            '-i' => true,
        ]);

        $this->call('make:resource', [
            'name' => "$name/{$name}Resource",
        ]);
    }
}
