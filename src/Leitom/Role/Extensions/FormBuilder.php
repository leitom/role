<?php namespace Leitom\Role\Extensions;

use \Illuminate\Routing\UrlGenerator;
use \Illuminate\Html\HtmlBuilder as IlluminateHtmlBuilder;
use \Illuminate\Session\Store as Session;
use \Illuminate\Html\FormBuilder as IlluminateFormBuilder;
use \Leitom\Role\Contracts\ManagerInterface;

class FormBuilder extends IlluminateFormBuilder
{
	/**
	 * An class var to check if the current
	 * user have access to the current form so that
	 * we dont need to run more queries
	 *
	 * @var boolean $access
	 */
	protected $access = false;

    /**
     * Instance of the current role manager
     *
     * @var \Leitom\Role\Contracts\ManagerInterface
     */
    protected $manager;

    /**
     * Create a new form builder instance.
     *
     * @param  \Illuminate\Routing\UrlGenerator  $url
     * @param  \Illuminate\Html\HtmlBuilder  $html
     * @param  string  $csrfToken
     * @return void
     */
    public function __construct(
        IlluminateHtmlBuilder $html, 
        UrlGenerator $url, 
        $csrfToken,
        ManagerInterface $manager
    ) {
        parent::__construct($html, $url, $csrfToken);

        $this->manager = $manager;      
    }

	/**
     * Open up a new HTML form.
     * Form model uses this so we dont have to check again
     *
     * @param  array   $options
     * @return string
     */
    public function open(array $options = array())
    {
    	// Check if the user has access if not dont show the element
		if ( ! $this->checkRoleAccess($options)) {
			$this->access = false;
			return;
		} else {
			$this->access = true;
		}

		return parent::open($options);
    }

	/**
	 * Close the current form.
     *
     * @return string
     */
	public function close()
    {
    	if ( ! $this->access) return;
    	
    	return parent::close();
    }

	/**
	 * Create a submit button element.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
	public function submit($value = null, $options = array())
    {
    	if ( ! $this->access) return;
        
        return parent::submit($value, $options);
    }

	/**
     * Create a button element.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
	public function button($value = null, $options = array())
    {
    	if ( ! $this->access) return;
    	
    	return parent::button($value, $options);
    }

	/**
     * Create a form input field.
     *
     * @param  string  $type
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
	public function input($type, $name, $value = null, $options = array())
    {
    	$this->appendReadOnly($options);

    	return parent::input($type, $name, $value, $options);
    }

 	/**
     * Create a text input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
	public function text($name, $value = null, $options = array())
	{
		$this->appendReadOnly($options);

		return parent::text($name, $value, $options);
	}

	/**
     * Create an e-mail input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
	public function email($name, $value = null, $options = array())
    {
    	$this->appendReadOnly($options);

    	return parent::email($name, $value, $options);
    }

	/**
     * Create a url input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
	public function url($name, $value = null, $options = array())
    {
    	$this->appendReadOnly($options);

    	return parent::url($name, $value, $options);
    }

	/**
     * Create a textarea input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
	public function textarea($name, $value = null, $options = array())
    {
    	$this->appendReadOnly($options);

    	return parent::textarea($name, $value, $options);
    }

	/**
     * Create a select box field.
     *
     * @param  string  $name
     * @param  array   $list
     * @param  string  $selected
     * @param  array   $options
     * @return string
     */
	public function select($name, $list = array(), $selected = null, $options = array())
    {
    	$this->appendReadOnly($options);

    	return parent::select($name, $list, $selected, $options);
    }

	/**
     * Create a checkbox input field.
     *
     * @param  string  $name
     * @param  mixed   $value
     * @param  bool    $checked
     * @param  array   $options
     * @return string
     */
	public function checkbox($name, $value = 1, $checked = null, $options = array())
	{
		$this->appendReadOnly($options);

		return parent::checkbox($name, $value, $checked, $options);
	}

	/**
     * Create a radio button input field.
     *
     * @param  string  $name
     * @param  mixed   $value
     * @param  bool    $checked
     * @param  array   $options
     * @return string
     */
	public function radio($name, $value = null, $checked = null, $options = array())
    {
    	$this->appendReadOnly($options);

    	return parent::radio($name, $value, $checked, $options);
    }

	/**
	 * Check if the user have role access
	 *
	 * @param  array $parameters
	 * @return void
	 */
	protected function checkRoleAccess(array $parameters = array())
	{
		$formMethod = $this->getMethod(array_get($parameters, 'method', 'post'));
		
		if (isset($parameters['url'])) {
			return $this->manager->hasUrlAccess($formMethod, $parameters['url']);
		} else if (isset($parameters['route'])) {
			return $this->manager->hasRouteAccess($formMethod, $parameters['route']);
		} else if (isset($parameters['action'])) {
			return $this->manager->hasActionAccess($formMethod, $parameters['action']);
		}
	}

	/**
	 * Append a html5 readonly attribute if the user
	 * dont have access to posting the form.
	 * If the form never should have been seen for the user
	 * add role control to the route!
	 *
	 * @var array $options
	 */
	protected function appendReadOnly(&$options = array())
	{
		if ( ! $this->access) $options['readonly'] = 'readonly';
	}
}
