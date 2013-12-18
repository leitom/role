<?php namespace Leitom\Role\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'role:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install the leitom/role package';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->info('Fire a bounch of artisan commands for automatic installing migrations, seeds, serviceprovider');
	}
}
