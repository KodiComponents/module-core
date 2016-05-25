<?php

namespace KodiCMS\CMS\Console\Commands;

use Composer;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Helper\TableSeparator;

class ModuleInstallCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:modules:install';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The table headers for the command.
     *
     * @var array
     */
    protected $headers = ['Namespace', 'Info'];

    /**
     * @var array
     */
    protected $installedPackages = [];

    /**
     * Execute the console command.
     *
     * @param Filesystem $files
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fire(Filesystem $files)
    {
        $this->files = $files;

        $moduleInfo = [];
        $tableData = [];

        if (file_exists($composerFile = base_path('vendor'.DIRECTORY_SEPARATOR.'composer'.DIRECTORY_SEPARATOR.'installed.json'))) {
            $this->installedPackages = json_decode($this->files->get($composerFile), true);
        }

        foreach ($this->files->directories(base_path('vendor')) as $packageDir) {
            foreach ($this->files->directories($packageDir) as $dir) {
                if (! is_null($data = $this->parseComposerFile($dir))) {
                    $moduleInfo = $moduleInfo + $data;

                    foreach ($data as $moduleName => $info) {
                        $tableData[] = [
                            $moduleName,
                            json_encode($info, JSON_PRETTY_PRINT)
                        ];
                    }

                    $tableData[] = new TableSeparator();
                }
            }
        }

        uasort($moduleInfo, function($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }
            return ($a['priority'] < $b['priority']) ? -1 : 1;
        });

        $this->info('Installed modules');
        $this->table($this->headers, $tableData);

        $modulesCachePath = $this->getPath();
        $stub = $this->files->get($this->getStub());

        $this->files->put($modulesCachePath, str_replace('{{modules}}', var_export($moduleInfo, true), $stub));

        $this->call('vendor:publish', [
            '--tag' => ['kodicms'],
            '--force' => true,
        ]);
    }

    /**
     * @param string $dir
     *
     * @return array|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function parseComposerFile($dir)
    {
        if( !file_exists($path = $dir.DIRECTORY_SEPARATOR.'composer.json')) {
            return;
        }

        $json = json_decode($this->files->get($path), true);

        if (
            ! isset($json['type'])
            or
            $json['type'] != 'kodicms-module'
            or
            ! isset($json['autoload']['psr-4'])
        ) {
            return;
        }

        foreach ($this->installedPackages as $package) {
            if (array_get($package, 'name') == array_get($json, 'name')) {
                $json['version'] = array_get($package, 'version');
                $json['source'] = array_get($package, 'source');
            }
        }

        $data = [];

        foreach ($json['autoload']['psr-4'] as $namespace => $path) {
            $pathInfo = pathinfo($dir);
            $moduleName = array_get($json, 'module.name', $pathInfo['basename']);

            $data[$moduleName] = [
                'namespace'   => $namespace,
                'path'        => $dir.DIRECTORY_SEPARATOR.normalize_path($path),
                'priority'    => (int)array_get($json, 'module.priority'),
                'authors'     => array_get($json, 'authors', []),
                'package'     => array_get($json, 'name'),
                'description' => array_get($json, 'description'),
                'homepage'    => array_get($json, 'homepage'),
                'support'     => array_get($json, 'support', []),
                'require'     => array_get($json, 'require', []),
                'version'     => array_get($json, 'version', []),
                'source'      => array_get($json, 'source', [])
            ];
        }

        return $data;
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     *
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return base_path('bootstrap'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'modules.php');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/modules.stub';
    }
}
