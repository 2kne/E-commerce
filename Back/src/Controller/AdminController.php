<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route ("/admin/add_article", name="admin_new_article")
     */
    public function AddArticles(Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();

        $article = new Articles();

        $article->setFournisseur($request->request->get('fournisseur'));
        $article->setDescription($request->request->get('description'));
        $article->setTitre($request->request->get('titre'));
        $article->setPrix($request->request->get('prix'));
        $article->setImages($request->request->get('images'));
        $article->setCaracteristiques($request->request->get('caracteristiques'));
        $article->setIdCategorie($request->request->get('id_cat'));
        $article->setIdSousCategorie($request->request->get('id_categorie'));
        $article->setStock($request->request->get('stock'));
        $article->setCreatedAt(new \DateTime());
        $article->setVisites(0);

        $entityManager->persist($article);
        $entityManager->flush();

        return new JsonResponse('success', 200,['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/admin/edit/{id}", name="edit_article")
     */
    public function UpdateArticles(Request $request,$id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = $entityManager->getRepository(Articles::class)->findOneBy(['id'=>$id]);
        $article->setDescription($request->request->get('description'));
        $article->setTitre($request->request->get('titre'));
        $article->setPrix($request->request->get('prix'));
        $article->setImages($request->request->get('images'));
        $article->setCaracteristiques($request->request->get('caracteristiques'));
        $article->setIdCategorie($request->request->get('id_categorie'));
        $article->setStock($request->request->get('stock'));
        $article->setUpdatedAt(new \DateTime());

        $entityManager->persist($article);
        $entityManager->flush();
        return new JsonResponse('success', 200,['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }


    /**
     * @Route("/ArticleDelete/{id}", name="Delete")
     */
    public function DeleteArticles(Articles $articles)
    {
        $EntityManager = $this->getDoctrine()->getManager();
        $EntityManager->remove($articles);
        $EntityManager->Flush();

        return new JsonResponse('success', 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @Route ("/admin/add_categories", name="admin_new_categories")
     */
    public function AddCategorie(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $categories = new Categories();
        $categories->setName($request->request->get("name"));

        $entityManager->persist($categories);
        $entityManager->flush();
        return new JsonResponse('success', 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

    }

    /**
     * @Route("/deleteCategorie/{id}", name="deleteCategorie")
     */
    public function DeleteCategorie(Categories $categories)
    {
        dump($categories);
        $EntityManager = $this->getDoctrine()->getManager();
        $EntityManager->remove($categories);
        $EntityManager->Flush();
        return new JsonResponse('success', 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }


    /**
     * @Route("/admin/stock/{id}", name="stock")
     */
    public function Stock($id, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repo->findOneBy(['id'=> $id]);

        $stock = $articles->getStock();
        $stock += $request->request->get('stock');

        $articles->setStock($stock);

        $entityManager->persist($articles);
        $entityManager->flush();


        return new JsonResponse('success', 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }
}
