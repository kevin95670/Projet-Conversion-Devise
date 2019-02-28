<?php 

require_once __DIR__ . '/vendor/autoload.php';

include __DIR__.'/src/Produit.php';
include __DIR__.'/src/Panier.php';

use Conversion\Produit;
use Conversion\Panier;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>Conversion</title>
</head>
<body>
	<header>
		<h1>Conversion</h1>
	</header>
	<main>

		<?php

		$jeux = new Produit("Mario Kart",3,60,'EUR');
		$films = new Produit("Kill Bill",3,50,'EUR');
		$encoreDesJeux = new Produit("Mario Kart",4,60,'EUR');
		$console = new Produit("PS4",1,250,'EUR');

		$panier = new Panier('USD');
		$panier->addProduit($jeux);
		$panier->addProduit($films);
		$panier->addProduit($console);
		$panier->addProduit($encoreDesJeux);

		?>

		<table>
			<thead>
				<tr>
					<th>Nom</th>
					<th>Quantité</th>
					<th>Prix</th>
					<th>Total</th>
					<th>Devise</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($panier->getProduits() as $index => $produit) 
        		{
        		?>
				<tr>
					<td><?php echo $produit->getNom();?></td>
					<td><?php echo $produit->getQuantite();?></td>
					<td><?php echo $produit->getPrix();?></td>
					<td><?php echo $produit->getTotal();?></td>
					<td><?php echo $produit->getDevise();?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="3">Total : </td>
				<td><?php echo $panier->getTotalPanier();?></td>
				<td><?php echo $panier->getDevise();?></td>
			</tr>
			</tfoot>
		</table> 

		<?php
		$panier->increaseQuantite($console, 2);
		$panier->removeProduit($encoreDesJeux);
		$panier->decreaseQuantite($films, 1);
		?>

		<table>
			<thead>
				<tr>
					<th>Nom</th>
					<th>Quantité</th>
					<th>Prix</th>
					<th>Total</th>
					<th>Devise</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($panier->getProduits() as $index => $produit) 
        		{
        		?>
				<tr>
					<td><?php echo $produit->getNom();?></td>
					<td><?php echo $produit->getQuantite();?></td>
					<td><?php echo $produit->getPrix();?></td>
					<td><?php echo $produit->getTotal();?></td>
					<td><?php echo $produit->getDevise();?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="3">Total : </td>
				<td><?php echo $panier->getTotalPanier();?></td>
				<td><?php echo $panier->getDevise();?></td>
			</tr>
			</tfoot>
		</table>

	</main>
</body>
</html>