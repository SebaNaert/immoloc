<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdController extends AbstractController
{
    /**
     * Permet d'afficher les annonces
     *
     * @param AdRepository $repo
     * @return Response
     */
    #[Route('/ads', name: 'ads_index')]
    public function index(AdRepository $repo): Response
    {
        // appel au model
        $ads = $repo->findAll();

        // vue
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    #[Route('/ads/new', name: "ads_create")]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AnnonceType::class, $ad);
        // Permet de vérifier l'état du formulaire (envoyé ou non par exemple)
        $form->handleRequest($request);

        $arrayForm = $request->request->all();
        // isSubmitted : permet de vérifier si formulaire soumis ou non
        // isValie : permet de vérifier si formulaire est valide ou non
        if ($form->isSubmitted() && $form->isValid()) {

            // dump($arrayForm['annonce']);
            $manager->persist($ad);
            $manager->flush();
            return $this->redirectToRoute('ads_show', ['slug' => $ad->getSlug()]);
        }
        // dump($request);

        return $this->render('ad/new.html.twig', [
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page de l'annonce choisie par l'utilisateur avec son slug
     * Attention {slug} c'est paramConverter pas lié a Symfony Flex
     * @param Ad $ad
     * @return Response
     */
    #[Route('/ads/{slug}', name: 'ads_show')]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])]
        Ad $ad
    ): Response {
        return $this->render("ad/show.html.twig", [
            "ad" => $ad
        ]);
    }
}
