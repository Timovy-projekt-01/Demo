<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartBlazeGraph extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Specify the full path to the directory containing blazegraph.jar
        //$blazegraphDirectory = 'C:\Users\mvrbo\OneDrive\Documents\STU_FEI\TP1\timak\myowl';
        //chdir($blazegraphDirectory);

        // Build the command to start BlazeGraph

        $blazegraphCommand = "java -jar blazegraph.jar";
        $this->info("BlazeGraph is starting... \nWorkBench is available at http://localhost:9999 \n");
        shell_exec($blazegraphCommand);
        $this->info("Failed to start BlazeGraph.");
    }
}
