{$render->doctype()}
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{$title|default:"Powered by zulworks Smarty 3 module for Zend Framework 2.0"}</title>

        <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="/images/favicon.ico" />
        <link rel="stylesheet" href="/css/bootstrap/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="/css/style.css" />
        <link rel="stylesheet" href="/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/css/zulworks.css" />
        
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/zulworks.js"></script>
        <script type="text/javascript" src="/js/jquery.min.js"></script>
    </head>
    
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="not-container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="/">&nbsp;zulworks</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li {if $active_page == "Home"}class="active"{/if}><a href="/">Home</a></li>
                            <li {if $active_page == "Blog"}class="active"{/if}><a href="/blog">Blog</a></li>
                            <li {if $active_page == "Routing"}class="active"{/if}><a href="/zf/routing">Routing</a></li>
                            <li><a href="https://github.com/ryanknu/zulworks.com">Source Code</a></li>
                            <li {if $active_page == "Zul"}class="active"{/if}><a href="/zf/routing">the ZUL</a></li>
                            <!--<li><a href="/">Smarty</a></li>
                            <li><a href="/">Less</a></li>
                            <li><a href="/">REST</a></li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        {$content}
    </body>
</html>
