<?php

namespace VirtualCloud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis-view:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the redis-view resources';

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $appConfigPath = config_path('app.php');
        $appConfigContent = File::get($appConfigPath);

        $providerClass = "App\Providers\RedisViewServiceProvider::class";
        if (strpos($appConfigContent, $providerClass) === false) {
            $appConfigContent = preg_replace(
                "/('providers' => \[)([^]]*)(\])/s",
                "$1$2\t    $providerClass,\n$3",
                $appConfigContent
            );
            File::put($appConfigPath, $appConfigContent);
        }

        $providerPath = "App/Providers/RedisViewServiceProvider.php";
        if ($this->filesystem->exists($providerPath)) {
            $this->error("RedisViewServiceProvider already exists!");
            return;
        }

        $content = $this->filesystem->get(__DIR__ . '/stubs/provider.stub');
        $content =  str_replace('{{namespace}}', 'App\Providers',$content);
        $content =  str_replace('{{class}}', 'RedisViewServiceProvider',$content);
        $content =  str_replace('{{extend}}', 'RedisServiceProvider',$content);
        $content =  str_replace('{{provider}}', 'VirtualCloud\RedisViewServeProvider',$content);
        $this->filesystem->put($providerPath, $content);

        $this->info("Service  created successfully.");
    }
}
