<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {

        $repository = $this->getDoctrine()->getRepository(Category::class);

        $categories = $repository->findAll();

        return $this->render('category/index.html.twig', [
            "categories" => $categories,
        ]);
    }

    /**
     * @Route("/category/ajouter", name="ajouter")
     */
    public function ajouter(Request $request)
    {
        $categorie = new Category();

        //creation du formulaire
        $formulaire = $this->createForm(CategoryType::class, $categorie);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid())
        {
            //récuperer l'entity manager (sorte de connexion à la BDD
            $em = $this->getDoctrine()->getManager();

            //je dis au manager que je veux ajouter la categorie dans la BDD
            $em->persist($categorie);

            $em->flush();

            return $this->redirectToRoute("category");
        }

        return $this->render('category/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Ajouter une categorie ",
        ]);
    }

    /**
     * @Route("/category/modifier/{id}", name="modifier")
     */
    public function modifier(int $id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categorie = $repository->find($id);

        //creation du formulaire
        $formulaire = $this->createForm(CategoryType::class, $categorie);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid())
        {
            //récuperer l'entity manager (sorte de connexion à la BDD
            $em = $this->getDoctrine()->getManager();

            //je dis au manager que je veux ajouter la categorie dans la BDD
            $em->persist($categorie);

            $em->flush();

            return $this->redirectToRoute("category");
        }

        return $this->render('category/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Modification de la categorie <i>".$categorie->GetTitre()."</i>",
        ]);
    }
    /**
     * @Route("/category/supprimer/{id}", name="supprimer")
     */
    public function delete(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categorie = $repository->find($id);

        $formulaire = $this->createFormBuilder()
            ->add("submit", SubmitType::class, ["label" =>"OK", "attr"=>["class"=>"btn btn-success"]])
            ->getForm();

        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->remove($categorie);

            $em->flush();

            return $this->redirectToRoute("category");
        }

        return $this->render('category/formulaire.html.twig', [
            'controller_name' => 'CategoriesController',
            'formulaire'=> $formulaire->createView(),
            "h1" => "Supprimer la categorie <i>".$categorie->getTitre()."</i>"
        ]);
    }
}
