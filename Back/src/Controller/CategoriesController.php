<?php

namespace App\Controller;


use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\SousCategories;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



class CategoriesController extends AbstractController
{

    /**
     *@Route("/cat", name="categories")
     */
    public function displayCat()
    {

        $repo = $this->getDoctrine()->getRepository(Categories::class);
        $cat = $repo->createQueryBuilder('p')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($cat, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }


    /**
     * @Route("/cate", name="cate")
     */
    public function displayCate() {

        $repo = $this->getDoctrine()->getRepository(Categories::class);
        $cat = $repo->createQueryBuilder('a')
            ->getQuery()
            ->getResult();


        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);


        $myResponse = $serializer->serialize($cat, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            'json_encode_options' => JSON_UNESCAPED_SLASHES
        ]);

        return new Response($myResponse,200,['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
    }


    /**
     * @Route("/cate/{id}", name="sousCatId")
     */
    public function catId($id) {

        $repo = $this->getDoctrine()->getRepository(SousCategories::class);
        $sousCat = $repo->createQueryBuilder('a')
            ->where('a.id_categorie = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($sousCat, 200 , ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

    }

    /**
     * @Route("/sousCat", name="sousCat")
     */
    public function displaySousCat() {

        $repo = $this->getDoctrine()->getRepository(SousCategories::class);
        $sousCat = $repo->createQueryBuilder('p')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($sousCat, 200,['Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json']);

    }


    /**
     * @Route("/categorie/{sousCat}" ,name="lo")
     */
    public function getArticlesSousCat($sousCat) {

        $repo = $this->getDoctrine()->getRepository(Articles::class);

        $sous_categories = $repo->createQueryBuilder('a')
            ->where('a.id_sous_categorie = :id')
            ->setParameter('id', $sousCat)
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($sous_categories, 200,['Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json']);


    }

}
