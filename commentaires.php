<!doctype html> 
<html>
	<head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	</head> 
	<body>
		<form method="POST">
			<input type="text" name="nom" placeholder="Nom"><br>
			<textarea name="commentaire" placeholder="Commentaire"></textarea><br>
			<input type="submit" value="Envoyer" name="envoyer">
		</form>
		<?php
			$lien=mysqli_connect("localhost","root","","tp");
			if(isset($_POST['envoyer']))
			    {
                $contenu = trim($_POST['commentaire']);
                $nom = trim($_POST['nom']);
                if ($contenu != '' && $nom != '')
                    {
                    $nom=htmlentities(mysqli_real_escape_string($lien,$_POST['nom']));
                    $contenu=htmlentities(mysqli_real_escape_string($lien,$_POST['commentaire']));
                    $req="INSERT INTO commentaires VALUES (NULL,'$nom','$contenu')";
                    $res=mysqli_query($lien,$req);
                    if(!$res)
                        {
                        echo "Erreur SQL:$req<br>".mysqli_error($lien);
                        }
                    }
                else    
                    {
                    echo "<div>Veuillez remplir tout les champs</div>";
                    }
			    }
			
			if(!isset($_GET['page']))
			    {
				$page=1;
			    }
			else
			    {
                if ($_GET['page'] < 1)
                    {
                    header("Location:commentaires.php?page=1");
                    }
                else
                    {
                    $page=$_GET['page'];
                    }
                }
                
			$commparpage=5;
			$premiercomm=$commparpage*($page-1);
			$req="SELECT * FROM commentaires ORDER BY id LIMIT $premiercomm,$commparpage";/* LIMIT dit ou je commence et combien j'en prends*/
			$res=mysqli_query($lien,$req);
			if(!$res)
			    {
				echo "Erreur SQL:$req<br>".mysqli_error($lien);
			    }
			else
			    {
				while($tableau=mysqli_fetch_array($res))
				    {
					echo "<h2>".$tableau['nom']."</h2>";
					echo "<p>".$tableau['commentaire']."</p>";
				    }
			    }
			
			$req="SELECT * FROM commentaires";
			$res=mysqli_query($lien,$req);
			if(!$res)
			    {
				echo "Erreur SQL:$req<br>".mysqli_error($lien);
			    }
			else
			    {
                $nbcomm=mysqli_num_rows($res); // Retourne le nombre de lignes dans un résultat. 
				$nbpages=ceil($nbcomm/$commparpage); /*Ceil arrondit a l'entier supérieur*/
				echo "<br> Pages : ";
                if ($page > 2)
                    {
                    echo "<a href='commentaires.php?page=1'> Début </a>";
                    echo "<a href='commentaires.php?page=".($page-1)."'> Précédente </a>";
                    for($i=($page-2);$i<=($page+2);$i++)
				        {
					echo "<a href='commentaires.php?page=$i'> $i </a>";
				        }
                    }
                else if ($page == 1)
                    {
                    for($i=$page;$i<=($page+4);$i++)
				        {
					    echo "<a href='commentaires.php?page=$i'> $i </a>";
				        }
                    }
                else if ($page == 2)
                    {
                    echo "<a href='commentaires.php?page=1'> Début </a>";
                    echo "<a href='commentaires.php?page=".($page-1)."'> Précédente </a>";
                    for($i=($page-1);$i<=($page+3);$i++)
                        {
                        echo "<a href='commentaires.php?page=$i'> $i </a>";
                        }
                    }
			    }
			echo "<a href='commentaires.php?page=".($page+1)."'> Suivante </a>";
			echo "<a href='commentaires.php?page=$nbpages'> Fin </a>";
			
			mysqli_close($lien);
		?>	
	</body>
</html>