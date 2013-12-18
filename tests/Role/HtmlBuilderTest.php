<?php

class HtmlBuilderTest extends TestCase
{
	public function testThatICanSeeLinkToRoute()
	{
		$user = User::find(1);
		$this->be($user);

		$html = $this->app->make('leitom.role.html');

		$this->assertContains('<a href="', $html->linkRoute('routes.create'));
	}

	public function testThatICanNotSeeLinkToRoute()
	{
		$user = User::find(2);
		$this->be($user);

		$html = $this->app->make('leitom.role.html');

		$this->assertEmpty($html->linkRoute('routes.create'));
	}

	public function testThatICanSeeLinkToAction()
	{
		$user = User::find(1);
		$this->be($user);

		$html = $this->app->make('leitom.role.html');

		$this->assertContains('<a href="', $html->linkAction('RoutesController@create'));
	}

	public function testThatICanNotSeeLinkToAction()
	{
		$user = User::find(2);
		$this->be($user);

		$html = $this->app->make('leitom.role.html');

		$this->assertEmpty($html->linkAction('RoutesController@create'));
	}

	public function testThatICanNotSeeRoleLinkTo()
	{
		$user = User::find(2);
		$this->be($user);

		$html = $this->app->make('leitom.role.html');

		$this->assertEmpty($html->roleCheckLink('routes/create'));
	}

	public function testThatICanSeeRoleLinkTo()
	{
		$user = User::find(1);
		$this->be($user);

		$html = $this->app->make('leitom.role.html');

		$this->assertContains('<a href="', $html->roleCheckLink('routes/create'));
	}
}
