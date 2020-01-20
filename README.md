# PHP MVC

Thank you very much for offering your time to seeing my project!

Here you will see a PHP MVC that I am using for my projects. I have used object-oriented programming along PDO.

## Folder structure

ROOT: .htaccess
|__ public: index.php, .htaccess
|__ css: style.css
|__ js: main.js
|__ img
|__app: bootstrap.php, .htaccess
       	|__ libraries: Core.php, Database.php, Controller.php
|__models: Post.php
|__view
	|__pages: index.php, about.php
|__inc: header.php, footer.php
|__controllers: Pages.php
|__helpers
|__config: config.php


## Details

The **.htaccess** from root make as entry point the **public/index.php** file. That include the **app/bootstrap.php** that include also all the library files (Core.php, Database.php, Controller.php, etc). 

The controller loads the model, calls a model function (getPosts), sets it to a variable ($posts) and passes/access it to a view.

> ### ROOT: .htaccess
>
> Rewrite everything from public to the root.

> ### /public/index.php
>
> This file will include the **../app/bootstrap.php** (that have included Core.php, Controller.php, Database.php, etc). Now instantiate Core class from **/libraries/Core.php** in order to know the **class/method/param** from **getUrl()** method that is called in Core constructor class.

> ### /public/.htaccess
>
> Deactivate **Multiviews** avoid confusion between links that end with the extension name or slash, then set the root folder, then set the condition: if the folder exists then to access it. If the file exists then to access it. If the requested file is not found then route everything through **/public/index.php**. Add placeholder variable to the URL, instead of writing **?url=posts** will allow writing just **/posts**.

> ### /app/bootstrap.php
>
> Is the file that requires all the library necessary files: Core.php, Controller.php, Database.php, config/config.php, etc. Here it will be an library autoload: **spl_autoload_register(function($className))**. In order to work, the library file name should match the class name.

> ### /app/.htaccess
>
> Set the access forbidden to the app folder content.

> ### /app/libraries/Core.php
>
> Take the link, create an array and decide what to load as a controller, as a method, and as a parameter. It is the main core class:  creates URL and loads core controller. The URL format is: **/controller/method/param**. 
>
> #### The class Core will load: 
> - the **Pages** default controller if not the other is specified by the URL link;
> - the current method **index** if not other is specified by the URL link;
> - an empty array **$params** that will be populated or not.
> - by calling in the construct the **getUrl()** method (that contains the URL). 
>
> **__construct():** assign to the $url variable the getUrl() method; if url[0](the controller file) exists set it as a current controller  ($this->currentController = ucwords($url[0])), then unset it. 
> Require the file then instantiate it.
> 
> **getUrl():** if the URL is set, then strip the ending slash by using the rtrim($_GET[‘url’], ‘/’) function, then sanitize it by URL:  filter_var($url, FILTER_SANITIZE_URL), then make it an array: explode(‘/’, $url) and return it.
>
> Check to see if method exists in the controller, if it is set $url[1], as:
> if(method_exists($this->currentController, $url[1])){} then apply it instead of the default method and unset it.
>
> Get params if exists or leave it empty: $this->params = $url ? array_values($url) : [];
>
> Call a callback with array of params:
> call_user_func_array([$this->current Controller, $this->currentMethod], $this->params);

> ### /app/libraries/Database.php
> It is the PDO class. That is doing connections to the database, create prepared statements, bind values, return rows and results. The database class will have private properties: $host, $user, $pass, $dbname, $dbh (database handler it will be useful when preparing a statement), then when we have a statement is need to have a property $stmt and $error.
>
> **__construct()**
> Contain a $dsn details: $dsn = ‘mysql:host=’ . $this->host . ‘;dbname=’ . $this->dbname;
> There are used some PDO options used in $options array:
> PDO::ATTR_PERSISTENT=>true (checking to see if a connection is already established);
> PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION (useful to handle the errors).
>
> Create PDO instance and show the errors.
>
> **query($sql)**
> Prepare statement with query.
>
> **bind($param, $value, $type=null)**
> When bind values there are 3 parameters: named parameter, value and the type. The switch function will assign a type: case the type ‘is_int’ the type will have the PDO::PARAM_INT value, then PARAM_BOOL or PARAM_NULL or default PARAM_STR.
> Then run the bindValue method: $this->stmt->bindValue($param, $value, $type);
>
> **execute()**
> Execute the prepared statement: return $this->stmt->execute();
>
> **resultSet()**
> Get result set as array of objects: call the execute() method and return: $this->stmt->fetchAll(PDO::FETCH_OBJ).
>
> **single()**
> Get a single record as an object
> 
> **rowCount()**
> Get row count. rowCount is a method part of PDO.

> ###/app/libraries/Controller.php
> It is a base controller, it is loading models and the view from other controllers. Every controller created will extend this class because including the methods to load the model and load the view, then you should be able to do it from your controller. The main class > #### Controller will  contain two methods: #### 
>
> **model()**
> Require the parameter $model like that: require_once ‘../app/models/’ . $model . ‘.php’ then instantiate it.
>
> **view()**
> That method receives two parameters: $view that is the file and $data=[ ] that represents the dynamic values that we can pass into the view. If the view exists require it else die.
> Now we should be able to access any of this method: model or view from within any controller that we extend the base controller from.

> ### /app/models
>Classes for db.

> ### /app/view
> Here are subfolders for each controller: pages, posts, users, etc.

> ### /app/view/pages/index.php
> Here we can use the data array, for example, the title that was passed to our controller:
> echo $data[‘title’];
> Here we can take the data from the model and send it to the view. This includes the header and footer. The $data array has all the values, so the forereach will bring for example $post->title.

> ### /app/view/inc/header.php
> The header website that contains the SITENAME constant, the CSS, and others;

> ### /app/view/inc/footer.php
> The footer website: contains also the js files.

> ### /app/controller
> All controller classes.

> ### /app/controllers/Pages.php
> It is the default class that loaded when no other controller is used. In order to work, a method should be written here, for example, the index that is the default method. That class Pages should extend the Controller.
>
> **__construct()**
> Load model: $this->postModel = $this->model(‘Post’);
>
> **index()**
> That requires the index view: pages/index, and pass the data to the view: [‘title’=>’Welcome’]
> The $posts will have all the posts then pass to $data.
> The getPosts() will be called in controller.
> $posts = $this->postModel->getPosts();
> $data = [‘title’=>’Welcome’, ‘posts’=>$posts];
>
> **about()**
> That requires the about view: pages/about, and pass the data to the view: [‘title’=>’About us’]
>
> **/app/helpers**
> Small things like redirect helper, session helper with a flash message, etc.
>
> **/app/config**
> Will contain the database parameter.

> ### /app/config/config.php
> Contain constants for APPROOT and URLROOT, SITENAME, and DB connection (DB_HOST, DB_USER, DB_PASS, DB_NAME).

> ### /app/models/
>For each model, accessed should have a property for DB: private $db.
>
> **__construct()**
> Instantiate the DB class: $this->db = new Database;

> ### /app/models/Post.php
> **__construct()**
> Instantiate the $db.
>
> **getPosts()**
> Get all the posts. Here is the query: 
> $this->db->query(“SELECT * FROM posts”);
> return $this->db->resultSet();
> Then go to the ‘index’ method from controllers/pages and call it.

##Deploying
If the application will be added inside of a folder, not to the root, go to ‘public/.htaccess’ and change the RewriteBase, add your folder.
Go to app/config/config.php and change the DB details and the URLROOT. 
