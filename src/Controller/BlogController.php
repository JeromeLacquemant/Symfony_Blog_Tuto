<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article; //Ne pasoublier d'ajouter ce use pour la fonction index()
use App\Repository\ArticleRepository;

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
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id); // Trouve moi l'article qui a cet identifiant

        return $this->render('blog/show.html.twig', [
            'article' => $article 
        ]); //On passe un tableau à twig avec les variables que je veux qu'il utilise
    }
}
