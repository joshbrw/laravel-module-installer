<?php

use PHPUnit\Framework\TestCase;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Joshbrw\LaravelModuleInstaller\Exceptions\LaravelModuleInstallerException;
use Joshbrw\LaravelModuleInstaller\LaravelModuleInstaller;

class LaravelModuleInstallerTest extends TestCase
{
    protected $io;
    protected $composer;
    protected $config;
    protected $test;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->io = Mockery::mock(IOInterface::class);
        $this->composer = Mockery::mock(Composer::class);
        $this->composer->allows([
            'getPackage' => $this->composer,
            'getDownloadManager' => $this->composer,
            'getConfig' => $this->composer,
            'get' => $this->composer,
        ])->shouldReceive('getExtra')->byDefault();

        $this->test = new LaravelModuleInstaller(
            $this->io, $this->composer
        );
    }

    /**
     * @test
     *
     * Your package composer.json file must include:
     *
     *    "type": "laravel-module",
     */
    public function it_supports_laravel_module_type_only(): void
    {
        $this->assertFalse($this->test->supports('module'));
        $this->assertTrue($this->test->supports('laravel-module'));
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function it_throws_exception_if_given_malformed_name(): void
    {
        $mock = $this->getMockPackage('vendor');

        $this->expectException(LaravelModuleInstallerException::class);

        $this->test->getInstallPath($mock);        
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function it_throws_exception_if_suffix_not_included(): void
    {
        $mock = $this->getMockPackage('vendor/name');

        $this->expectException(LaravelModuleInstallerException::class);

        $this->test->getInstallPath($mock);
    }

    /**
     * @test
     */
    public function it_returns_modules_folder_by_default(): void
    {
        $mock = $this->getMockPackage('vendor/name-module');

        $this->assertEquals('Modules/Name', $this->test->getInstallPath($mock));
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function it_throws_exception_if_given_malformed_compound_name(): void
    {
        $mock = $this->getMockPackage('vendor/some-compound-name');

        $this->expectException(LaravelModuleInstallerException::class);

        $this->test->getInstallPath($mock);
    }

    /**
     * @test
     */
    public function it_can_use_compound_module_names(): void
    {
        $mock = $this->getMockPackage('vendor/compound-name-module');

        $this->assertEquals('Modules/CompoundName', $this->test->getInstallPath($mock));
    }

    /**
     * @test
     *
     * You can optionally include a base path name
     * in which to install.
     *
     *    "extra": {
     *      "module-dir": "Custom"
     *    },
     */
    public function it_can_use_custom_path(): void
    {
        $package = $this->getMockPackage('vendor/name-module');

        $this->composer->shouldReceive('getExtra')
            ->andReturn(['module-dir' => 'Custom'])
            ->getMock();

        $this->assertEquals('Custom/Name', $this->test->getInstallPath($package));
    }

    private function getMockPackage($return)
    {
        return Mockery::mock(PackageInterface::class)
            ->shouldReceive('getPrettyName')
            ->once()
            ->andReturn($return)
            ->getMock();
    }

}
