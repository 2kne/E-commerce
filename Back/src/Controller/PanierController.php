<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Panier;
use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

class PanierController extends AbstractController
{
    //  /**
    //  * @Route("/Panier/Create", name="CreatePanier")
    //  */
    // public function Panier(Request $request)
    // {
    //     $entityManager = $this->getDoctrine()->getManager();
        
    //         //$panier = new Panier();
    //         $_SESSION['Panier']=NULL;
    //         $_SESSION['id_article'] = NULL;
    //         $_SESSION['quantity'] = NULL;
    //     return true;
    //     // $entityManager->persist($panier);
    //     // $entityManager->flush();
    //     //return new JsonResponse('success', 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    // }

    // /**
    //  * @Route("/Article/Add", name="AddArticle")
    //  */
    // public function ajouterArticle(Request $request)
    // {

    // //Si le panier existe
    //     if ($this->Panier($request))
    //     {
    //         //Si le produit existe déjà on ajoute seulement la quantité
    //         $positionProduit = ($_SESSION['Panier']);

    //         if ($positionProduit !== false)
    //         {
    //             $_SESSION['quantity'][$positionProduit] += $qteProduit ;
    //         }
    //         else
    //         {
    //             //Sinon on ajoute le produit
    //             array_push( $_SESSION['id_article'],$id_article);
    //             array_push( $_SESSION['quantity'],$quantity);
    //         }
    //     }
    //     return new JsonResponse('success', 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    // }
}
