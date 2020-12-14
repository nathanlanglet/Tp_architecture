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

				if ($page > $nbpages && $page != 1)
					{
					header('Location:commentaires.php?page='.$nbpages);
					}

				if ($nbpages >= 1)
					{
					echo "<br> Pages : ";
					}

				if ($page > 1)
					{
					echo "<a href='commentaires.php?page=1'> Début </a>";
					echo "<a href='commentaires.php?page=".($page-1)."'> Précédente </a>";
					}
				
				if ($nbpages > 0)
					{
					if ($page == 1)
						{
						$debut = $page;
						if ($nbpages >= $page+4 )
							{
							$fin = $page+4;			
							}
						else
							{
							$fin = $nbpages;
							}
						}
					else if ($page == 2)
						{
						$debut = $page-1;
						if ($nbpages >= $page+3)
							{
							$fin = $page+3;
							}
						else
							{
							$fin = $nbpages;
							}
						}
					else if ($page == $nbpages)
						{
						if ($page-4 > 0 )
							{
							$debut = $page-4;
							}
						else
							{
							$debut = 1;
							}
						$fin = $page;			
						}
					else if ($page == $nbpages-1)
						{
						if ($page-3 > 0)
							{
							$debut = $page-3;
							}
						else
							{
							$debut = 1;
							}
						$fin = $nbpages;				
						}
					else
						{
						$debut = $page-2;
						$fin = $page+2;
						}

					for($i=$debut;$i<=$fin;$i++)
						{
						if ($i == $page)
							{
							echo "<a style='color:red' href='commentaires.php?page=$i'> $i </a>";
							}
						else
							{
							echo "<a href='commentaires.php?page=$i'> $i </a>";
							}
						}		
					}

				if ($nbpages > $page)
					{
					echo "<a href='commentaires.php?page=".($page+1)."'> Suivante </a>";
					echo "<a href='commentaires.php?page=$nbpages'> Fin </a>";
					}
			    }
			
			mysqli_close($lien);
		?>	
	</body>
</html>