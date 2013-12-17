<?php namespace Leitom\Role\Extensions;

use \Illuminate\Routing\UrlGenerator;
use \Illuminate\Html\HtmlBuilder as IlluminateHtmlBuilder;
use \Leitom\Role\Contracts\ManagerInterface;

class HtmlBuilder extends IlluminateHtmlBuilder
{
	/**
     * Instance of the current role manager
     *
     * @var \Leitom\Role\Contracts\ManagerInterface
     */
    protected $manager;

	/**
     * Create a new HTML builder instance.
     *
     * @param  \Illuminate\Routing\UrlGenerator  $url
     * @return void
     */
    public function __construct(UrlGenerator $url = null, ManagerInterface $manager)
    {
    	parent::__construct($url);

    	$this->manager = $manager;
    }

    /**
     * Generate a HTML link with role control.
     *
     * @param  string  $url
     * @param  array   $parameters
     * @param  string  $title
     * @param  array   $attributes
     * @param  bool    $secure
     * @return string
     */
    public function roleCheckLink($url, $parameters = array(), $title = null, $attributes = array(), $secure = null)
    {
        if ( ! $this->manager->hasUrlAccess('GET', $url)) return;

        $url = $this->url->to($url, $parameters, $secure);

        if (is_null($title) || $title === false) $title = $url;

        return '<a href="'.$url.'"'.$this->attributes($attributes).'>'.$this->entities($title).'</a>'; 
    }

	/**
     * Generate a HTML link to a named route.
     *
     * @param  string  $name
     * @param  string  $title
     * @param  array   $parameters
     * @param  array   $attributes
     * @return string
     */
    public function linkRoute($name, $title = null, $parameters = array(), $attributes = array())
    {
    	if ( ! $this->manager->hasRouteAccess('GET', $name)) return;
    	
    	return parent::linkRoute($name, $title, $parameters, $attributes);
    }

	/**
     * Generate a HTML link to a controller action.
     *
     * @param  string  $action
     * @param  string  $title
     * @param  array   $parameters
     * @param  array   $attributes
     * @return string
     */
	public function linkAction($action, $title = null, $parameters = array(), $attributes = array())
    {
    	if ( ! $this->manager->hasActionAccess('GET', $action)) return;
    	
    	return parent::linkAction($action, $title, $parameters, $attributes);
    }
}
