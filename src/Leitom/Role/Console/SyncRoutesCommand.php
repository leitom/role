<?php namespace Leitom\Role\Console;

use \Leitom\Role\Contracts\RouteRepositoryInterface;
use \Leitom\Role\Contracts\RouteScannerInterface;
use \Leitom\Role\Contracts\RoleRepositoryInterface;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncRoutesCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'routes:sync';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sync all routes to persisant storage and clears the cache';

	/**
	 * The route repository to use
	 *
	 * @var \Leitom\Role\Contracts\RouteRepositoryInterface $repository
	 */
	protected $repository;

	/**
	 * The scanner instance to use
	 *
	 * @var \Leitom\Role\RouteScannerInterface $scanner
	 */
	protected $scanner;

	/**
	 * The role repository to use
	 *
	 * @var \Leitom\Role\Contracts\RoleRepositoryInterface $roleRepository
	 */
	protected $roleRepository;

	/**
	 * Create a new command instance.
	 *
	 * @param  \Leitom\Role\Contracts\RouteRepositoryInterface 	$repository
	 * @param  \Leitom\Role\RouteScannerInterface 				$scanner
	 * @param  \Leitom\Role\Contracts\RoleRepositoryInterface 	$roleRepository
	 * @return void
	 */
	public function __construct(
		RouteRepositoryInterface $repository,
		RouteScannerInterface $scanner,
		RoleRepositoryInterface $roleRepository
	) {
		parent::__construct();

		$this->repository = $repository;
		$this->scanner = $scanner;
		$this->roleRepository = $roleRepository;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		// Trigger sync
		$this->repository->sync($this->scanner, $this->roleRepository);

		$this->info('Routes synced!');

		$this->call('cache:clear');
	}
}
