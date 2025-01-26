<?php 
namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends BaseTestCase
{
    protected array $migrationPaths = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Force ALL connections to be SQLite in-memory
        $connections = config('database.connections');
        foreach ($connections as $name => $config) {
            config(["database.connections.$name" => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]]);
        }

        // Force default connection to SQLite
        config(['database.default' => 'sqlite']);

        require base_path('routes/web.php');
        require base_path('routes/api.php');

         // Debug database configuration
        fwrite(STDERR, sprintf(
            "\n🔍 Before checks - Connection: %s, Driver: %s, Database: %s\n",
            config('database.default'),
            config('database.connections.' . config('database.default') . '.driver'),
            config('database.connections.' . config('database.default') . '.database')
        ));

        
        // Add check for ALL configured connections
        foreach (config('database.connections') as $name => $config) {
            if ($config['driver'] !== 'sqlite') {
                throw new \RuntimeException(
                    "Tests must use SQLite! Found non-SQLite connection '$name' using: {$config['driver']}\n" .
                    'This is a safety measure to prevent accidental production database access.'
                );
            }

            if (($config['database'] ?? null) !== ':memory:') {
                throw new \RuntimeException(
                    "Tests must use in-memory SQLite! Found connection '$name' using database: " . 
                    ($config['database'] ?? 'null') . "\n" .
                    'This is a safety measure to prevent any file-based database access.'
                );
            }
        }

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });
    
        config([
            'session.driver' => 'database',
            'session.table' => 'sessions'
        ]);
        $this->checkAllModelsForHardcodedConnections();
    }

    // Add a method that actually runs our specific migrations
    protected function runSpecificMigrations()
    {
        if (!empty($this->migrationPaths)) {
            $this->artisan('migrate:fresh', [
                '--path' => $this->migrationPaths,
                '--realpath' => true  // This ensures it uses exact paths
            ]);
        }
    }

    // Add method to ensure all used connections are safe
    protected function assertSafeConnections(string $code): void 
    {
        if (preg_match("/DB::connection\(['\"](?!sqlite)([^'\"]*)['\"]\)/", $code, $matches)) {
            throw new \RuntimeException(
                "Found unsafe database connection usage: {$matches[1]}\n" .
                "Use test-safe database connections only."
            );
        }
    }

    protected function checkAllModelsForHardcodedConnections(): void
    {
        $modelDirectory = app_path('Models');
        $modelNamespace = 'App\\Models\\';
        
        if (!is_dir($modelDirectory)) {
            return;
        }
    
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($modelDirectory)
        );
    
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $className = $modelNamespace . str_replace(
                    [$modelDirectory . '/', '.php'], 
                    ['', ''],
                    $file->getRealPath()
                );
                
                $className = str_replace('/', '\\', $className);
                
                if (class_exists($className)) {
                    $reflection = new \ReflectionClass($className);
                    
                    // Check for hardcoded connection property
                    if ($reflection->hasProperty('connection')) {
                        $property = $reflection->getProperty('connection');
                        
                        // If the property is defined in this class (not parent)
                        if ($property->getDeclaringClass()->getName() === $className) {
                            $property->setAccessible(true);
                            
                            // Get default value if it exists
                            if ($property->hasDefaultValue()) {
                                $defaultValue = $property->getDefaultValue();
                                if ($defaultValue !== null) {
                                    throw new \RuntimeException(
                                        "DANGER: Found hardcoded database connection '{$defaultValue}' in {$className}!\n" .
                                        "Remove 'protected \$connection = \"{$defaultValue}\";' from the model.\n" .
                                        "Connection should only be set conditionally in production environment."
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}