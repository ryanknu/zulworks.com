<style>
    h2 { margin-top: 20px; }
</style>
<div style="margin-left:80px;margin-right:80px;">
<h1>Routing</h1>

<h2>Routes and Child Routes</h2>

<p>Routes are recursively defined in the Zend Framework. This means any route may have a collection of routes that are “child routes”. This is a very important concept for creating complicated web applications, where defining all URL’s might make for a hassle if one root node changes name.</p>

<pre>'zf' => array(
    'type' => 'Literal',
    'options' => array(
        'route' => '/zf',
        'defaults' => array(
            '__NAMESPACE__' => 'Application\Controller',
            'controller'    => 'Article',
            'action'        => 'index',
        ),
    ),
    'may_terminate' => true,<span style="color:red">
    'child_routes' => array(
        'default' => array(
            'type'    => 'Segment',
            'options' => array(
                'route'    => '/:action',
                'constraints' => array(
                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
            ),
        ),
    ),</span>
),</pre>

<p>This route is actually from my zulworks website, and it is the one that runs when you request <code>/zf/routing</code>. Notice how, in this example, you end up with the controller <code>Application\Controller\Article</code>, with an action of <code>routingAction</code>.</p>

<h2>The Literal Route</h2>

<p>The Zend Framework application creates and maintains a route stack interface designed to allow you to configure your routes in a flexible and powerful manner. The router in the Zend framework is designed to mimic the routing types and schemas of Ruby on Rails. Let’s look at a basic route:</p>

<pre>'home' => array(
    'type' => 'Zend\Mvc\Router\Http\Literal',
    'options' => array(
        'route'    => '/users/ryan@ryanknu.com',
        'defaults' => array(
            'controller' => 'Application\Controller\Users', // Controller or Controller Alias
            'action'     => 'view',                         // Method to be called, viewAction() in this case
        ),
    ),
),</pre>

<p>This is a literal HTTP route, it matches the resource portion of the HTTP request. You can determine the type of route by setting the ‘type’ flag to be an instance of any class you have a factory set up for. Notice, you can make your own route match classes via this mechanism.
</p>

<p><code>HTTP1/1 GET /users/ryan@ryanknu.com</code></p>

<p>A literal HTTP route only matches if the entire request string matches. Literally. This makes them less useful for most routes, however, it can allow you to improve the performance (somewhat negligibly, but still improve) of incredibly common pages that require some amount of recursive route matching to determine the path for. For example, you may use it to cache some URL’s that have get parameters for your biggest companies, short-circuiting the route matching and supplying it directly with a namespace, controller, action, and other URL parameters. </p>

<h2>The Segment Route</h2>

<p>This is exactly like a literal route, only it only matches a portion of the URL string, along with wildcard characters. You would typically use this to only match a portion in the middle of the string, as a simple segment route would usually be a child route to a literal route. The segment route offers basic glob functionality as well, allowing the use of the * character as a wildcard.</p>

<pre>'default' => array(
    'type'    => 'Segment',
    'options' => array(
        'route'    => '/:action',
        'constraints' => array(
            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        ),
    ),
),</pre>

<p>Optional segments can be defined with the brackets syntax:</p>

<pre>'testing' => array(
    'type' => 'Segment',
    'options' => array(
        'route' => '/test/:a[/:b[/:c[/:d[/]]]]',
        'defaults' => array(
            'controller' => 'Test',
            'action' => 'test',
        ),
    ),
),</pre>

<p>This is a route I used to test route matching. It actually works very well for allowing the input of up to four arguments, as a catch all. You may use this as a parent route for any routes you want to play with, and then see what you can/can't override.</p>

<h2>The Method Route</h2>

<p>ZF2 is designed to make RESTful web applications, so it’s essential that your framework has the capability to respond to all HTTP methods. This is done via the method route match, which will only match a given segment route if the HTTP verb int he request corresponds to the route config given.</p>

<pre>'add' => array (
    'type' => 'Segment',
    'options' => array(
        'route' => '/add/:item',
        'defaults' => array(
            'action' => 'add',
        ),
    ),
    'child_routes' => array(
        'put' => array (
            <span style="color:red">'type' => 'Zend\Mvc\Router\Http\Method',</span>
            'options' => array(
                <span style="color:red">'verb' => 'PUT',</span>
                'defaults' => array(
                    'action' => 'put',
                ),
            )
        ),
        'get' => array (
            <span style="color:red">'type' => 'Zend\Mvc\Router\Http\Method',</span>
            'options' => array(
                <span style="color:red">'verb' => 'GET',</span>
                'defaults' => array(
                    'action' => 'add',
                ),
            )
        ),
    ),
),</pre>

<p>This route will do the action <code>Controller::putAction()</code> for this HTTP request:</p>

<pre><span style="color:red">HTTP1/1 PUT /add/12345

foo=true&amp;bar=false</span></pre>

<p>and the action <code>Controller::addAction()</code> for GET requests, and serve a 404 for all others.</p>

<h2>The Part Route</h2>

<p>The Zend\Mvc\Router\Http\Part class allows you to define a partial route-match tree. In fact, if you pass into Part::factory that which you supply in your module config files, you will end up with the exact same RouteMatch as you would.</p>

<h2>The Wildcard Route</h2>

<p>Matches a route segment that contains any characters. I assume this is the least useful route type available.</p>

<h2>The Scheme and Hostname Routes</h2>

<p>The scheme and hostname routes allow your application to react to both the hostname used to identify the computer as well as the url scheme provided. </p>

<pre>'http' => array(
    'type' => 'Zend\Mvc\Router\Http\Scheme',
    'options' => array(
        'scheme' => 'http',
        <span style="color:red">'priority' => 2,</span>
        'defaults' => array(
            'controller' => 'Application\Controller\Http',
            'action'     => 'index',
        ),
    ),
),</pre>

<p>This route will snag ALL requests using regular HTTP, for the purposes of forwarding the user to HTTPS. The reason that child routes aren't matched first is because of the highlighted "priority" portion of the route definition.</p>

<h1>Designing Request URL’s</h1>

<p>It is important that when you design a URL to reduce redundancy from left to right. Let’s look at how we can do this with an example. Let’s assume we have three resources that provide an interface to the ACL system. </p>

<pre>Route     Resource    Parameters
1         Role        company, role
2         Roles       company
3         Users       company</pre>

<p>Route 1 requires two parameters, it is very tempting to put them together (at the end). If we follow suit with all three routes we end up with this set:</p>

<pre>/acl/roles/:company/:role[/]  => /acl/roles/mpn/developer
/acl/roles/:company[/]        => /acl/roles/mpn
/acl/users/:company[/]        => /acl/users/mpn</pre>

<p>Except this scheme has a problem, we’re thinking about this in terms of requesting a resource and passing in arguments:</p>

<pre>/district.php?company=28&categ=40     => district = (28, 40)
/district/28/categ/40                 => district = 28, category = 40</pre>

<p>Instead of thinking of these things in terms of resources that take arguments (that’s what they are, but that’s not semantically what a url represents), we should look at these in terms of collections. </p>

<pre>URL          Collection
/acl/roles   Roles
/acl/users   Users</pre>

<p>Now we’re getting somewhere, but wait, this is still not perfect (although some could argue it is.) Let’s expand this out a little bit</p>

<pre>URL              Collection
/acl/roles/mpn   Roles at MPN
/acl/users/mpn   Users at MPN</pre>

<p>Notice the redundancy? For every collection above ACL, we repeat MPN multiple times. Let’s change up our scheme a little bit and see what it means semantically.</p>

<pre>/acl/:company/roles/:role[/]  => /acl/mpn/roles/developer
/acl/:company/roles[/]        => /acl/mpn/roles
/acl/:company/users[/]        => /acl/mpn/users</pre>

<p>This looks like equally as good a URL scheme, but there’s one advantage in what it logically represents</p>

<pre>URL              Collection
/acl/mpn         ACL Rules at MPN
/acl/mpn/roles   Roles
/acl/mpn/users   Users</pre>

<p>Aha! Our redundancy is gone, fantastic. So there’s our amazing URL... but what happens if we design them badly and have to change them around logically? That’s no problem! The router can build URL’s based on route prototypes and parameters!</p>

<p><code>$url = $this->url()->fromRoute('acl/roles', array('company' => 'mpn', 'role' => 'developer'));</code></p>

</div>