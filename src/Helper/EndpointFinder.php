<?php

namespace Tenolo\Apilyzer\Helper;

use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class EndpointFinder
 *
 * @package Tenolo\Apilyzer\Helper
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class EndpointFinder
{

    /**
     * @param array $directories
     *
     * @return array|ReflectionClass[]
     */
    public function find(array $directories): array
    {
        $classes = [];

        $fcqns = $this->getAllFCQNs($directories);

        foreach ($fcqns as $fcqn) {
            $reflection = new ReflectionClass($fcqn);

            if (!$reflection->isAbstract() && !$reflection->isInterface() && $reflection->implementsInterface(EndpointInterface::class)) {
                $classes[] = $reflection;
            }
        }

        return $classes;
    }

    /**
     * @param $path
     *
     * @return array
     */
    protected function getFilesNames($path): array
    {
        $finderFiles = Finder::create()->files()->in($path)->name('*.php');

        $filenames = [];

        foreach ($finderFiles as $finderFile) {
            $filenames[] = $finderFile->getRealpath();
        }

        return $filenames;
    }

    /**
     * @param $path
     *
     * @return array
     */
    protected function getAllFCQNs($path): array
    {
        $filenames = $this->getFilesNames($path);
        $fcqns = [];

        foreach ($filenames as $filename) {
            $fcqns[] = $this->getFullNamespace($filename).'\\'.$this->getClassName($filename);
        }

        return $fcqns;
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    protected function getClassName(string $filename): string
    {
        $directoriesAndFilename = explode('/', $filename);
        $filename = array_pop($directoriesAndFilename);
        $nameAndExtension = explode('.', $filename);

        return array_shift($nameAndExtension);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    protected function getFullNamespace(string $filename): string
    {
        $lines = file($filename);
        $reg = preg_grep('/^namespace /', $lines);
        $namespaceLine = array_shift($reg);
        $namespaceLine = str_replace(["\r", "\n"], '', $namespaceLine);

        $match = [];
        preg_match('/^namespace (.*);$/', $namespaceLine, $match);

        return array_pop($match);
    }
}
