<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article; //Ne pasoublier d'ajouter ce use pour la fonction index()
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Form\ArticleType;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo) //Quand Symfony va appeler la fonction index. Il va savoir que la fonction index() a besoin d'une instance de la classe ArticleRepository
    {
        $articles = $repo->findAll(); //Trouve moi tous les articles

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => "Bienvenue les amis"
        ]);
    }
    
    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, ManagerRegistry $manager)
    {
        if(!$article)
        {
            $article = new Article();
        }
        //$article = new Article();

        $article    ->setTitle("Titre de l'article")
                    ->setContent("le contenu de l'article");
        
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) //Condition : est-ce que le formulaire a été soumis et surtout est-il valide ?
        {
            if(!$article->getId())
            {
                $article->setCreatedAt(new \DateTime()); // On ajoute la date directement ici automatiquement
            }

            $manager->getManager()->persist($article); // Le manager se prépare à faire persister l'article
            $manager->getManager()->flush(); // On balance la requête.

            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId() //On se redirige vers le nouvel article fraichement créé
            ]);
        }

        return $this->render('blog/create.html.twig', [
            'formArticle'   => $form->createView(),
            'editMode'      => $article->getId() !== null
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article 
        ]); //On passe un tableau à twig avec les variables que je veux qu'il utilise
    }
}
