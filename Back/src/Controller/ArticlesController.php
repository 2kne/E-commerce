<?php

namespace App\Controller;

use App\Entity\Articles;
// use Symfony\Component\Validator\Constraints\MinLength;
// use Symfony\Component\Validator\Constraints\MaxLength;
// use Symfony\Component\Validator\Constraints\Length;
use App\Entity\Categories;
use App\Entity\SousCategories;
use App\Repository\ArticlesRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Doctrine\DBAL\Connection;


class ArticlesController extends AbstractController
{

    /**
     * @Route("/all", name="all")
     */
    public function displayArticles()
    {
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repo->createQueryBuilder('q')
            ->orderBy('q.visites', 'DESC')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($articles, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/stock", name="sto")
     */
    public function orderbystock()
    {
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repo->createQueryBuilder('q')
            ->addOrderBy('q.stock', 'ASC')
            ->addOrderBy('q.order_stock', 'ASC')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($articles, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }


    /**
     * @Route("/images/{id}", name="image")
     */
    public function displayImages($id) {
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $article = $repo->createQueryBuilder('z')
            ->andWhere('z.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();


        foreach ($article as $key => $value) {
            dump($article);
        }

//
//        $tmp = $article[0]['id'];
//        $tmp2 = $article[0]['images'][0][0]['nom'];
//        $tmp3 = $article[0]['images'][0][0]['url'];
//
//        array_push($response['images']['name'],$tmp2);
//        array_push($response['images']['url'],$tmp3);
//        array_push($response['images']['id'],$tmp);

        return new JsonResponse($article[0]['images'], 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }


    /**
     * @Route("/visit/{id}",  name="visit")
     */
    public function updateVisit($id)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repo->findOneBy(['id'=> $id]);

        $visit = $articles->getVisites();
        $visit += 1;

        $articles->setVisites($visit);

        $entityManager->persist($articles);
        $entityManager->flush();


        return new JsonResponse("success", 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     *@Route("/search/date/{params}",  name="date")
     */
    public function dateFilter($params)
    {
        $repo = $this->getDoctrine()->getRepository(Articles::class);


        $articles = $repo->createQueryBuilder('q')
            ->orderBy('q.createdAt', $params)
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($articles, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/search/price/{params}",  name="price")
     */
    public function priceFilter($params)
    {
        $repo = $this->getDoctrine()->getRepository(Articles::class);

        $articles = $repo->createQueryBuilder('q')
            ->orderBy('q.prix', $params)
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($articles, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/search/filter",  name="search_filter")
     */
    public function search_filter(ObjectManager $manager, Request $request)
    {

        /* $repo = $this->getDoctrine()->getRepository(Articles::class);
         $article = $repo->createQueryBuilder('q')
             ->andWhere('q.titre LIKE :search')
             ->andWhere('q.prix <= :max')
             ->andWhere('q.prix >= :min')
             ->setParameter('search', '%'.$request->get('input') . '%')
             ->setParameter('max', $request->get('max'))
             ->setParameter('min', $request->get('min'))
             ->getQuery()
             ->getArrayResult();*/
        $searchby = $request->get('searchby');
        $search = '%'.$request->get('input') . '%';
        $min = $request->get('min');
        $max = $request->get('max');
        $orderby = $request->get('order1');
        $order = $request->get('order2');
        $stock = $request->get('stock');
        $categorie = $request->get('categorie');

        $string = ' AND ';
        foreach ($categorie as $value) {
            $or = " OR ";
            if ($value === end($categorie)) {
                $or = '';
            }
            $tmp = 'id_sous_categorie = '.$value . $or;
            $string = $string . $tmp;
        }
        if (count($categorie) === 0) {
            $string = '';
        }

        $conn = $this->getDoctrine()->getManager();

        $sql = "SELECT * FROM articles WHERE `".$searchby."` LIKE '".$search."' AND prix >= ".$min." AND prix <= ".$max." AND stock ".$stock.$string." ORDER BY `".$orderby."` " . $order;

        $stmt = $conn->getConnection()->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        $article = $stmt->fetchAll();
        foreach ($article as $value) {
            $tmp = unserialize($value['images']);
            $value['images'] = 0;
        }

        return new JsonResponse($article,200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/search/name/{params}",  name="name")
     */
    public function titleFilter($params)
    {
        $repo = $this->getDoctrine()->getRepository(Articles::class);

        $articles = $repo->createQueryBuilder('q')
            ->where('q.titre LIKE :titre')
            ->setParameter('titre', '%'.$params . '%')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($articles, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/search/{params}",  name="search")
     */
    public function wordFilter(Request $request)
    {

        $filter = $request->request->get('filter');


        $repo = $this->getDoctrine()->getRepository(Articles::class);

        $articles = $repo->createQueryBuilder('q')
            ->andWhere('q.prix > :prix')
            ->andWhere('q.titre LIKE :titre')
            ->andWhere('q.description LIKE :description')
            ->setParameter('prix', $filter[0])
            ->setParameter('titre', '%'. $filter[1] . '%')
            ->setParameter('description', "%" . $filter[2]. '%')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($articles, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/search/bar/{search}",  name="SearchBar")
     */
    public function BarreDeRecherche(Request $request, $search, ArticlesRepository $articles)
    {
       
        $em = $this->getDoctrine()->getManager();
        // $form = $request->query->get('form');
        // $search = $form['search'];
        //dd($search);
        $search = '%'.$search.'%';
       // dd($search);
        
        $resultats = $articles->findBySearch($search);
        //return ['search' => $id, 'resultats' => $resultats];
        //$search = NULL;
        // $formulaire = $this->createFormBuilder()
        // ->setAction($this->generateUrl('search_results', array('search' => $search)))
        //     ->add('search', SearchTitre::class, array('constraints' => new Length(array('min' => 3)), 'attr' => array('placeholder' => 'Rechercher un produit') ))
        //     ->add('send', SubmitTitre::class, array('label' => 'Envoyer'))
        //     ->getForm();

        // $formulaire->handleRequest($request);
        // if($formulaire->isSubmitted() && $formulaire->isValid())
        // {
        //     $search = $formulaire['search']->getData();

        // } 
        
        // return['formulaire' => $formulaire->createView()];
        dd($resultats);
        //return new JsonResponse($resultats, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/cat/{id_cat}", name="articleCat")
     */
    public function articleCat($id_cat) {

        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repo->createQueryBuilder('q')
            ->Where('q.id_categorie = :id_cat')
            ->setParameter('id_cat', $id_cat)
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($articles, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

    }
    
    /**
     * @Route("/product/{id}",  name="product")
     */
    public function product($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repo->createQueryBuilder('q')
            ->where('q.id=:id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getArrayResult();


        return new JsonResponse($articles, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

    }

    /**
     * @Route("/breadProduct/{product}",  name="bread")
     */
    public function breadProduct($product) {


        $article = $this->getDoctrine()->getRepository(Articles::class);
        $article = $article->findOneBy(['id' => $product]);
        $souscat = $article->getIdSousCategorie();


        $categorie = $this->getDoctrine()->getRepository(SousCategories::class);
        $categorie1 = $categorie->find($souscat);
        $categorie = $this->getDoctrine()->getRepository(Categories::class);
        $categorie = $categorie->createQueryBuilder('c')
            ->where('c.id=:id')
            ->setParameter(':id',  $categorie1->getIdCategorie())
            ->getQuery()
            ->getArrayResult();

        $sous_categorie = $this->getDoctrine()->getRepository(SousCategories::class);
        $sous_categorie = $sous_categorie->createQueryBuilder('sc')
            ->where('sc.id=:id')
            ->setParameter(':id', $souscat)
            ->getQuery()
            ->getArrayResult();
        $result = [$categorie[0]['name'], $sous_categorie[0]['name']];



        return new JsonResponse($result, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

    }

    /**
     * @Route("/breadCat/{id_cat}")
     */
    public function breadCat($id_cat) {

        $categorie = $this->getDoctrine()->getRepository(Categories::class);
        $categorie = $categorie->findOneBy(['id' => $id_cat]);
        $categorie = $categorie->getName();

        return new JsonResponse($categorie, 200 ,['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

    }


}
