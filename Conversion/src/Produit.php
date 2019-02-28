<?php

namespace Conversion;

class Produit
{

	private $nom;
	private $quantite;
	private $prix;
	private $total;
	private $devise;

	public function __construct($nomProduit, $quantiteProduit, $prixProduit, $deviseProduit)
	{
		$this->nom = $nomProduit;
		if(!is_int($quantiteProduit) || $quantiteProduit < 0)
		{
			$this->quantite = 0;
		}
		else
		{
			$this->quantite = $quantiteProduit;
		}
		if(!is_int($prixProduit) || $prixProduit < 0)
		{
			$this->prixProduit = 0;
		}
		else
		{
			$this->prix = $prixProduit;
		}
		$this->devise = $deviseProduit;
	}

	public function getNom() 
	{
		return $this->nom;
	}

	public function getQuantite()
	{
		return $this->quantite;
	}

	public function getPrix()
	{
		return $this->prix;
	}

	public function getDevise()
	{
		return $this->devise;
	}

	public function getTotal()
	{
		return $this->total;
	}

	public function setTotal($nouveauTotal)
	{
		$this->total = $nouveauTotal;
	}

	public function setNom($nouveauNom) 
	{
		$this->nom = $nouveauNom;
	}

	public function setQuantite($nouvelleQuantite)
	{
		if($nouvelleQuantite > 0)
		{
			$this->quantite = $nouvelleQuantite;
			$this->calculerTotal();
		}
		else
		{
			echo "Quantité non valide";
		}
	}

	public function setPrix($nouveauPrix)
	{
		if($nouveauPrix > 0)
		{
			$this->prix = $nouveauPrix;
			$this->calculerTotal();
		}
		else
		{
			echo "Prix non valide";
		}
	}

	public function setDevise($nouvelleDevise)
	{
		$this->devise = $nouvelleDevise;
	}

	//Fonction pour calculer le prix d'un produit
	public function calculerTotal()
	{
		$this->setTotal(($this->getQuantite())*($this->getPrix()));
	}

}