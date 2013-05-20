<!DOCTYPE HTML>
<html
    <head>
        <link href='http://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css'>
        <style>
            body { font-family: "Questrial", sans-serif; font-size:62.5%; text-transform: uppercase; }
            h2 { font-size: 4em; }
            .big { font-size: 3em; }
            .ledger { font-size: 2em;line-height:1.25em; }
            .left { float:left; }
            .right { float:right; }
            #loading { height:50%; margin-top:50%;width:1%;margin-left:auto;margin-right:auto; }
            #main-area > div { display: none; }
            #main-area > form { display: none; }
            
        </style>
        <script type="text/javascript" src="/js/jquery.min.js"></script>
        <script type="text/javascript" src="/js/collab.js"></script>
        
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        
        <title>Zul Collab online Balance Checker</title>
    </head>
    <body>

        <div id="main-area">
            <div id ="loading" style="display:block">
                Loading
            </div>
            
            <div id="db-load-error">
                Error: Cannot connect to database! Please contact Ryan to resolve this issue.
            </div>
            
            <form id="login-area">
                <div id="invalid" style="display:none;">
                    Sorry, your username and password did not match.
                </div>
                Username: <input type="text" id="user" /> <br />
                Password: <input type="password" id="pass" /> <br />
                <input type="submit" />
            </form>
            
            <div id="authenticated-area">
                <h2>Totals</h2>
                <div id="accounts">
                    No accounts!
                </div>
                <h2>Ledger</h2>
                <div id="ledger">&nbsp;</div>
            </div>
        </div>

    </body>
</html>