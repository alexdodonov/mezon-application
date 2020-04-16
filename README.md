# Base application class [![Build Status](https://travis-ci.com/alexdodonov/mezon-application.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-application) [![codecov](https://codecov.io/gh/alexdodonov/mezon-application/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-application)

## Intro

All your applications will be derived from this class or will be using classes wich are siblings of this Application class.

## Functionality

This class provieds:

- routing
- transformation actionActionName methods into static routes /action-name/ wich handles GET and POST methods
- loading routes from config file (php or json)

### Loading routes from config file

With time your application will grow and number of routes will increase. So we have provided convinient way to store all routes in a standalone config file. So it is not necessary to initialize all routes in an Application (or any derived class) object's constructor.

Let's find out how you can use it.

First of all create config file ./conf/routes.php in your projects directory. It must look like this:

```PHP
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
        ]
    ];
```

Note that the 'method' field is not set then it will be defaulted to GET.

Then just call Application::loadRoutesFromConfig() method and it will load your ./conf/routes.php config.

You can also specify your own config file.

```PHP
$app->loadRoutesFromConfig( './conf/my-config.php' );
```
## Common application class

### Intro

This class provides simple aplication routine. Using this class you can create veri simple applications with the [basic template](https://github.com/alexdodonov/mezon/tree/master/HtmlTemplate) wich looks like black text on white background.

It can be simply used for prototyping.

### Extended routes processing

In [Application](https://github.com/alexdodonov/mezon/tree/master/Application) class routes may return only strings. But CommonApplication class allows you to return arrays of string wich will be placed in the template placeholders.

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

Here we pass instance of the class View (or any class derived from View) to the application page compilator. It will call View::render method wich must return compiled html content.

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

		$this->loadRoutesFromConfigs(['./my-routes.json', './conf/my-routes.php']);
	}
```