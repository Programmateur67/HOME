<?php
    
    $bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u673050555_user','u673050555_root','davserveur');
    //$bdd = new PDO('mysql:host=localhost;dbname=poups','root','');

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
    <body class="container-fluid bg">
        <?php $connected = false; ?>
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
                    </div>
                </nav>      
            </div>    
        </header> 
        <div id="wrap">
            <div class="well">
                <div class="container-fluid" id="main-content">
                    <div class="row">
                        <div class="col-sm-4">
                            <article class="well nowrap margin-righ">
                                    <h3>DM Philo</h3>
                                    <h5 class="italic">Rappel défini à 6H le 26/01/2017</h5>
                                    <p>
                                        Je dois faire mon devoir de philo. Est ce que la conscience à un fardeau ? 30 pages. Finir pour le 05/02/2017. Le faire en une fois c'est comme au bac.
                                    </p>
                                    <h6>Edité par Diego le 25/01/2017 à 5h24</h6>
                                    <a class="btn btn-primary">Fait !</a>
                                    <a class="btn btn-success">Tranquille</a>
                            </article>
                        </div>
                       <div class="col-sm-4">
                           <article class="well nowrap margin-righ">
                                    <h3>Jev Australie</h3>
                                    <h5 class="italic">Rappel défini à 15H le 01/04/2017</h5>
                                    <p>
                                        Diego ne dois pas oublier d'envoyer son dossier, préalablement fini, à la préfécture pour son visa d'étudiant en Australie. C'est asse important.
                                    </p>
                                    <h6>Edité par Maman le 05/01/2017 à 08h24</h6>
                                    <a class="btn btn-primary">Fait !</a>
                                    <a class="btn btn-warning">Pressé</a>
                            </article>                        
                        </div>
                        <div class="col-sm-4">
                            <article class="well nowrap margin-righ">
                                    <h3>Dr Ortega</h3>
                                    <h5 class="italic">Rappel défini à 10H le 21/02/2017</h5>
                                    <p>
                                        Tino doit aller chez Ortega pour le surclassement pour l'équipe de France. Ne pas oubliez de prendre la carte vitale et le dossier de la fédé. Il faut ensuite leur envoyer.
                                    </p>
                                    <h6>Edité par Maman le 10/01/2017 à 10h24</h6>
                                    <a class="btn btn-primary">Fait !</a>
                                    <a class="btn btn-danger">Important</a>
                            </article>
                        </div>
                        <div class="col-sm-4">
                            <form action="index.php" method="post" class="well">
                                <input type="text" class="form-control" name="title" placeholder="Titre"/>
                                <input type="text" class="form-control" name="text" placeholder="Courte description ici..."/>
                                <input type="date" class="form-control" name="date"/>
                                <input type="time" class="form-control" name="time"/>
                                <select class="form-control">
                                    <option value="0">Public</option>
                                    <option value="1">Tino</option>
                                    <option value="2">Diego</option>
                                    <option value="3">Felix</option>
                                    <option value="4">Maman</option>
                                    <option value="5">Papa</option>
                                </select>
                                <select class="form-control">
                                    <option value"0">Pas d'importance</option>
                                    <option value"1">Tranquille</option>
                                    <option value"2">Pressé</option>
                                    <option value"3">Important</option>
                                </select>
                                <input type="submit" class="hidden"/>
                            </form>
                        </div>
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