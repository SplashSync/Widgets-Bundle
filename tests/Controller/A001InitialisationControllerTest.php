<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2020 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Widgets\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Process\Process;

/**
 * Test Sequence Initiatlisation
 */
class A001InitialisationControllerTest extends KernelTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp() : void
    {
        self::bootKernel();
    }

    /**
     * Show Splash Logo
     */
    public function testDisplayLogo() : void
    {
        echo PHP_EOL;
        echo " ______     ______   __         ______     ______     __  __    ".PHP_EOL;
        echo "/\\  ___\\   /\\  == \\ /\\ \\       /\\  __ \\   /\\  ___\\   /\\ \\_\\ \\   ".PHP_EOL;
        echo "\\ \\___  \\  \\ \\  _-/ \\ \\ \\____  \\ \\  __ \\  \\ \\___  \\  \\ \\  __ \\  ".PHP_EOL;
        echo " \\/\\_____\\  \\ \\_\\    \\ \\_____\\  \\ \\_\\ \\_\\  \\/\\_____\\  \\ \\_\\ \\_\\ ".PHP_EOL;
        echo "  \\/_____/   \\/_/     \\/_____/   \\/_/\\/_/   \\/_____/   \\/_/\\/_/ ".PHP_EOL;
        echo "                                                                ".PHP_EOL;
        $this->assertTrue(true);
    }

    /**
     * Clear Cache for All Environements
     *
     * @dataProvider envTestNamesProvider
     *
     * @param string $environement
     */
    public function testCacheClear(string $environement) : void
    {
        //====================================================================//
        // Create Command
        $command = "php bin/console cache:clear --no-debug --env=".$environement;
        //====================================================================//
        // Execute Test (SF 3&4 Versions)
        try {
            $process = Process::fromShellCommandline($command);
        } catch (\Error $exception) {
            $process = new Process($command);
        }

        //====================================================================//
        // Clean Working Dir
        $workingDirectory = (string) $process->getWorkingDirectory();
        if (strrpos($workingDirectory, "/app") == (strlen($workingDirectory) - 4)) {
            $process->setWorkingDirectory(substr($workingDirectory, 0, strlen($workingDirectory) - 4));
        }

        //====================================================================//
        // Run Process
        $process->run();

        if (!$process->isSuccessful()) {
            echo $process->getCommandLine().PHP_EOL;
            echo $process->getOutput();
        }

        $this->assertTrue($process->isSuccessful());
    }

    /**
     * Test All Environement Are Loadable
     *
     * @dataProvider envTestNamesProvider
     *
     * @param string $environement
     */
    public function testEnvironements(string $environement) : void
    {
        //====================================================================//
        // Create Process
        $process = new Process("php bin/console debug:router --no-debug --env=".$environement);

        //====================================================================//
        // Clean Working Dir
        $workingDirectory = (string) $process->getWorkingDirectory();
        if (strrpos($workingDirectory, "/app") == (strlen($workingDirectory) - 4)) {
            $process->setWorkingDirectory(substr($workingDirectory, 0, strlen($workingDirectory) - 4));
        }

        //====================================================================//
        // Run Process
        $process->run();

        //====================================================================//
        // Fail => Display Process Outputs
        if (!$process->isSuccessful()) {
            echo $process->getCommandLine().PHP_EOL;
            echo $process->getOutput();
        }

        $this->assertTrue($process->isSuccessful());
    }

    /**
     * Tested Environements Codes Provider
     *
     * @return array
     */
    public function envTestNamesProvider() : array
    {
        return array(
            array("dev"),
            array("test"),
        );
    }
}
