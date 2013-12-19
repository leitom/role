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
		// Publish the config file
		$this->call('config:publish', array('leitom/role'));
		
		// Migrations
		$this->call('migrate', array('--package' => 'leitom/role'));
		
		// Display finished message
		$this->info('Installation of package: leitom/role finished.');
	}
}
