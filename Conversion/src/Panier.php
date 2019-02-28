<?php

namespace Conversion;

class Panier
{

	private $produits;
	private $devisePanier;
	private $totalPanier;

	public function __construct($devise)
	{
		$this->devisePanier = $devise;
	}

	public function getProduits()
    {
        return $this->produits;
    }

    public function getDevise()
    {
    	return $this->devisePanier;
    }

    public function getTotalPanier()
    {
        return $this->totalPanier;
    }

    public function setDevise($nouvelleDevise)
    {
        $this->devisePanier = $nouvelleDevise;
    }

    public function setTotalPanier($nouveauTotal)
    {
        $this->totalPanier = $nouveauTotal;
    }

    //On ajoute un produit si celui n'existe pas encore. Dans le cas où il existe, on met à jour la quantité
    public function addProduit(Produit $produit)
    {
    	//Aucun produit dans le panier, on ajoute automatiquement
		if(count($this->produits) == 0)
		{
			echo '<p>Produit '.$produit->getNom().' ajouté au panier.</p>';
			$this->produits[] = $produit;
			end($this->produits)->calculerTotal();
		}
		else
		{
  			$produitExiste = $this->getIndexOfProduit($produit);
    		if($produitExiste == -1)
    		{
    			echo '<p>Produit '.$produit->getNom().' ajouté au panier.</p>';
        		$this->produits[] = $produit;
        		end($this->produits)->calculerTotal();
        	}
        	else //Produit existe déjà, donc maj de la quantité
        	{
        		echo '<p>Produit '.$produit->getNom().' déjà existant, mise à jour de la quantité.</p>';
        		$this->produits[$produitExiste]->setQuantite($this->produits[$produitExiste]->getQuantite() + $produit->getQuantite()); //Maj prix produit
        		$this->produits[$produitExiste]->calculerTotal();
        	}
    	}
    	$this->totalPanier(); //Maj prix panier
    }

    //On augmente la quantité d'un produit
    public function increaseQuantite(Produit $produit,$quantite)
    {
    	if(count($this->produits) == 0)
		{
			echo "Votre panier est vide";
		}
		else
		{
	    	$produitExiste = $this->getIndexOfProduit($produit);
	    	if($produitExiste == -1)
	    	{
	    		echo "Ce produit n'est pas dans votre panier<br/>";
	    	}
	    	else
	    	{
	    		if(!is_int($quantite) || $quantite < 0)
	    		{
	    			echo 'Quantité non valide';
	    		}
	    		else
	    		{
	    			echo '<p>Quantité du produit '.$produit->getNom().' augmentée de '.$quantite.'</p>';
	    			$this->produits[$produitExiste]->setQuantite($this->produits[$produitExiste]->getQuantite() + $quantite);
        			$this->produits[$produitExiste]->calculerTotal();
        		}
	    	}
    	}
    	$this->totalPanier(); //Maj prix panier
    }

    //On retire le produit du panier
    public function removeProduit(Produit $produit)
    {

    	if(count($this->produits) == 0)
		{
			echo "Votre panier est vide";
		}
		else
		{
	    	$produitExiste = $this->getIndexOfProduit($produit);
	    	if($produitExiste == -1)
	    	{
	    		echo "Ce produit n'est pas dans votre panier<br/>";
	    	}
	    	else
	    	{
	    		echo '<p>Produit '.$produit->getNom().' retiré du panier</p>';
	    		unset($this->produits[$produitExiste]);
	    	}
    	}
    	$this->totalPanier(); //Maj prix panier

    }

    //On diminue la quantité d'un produit
    public function decreaseQuantite(Produit $produit,$quantite)
    {

    	if(count($this->produits) == 0)
		{
			echo "Votre panier est vide";
		}
		else
		{
	    	$produitExiste = $this->getIndexOfProduit($produit);
	    	if($produitExiste == -1)
	    	{
	    		echo "Ce produit n'est pas dans votre panier";
	    	}
	    	else
	    	{
	    		if(!is_int($quantite) || $quantite < 0)
	    		{
	    			echo 'Quantité non valide';
	    		}
	    		else
	    		{
	    			if($this->produits[$produitExiste]->getQuantite() <= $quantite)
	    			{
	    				$this->removeProduit($produit);
	    			}
	    			else
	    			{
	    				echo '<p>Quantité du produit '.$produit->getNom().' diminuée de '.$quantite.'</p>';
	    				$this->produits[$produitExiste]->setQuantite($this->produits[$produitExiste]->getQuantite() - $quantite);
        				$this->produits[$produitExiste]->calculerTotal();
	    			}
	    		}
	    	}
    	}
    	$this->totalPanier(); //Maj prix panier

    }

    //Si produit dans le panier, on retourne l'indice
    public function getIndexOfProduit(Produit $produit)
    {

    	foreach ($this->produits as $index => $unProduit) 
    	{  		
    		if(($unProduit->getNom() === $produit->getNom()) && ($unProduit->getPrix() === $produit->getPrix()))
    		{
    			return $index;
    		}
    	}
    	return -1;
    }

    //Calcul du prix total du panier
    public function totalPanier()
    {
        $this->totalPanier = 0;

        foreach ($this->produits as $index => $produit) 
        {
        	//Si devise produit différente de celle du panier, on fait la conversion
            if ($produit->getDevise() != $this->devisePanier)
            {
                $devise =  json_decode(file_get_contents('https://api.exchangeratesapi.io/latest?base=' . $produit->getDevise() . '&symbols='. $this->devisePanier.''), true);
                $devisepanier = $devise['rates'][$this->devisePanier];
                $tarifs = $devisepanier * $produit->getPrix();
                $this->setTotalPanier($this->totalPanier + ($produit->getQuantite())*($tarifs));
            }
            else //Sinon aucun conversion, donc calcul du prix sans conversion
            {
                $this->setTotalPanier($this->totalPanier + ($produit->getQuantite())*($produit->getPrix()));
            }
        }
    }
}