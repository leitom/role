<?php

class FormBuilderTest extends TestCase
{
	public function testThatFormCanOpen()
	{
		$user = User::find(1);
		$this->be($user);

		$form = $this->app->make('leitom.role.form');

		$this->assertContains('<form', $form->open(array('url' => 'formbuilder')));
	}

	public function testThatFormCantOpen()
	{
		$user = User::find(2);
		$this->be($user);

		$form = $this->app->make('leitom.role.form');

		$this->assertEmpty($form->open(array('url' => 'formbuilder')));
	}

	public function testThatFormCanClose()
	{
		$user = User::find(1);
		$this->be($user);

		$form = $this->app->make('leitom.role.form');

		$form->open(array('url' => 'formbuilder'));

		$this->assertContains('</form>', $form->close());
	}

	public function testThatFormCantClose()
	{
		$user = User::find(2);
		$this->be($user);

		$form = $this->app->make('leitom.role.form');

		$form->open(array('url' => 'formbuilder'));

		$this->assertEmpty($form->close());
	}

	public function testThatSubmitShows()
	{
		$user = User::find(1);
		$this->be($user);

		$form = $this->app->make('leitom.role.form');

		$form->open(array('url' => 'formbuilder'));

		$this->assertContains('<input type="submit"', $form->submit());
	}

	public function testThatSubmitDontShow()
	{
		$user = User::find(2);
		$this->be($user);

		$form = $this->app->make('leitom.role.form');

		$form->open(array('url' => 'formbuilder'));

		$this->assertEmpty($form->submit());
	}

	public function testThatButtonShows()
	{
		$user = User::find(1);
		$this->be($user);

		$form = $this->app->make('leitom.role.form');

		$form->open(array('url' => 'formbuilder'));

		$this->assertContains('<button', $form->button());
	}

	public function testThatButtonDontShow()
	{
		$user = User::find(2);
		$this->be($user);

		$form = $this->app->make('leitom.role.form');

		$form->open(array('url' => 'formbuilder'));

		$this->assertEmpty($form->button());
	}

	public function testThatHtml5ReadOnlyIsApplied()
	{
		$user = User::find(2);
		$this->be($user);

		$form = $this->app->make('leitom.role.form');

		$form->open(array('url' => 'formbuilder'));

		// Input
		$this->assertContains('readonly="readonly"', $form->input('test', 'test'));

		// Text
		$this->assertContains('readonly="readonly"', $form->text('test', 'test'));

		// Email
		$this->assertContains('readonly="readonly"', $form->email('test', 'test'));

		// Url
		$this->assertContains('readonly="readonly"', $form->url('test', 'test'));

		// Textarea
		$this->assertContains('readonly="readonly"', $form->textarea('test', 'test'));

		// Select
		$this->assertContains('readonly="readonly"', $form->select('test', array('m', 'b', 'c')));

		// Checkbox
		$this->assertContains('readonly="readonly"', $form->checkbox('test', 'test'));

		// Radio
		$this->assertContains('readonly="readonly"', $form->radio('test', 'test'));
	}
}
