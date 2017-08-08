<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace MYD\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MYD\PlatformBundle\Entity\Advert;
use MYD\PlatformBundle\Entity\Image;
use MYD\PlatformBundle\Entity\Application;
use MYD\PlatformBundle\Entity\Categories;


class AdvertController extends Controller
{
  public function indexAction($page)
  {
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
    if ($page < 1) {
      // On déclenche une exception NotFoundHttpException, cela va afficher
      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

    // Ici, on récupérera la liste des annonces, puis on la passera au template

    $listAdverts = $this->getDoctrine()
    ->getRepository('MYDPlatformBundle:Advert')
    ->findAll(); 

    // Mais pour l'instant, on ne fait qu'appeler le template
    return $this->render('MYDPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts ));
  }

  public function viewAction($id)
  {

    $em = $this->getDoctrine()->getManager();
    // Ici, on récupérera l'annonce correspondante à l'id $id
    $advert = $em
    ->getRepository('MYDPlatformBundle:Advert')
    ->find($id)
    ;

    // $advert est donc une instance de MYD\PlatformBundle\Entity\Advert
    // ou null si l'id $id  n'existe pas, d'où ce if :
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    $applications = $em
    ->getRepository('MYDPlatformBundle:Application')
    ->findBy(array('advert' => $advert))
    ;

    return $this->render('MYDPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert, 
      'applications' => $applications
      ));
  }

  public function addAction(Request $request)
  {
    // Création de l'entité
    $advert = new Advert();
    $advert->setTitle('Super projet !');
    $advert->setAuthor('Marion');
    $advert->setContent("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe recusandae molestiae, dicta accusantium, veniam nisi deserunt minima facilis soluta tempora voluptatem hic. Minima error inventore, animi, atque officia saepe laudantium.");
    // On peut ne pas définir ni la date ni la publication,
    // car ces attributs sont définis automatiquement dans le constructeur

    $image = new Image(); 
    $image->setUrl('https://www.w3schools.com/css/img_fjords.jpg'); 
    $image->setAlt('Fjords');

    $advert->setImage($image); 

    // Création d'une première candidature
    $application1 = new Application();
    $application1->setAuthor('Marine');
    $application1->setContent("J'ai toutes les qualités requises.");

    // Création d'une deuxième candidature par exemple
    $application2 = new Application();
    $application2->setAuthor('Pierre');
    $application2->setContent("Je suis très motivé.");

    // On lie les candidatures à l'annonce
    $application1->setAdvert($advert);
    $application2->setAdvert($advert);

    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
    $em->persist($advert);

    // Étape 1 ter : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
    // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
    $em->persist($application1);
    $em->persist($application2);


    // Étape 2 : On « flush » tout ce qui a été persisté avant
    $em->flush();

    // Reste de la méthode qu'on avait déjà écrit
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // Puis on redirige vers la page de visualisation de cettte annonce
      return $this->redirectToRoute('myd_platform_view', array('id' => $advert->getId()));
    }

    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('MYDPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
  }

  public function editAction($id, Request $request)
  {
    // Ici, on récupérera l'annonce correspondante à $id

    // Même mécanisme que pour l'ajout
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirectToRoute('myd_platform_view', array('id' => $id));
    }

    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('MYDPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // La méthode findAll retourne toutes les catégories de la base de données
    $listCategories = $em->getRepository('MYDPlatformBundle:Category')->findAll();

    // On boucle sur les catégories pour les lier à l'annonce
    foreach ($listCategories as $category) {
      $advert->addCategory($category);
    }

    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

    // Étape 2 : On déclenche l'enregistrement
    $em->flush();

    return $this->render('MYDPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert
      ));
  }

  public function deleteAction($id)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // On boucle sur les catégories de l'annonce pour les supprimer
    foreach ($advert->getCategories() as $category) {
      $advert->removeCategory($category);
    }

    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

    // On déclenche la modification
    $em->flush();

    return $this->render('MYDPlatformBundle:Advert:delete.html.twig');
  }

  public function menuAction($limit)
  {
    // On fixe en dur une liste ici, bien entendu par la suite
    // on la récupérera depuis la BDD !
    $listAdverts = $this->getDoctrine()
    ->getRepository('MYDPlatformBundle:Advert')
    ->findBy(array(), ['id' => 'DESC' ], $limit, null); 

    return $this->render('MYDPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'listAdverts' => $listAdverts
      ));
  }
}