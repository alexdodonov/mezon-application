# Base application class

[![Build Status](https://travis-ci.com/alexdodonov/mezon-application.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-application) [![codecov](https://codecov.io/gh/alexdodonov/mezon-application/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-application) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdodonov/mezon-application/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexdodonov/mezon-application/?branch=master)

## Intro

All your applications will be derived from this class or will be using classes which are siblings of this Application class.

## Functionality

This class provides:

- routing
- transformation actionActionName methods into static routes /action-name/ which handles GET and POST methods
- loading routes from config file (php or json)

### Loading routes from config file

With time your application will grow and number of routes will increase. So we have provided convenient way to store all routes in a standalone config file. So it is not necessary to initialize all routes in an Application (or any derived class) object's constructor.

Let's find out how you can use it.

First of all create config file ./conf/routes.php in your projects directory. It must look like this:

```PHP
$callbackProvider = new SomeCallbackClass();

return 
    [
        [
            'route' => '/news/' , // your route
            'callback' => 'displayNewsLine' // this must be the method name of your 
                                              // Application derived class
        ] , 
        [
            'route' => '/news/[i:news_id]/' ,    // your route
            'callback' => 'displayExactNews' , // this must be the method name of your 
            'method' => 'POST'                   // Application derived class
        ] , 
        [
        	'route' => '/some-route/' , 
        	'method' => 'GET' , 
        	'callback' => [ // here we specify callback as pair [object, method]
        		callbackProvider , 
        		'someMethod'
        	]        	
        ]
    ];
```

Note that the 'method' field is not set then it will be defaulted to GET.

You can also specify your own config file.

Then just call Application::loadRoutesFromConfig()



```PHP
$app->loadRoutesFromConfig( './conf/my-config.php' );
```
## Common application class

### Intro

This class provides simple application routine with more complex rendering and error handling. 

### Extended routes processing

In [Application](https://github.com/alexdodonov/mezon/tree/master/Application) class routes may return only strings. But CommonApplication class allows you to return arrays of string which will be placed in the template placeholders.

Simple example:

```PHP
class           ExampleApplication extends CommonApplication
{
	/**
	 * Constructor.
	 */
	function			__construct( $template )
	{
		parent::__construct( $template );
	}

    function            actionSimplePage()
    {
        return [ 
            'title' => 'Route title' , 
            'main' => 'Route main'
        ];
    }
}
```

Here route's handler generates two parts of the page /simple-page/ - 'title' and 'main'. These two part will be inserted into {title} and {main} placeholders respectively.

More complex example:

```PHP
class           ExampleApplication extends CommonApplication
{
	/**
	 * Constructor.
	 */
	function			__construct($template)
	{
		parent::__construct($template);
	}

    function            actionSimplePage()
    {
        return [ 
            'title' => 'Route title' , 
            'main' => new View('Generated main content')
        ];
    }
}
```

Here we pass instance of the class View (or any class derived from View) to the application page compilator. It will call View::render method which must return compiled html content.

### Routes config

You can also keep al routes in configs. You can use json configs:

```JS
[
	{
		"route": "/route1/",
		"callback": "route1",
		"method": "GET"
	},
	{
		"route": "/route2/",
		"callback": "route2",
		"method": ["GET","POST"]
	}
]
```

This data must be stored in the './conf/' dir of your project. Or load configs explicitly as shown below (using method loadRoutesFromConfig).

And we also need these methods in the application class.

```PHP
class           ExampleApplication extends CommonApplication
{
	/**
	 * Constructor.
	 */
	function			__construct($template)
	{
		parent::__construct($template);

		// loading config on custom path
		$this->loadRoutesFromConfig('./my-routes.json');
	}

    function            route1()
    {
        return [ 
            // here result
        ];
    }

    function            route2()
    {
        return [ 
            // here result
        ];
    }
}
```

Note that you can load multiple configs with one call of the method loadRoutesFromConfigs

```PHP
function			__construct($template)
	{
		parent::__construct($template);

		$this->loadRoutesFromConfigs(['./conf/my/routes.json', './conf/my-routes.php']);
	}
```

Or the same:

```PHP
function			__construct($template)
	{
		parent::__construct($template);

		$this->loadRoutesFromDirectory('./conf');
	}
```

## Action messages

You can create file with the list of messages which will be substituted in the template variable `action-message`

This file must be stored in the directory `%your-application-class-directory%/res/action-messages.json`

Then if the class will find `$_GET['action-message']` parameter, then `action-message` substitution will be triggered.

# View

## Static view
You can simply output content of the *.tpl file as common view. It can be usefull if you need to render static pages or static page parts. It will let you to avoid creating separate class and separate view methods for these purposes.

It is quite simple:

```php
// here $template is an instance of the \Mezon\HtmlTemplate\HtmlTemplate class
// and 'block-name' is a block name in this class
$view = new \Mezon\Application\ViewStatic($template, 'block-name');
```

For more details about Mezon templates [see](https://github.com/alexdodonov/mezon-html-template)

# Controller

tba