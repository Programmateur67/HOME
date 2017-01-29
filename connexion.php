<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="style.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
        <title>Poups</title>
    </head>
    <body class="container-fluid bg">
        <div class="row">
            <form class="navbar-form" action="index.php" method="post">
                <div class="form-groups">                
                    <input type="text" placeholder="Identifiant" name="identity" class="form-control"/>  
                    <div class="input-group">
                        <input type="password" placeholder="Mot de passe" name="password" class="form-control"/>
                        <div class="input-group-btn">
                            <button type="submit" role="button" class="btn btn-warning margin-right"><span class="fa fa-check-square"></span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>