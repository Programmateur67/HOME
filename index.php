<?php
    
    //$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u673050555_user','u673050555_root','davserveur');
    $bdd = new PDO('mysql:host=localhost;dbname=poups','root','');

    $default_pass = sha1('0000');
    setcookie('pseudo','User', null, null,null,false,true);
    setcookie('id','User', null, null,null,false,true);
    setcookie('pass',$default_pass, null, null,null,false,true);

    session_start();
    $_SESSION['id'] = '0';
    $_SESSION['pseudo'] = 'User';
?>
<!DOCTYPE html>s
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="style.css"/>
        <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
        <title>Poups</title>
    </head>
    <?php $connected = false; ?>
    <body class="container-fluid bg">
        <header class="container-fluid mragin-bottom">
            <div class="row">
                <nav class="navbar navbar-inverse navbar-default navbar-fixed-top">
                    <div class="navbar-header">
                        <a class="navbar-brand btn">Poups</a>
                    </div>
                    <ul class="nav navbar-nav">
                        <li><a class="btn" >Calendrier</a></li>
                        <li><a class="btn">Listes</a></li>
                    </ul>
                    <div>
                    <?php 
                        if($connected == false)
                        { 
                            ?>   
                        <div class="container-fluid">
                    <a href="#" data-toggle="modal" data-target="#coModal" class="navbar-brand btn navbar-right">Connexion</a>
                        </div>
                    <?php
                        } else 
                        {
                            ?>
                    <div class="container-fluid">    
                        <a href="deconnexion.php" class="navbar-brand btn navbar-right">Déconnexion</a>
                        </div>
                    <?php
                        } 
                    ?>
                    <?php 
                    if(isset($_POST['identity']) && isset($_POST['password']))
                    {
                        $pseudo = htmlspecialchars($_POST['identity']) ;
                        // Hachage du mot de passe
                        $pass_hache = sha1($_POST['password']);

                        // Vérification des identifiants
                        $req = $bdd->prepare('SELECT pseudo FROM user WHERE pseudo = :pseudo AND pass = :pass');
                        $req->execute(array(
                            'pseudo' => $pseudo,
                            'pass' => $pass_hache));

                        $resultat = $req->fetch();

                        if (!$resultat)
                        {
                            $connected = false;
                            header('location:index.php');
                        }
                        else
                        {
                            $_SESSION['pseudo'] = $resultat['pseudo'];
                            $request = $bdd->prepare('UPDATE user SET lastco = NOW() WHERE pseudo = :pseudo');
                            $request->execute(array('pseudo' => $_SESSION['pseudo']));
                            $connected = true;
                        }
                    }
                    ?>
                    </div>
                </nav>      
            </div>    
        </header> 
        <div id="wrap" class="<?php if($connected == false){echo 'hidden';}else{echo '';} ?>">
            <div class="well">
                <div class="container-fluid" id="main-content">
                    <div class="row">
                        <div class="col-sm-4">
                            <form action="index.php" method="post" class="well">
                                <input type="text" class="form-control" name="title" placeholder="Titre"/>
                                <input type="text" class="form-control" name="text" placeholder="Courte description ici..."/>
                                <input type="date" class="form-control" name="date"/>
                                <input type="time" class="form-control" name="time"/>
                                <select class="form-control" name="impo">
                                    <option value="0">Pas d'importance</option>
                                    <option value="1">Tranquille</option>
                                    <option value="2">Pressé</option>
                                    <option value="3">Important</option>
                                </select>
                                <button type="submit" class="btn form-control btn-success">Valider</button>
                            </form>
                            <?php
                            if(isset($_POST['title']) && isset($_POST['text']) && isset($_POST['date']) && isset($_POST['time']) && isset($_POST['impo']))
                            {
                                                                            
                                $add = $bdd->prepare('INSERT INTO rappel(datedo,user,title,text,rappelday,id,importance,rappelhour) VALUES ( now(), :user, :tilte, :text, :rappelday, :id, :importance, :rappelhour)');
                                $add->execute(array(
                                    'user' => $_SESSION['pseudo'],
                                    'title' => $_POST['title'],
                                    'text' => $_POST['text'],
                                    'rappelday' => $_POST['date'],
                                    'id' => 1,
                                    'importance' => $_POST['impo'],
                                    'rappelhour' => $_POST[''],
                                ));
                            }
                        ?>
                        </div>
                        <?php 
                            
                            $rappel = $bdd->query('SELECT * FROM rappel');
                            while($rappels = $rappel->fetch()){
                            ?>
                            <div class="col-sm-4">
                            <article class="well nowrap margin-righ">
                                <h3><?php echo $rappels['title']; ?></h3>
                                <h5 class="italic">Rappel défini le <?php echo $rappels['rappelday']; ?> à <?php echo $rappels['rappelhour']; ?> </h5>
                                <p>
                                    <?php echo $rappels['text']; ?>
                                </p>
                                <h6>Edité par <?php echo $rappels['user']; ?> le <?php echo $rappels['datedo']; ?></h6>
                                <?php
                                    switch($rappels['importance']){
                                        case 0:
                                            echo '<a class="btn btn-primary">A faire</a>';
                                            break;
                                        case 1:
                                            echo '<a class="btn btn-success">Tranquille</a>';
                                            break;
                                        case 2:
                                            echo '<a class="btn btn-warning">C\'est pressé</a>';
                                            break;
                                        case 3:
                                            echo '<a class="btn btn-danger">Très Urgent</a>';
                                            break;
                                        default:
                                            echo '<a class="btn btn-primary">A faire</a>';
                                            break;
                                    }
                                ?>
                                <a class="btn btn-primary" href="">Fait !</a>
                            </article>
                        </div>                            
                        <?php
                            }
                        
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="navbar navbar-inverse navbar-default navbar-fixed-bottom">
            <div class="navabr-header">
                <a href="profil.php" class="navbar-brand btn"><?php echo $_SESSION['pseudo']; ?></a>
            </div>
        </footer>
    </body>
    <script src="bootstrap-3.3.7-dist/js/jquery-3.1.1.min.js"></script>
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="jquery.nicescroll.min.js"></script>
    <script>$(function() { $("#main-content > .row").niceScroll();});</script>
    <div id="coModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header headerco">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="text-center">CONNEXION</h3>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="post">
                            <input type="text" placeholder="Identifiant" name="identity" class="form-control"/>  
                            <input type="password" placeholder="Mot de passe" name="password" class="form-control"/>
                            <input type="submit" class="btn btn-warning form-control"/>
                    </form>
                </div>
                <div class="modal-footer footerco">
                
                </div>
            </div>
        </div>
    </div>
</html>