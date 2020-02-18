# Base application class

##Intro

All your applications will be derived from this class or will be using classes wich are siblings of this Application class.

##Functionality

This class provieds:

- routing
- transformation actionActionName methods into static routes /action-name/ wich handles GET and POST methods
- loading routes from config file (php or json)

###Loading routes from config file

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
	*	Constructor.
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
	*	Constructor.
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

## Template resources class

### Intro

This class allows you to include css and js files to the 'head' tag of your DOM from any place os your source code.

### Usage

To use this class just create it's object:

```PHP
$resources = new TemplateResources();
```

Then add CSS and JS files:

```PHP
$resources->addJsFile( './include/js/file1.js' ); // one file
$resources->addJsFiles( array( './include/js/file1.js' , './include/js/file2.js' ) ); // or many files at one call
// and note that duplicate file file1.js will bi included into 'head' only once.

$resources->addCssFile( './res/css/file1.css' ); // one file
$resources->addCssFiles( array( './res/css/file2.css' , './res/css/file3.css' ) ); // or many files at one call
```

Quite simple.