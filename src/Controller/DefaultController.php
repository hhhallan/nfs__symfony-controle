<?php

namespace App\Controller;

use App\Entity\Objet;
use App\Repository\ObjetRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/create', name: 'api_create', methods: ['POST'])]
    public function create(Request $request, EntityManager $manager): JsonResponse
    {
        $objet = new Objet();


//        return new JsonResponse(['message' => 'Objet créé avec succès!'], status, headers, json);
        return new JsonResponse(['message' => 'Objet créé avec succès!'], Response::HTTP_OK);
    }

    #[Route('/getAll', name: 'api_get_all', methods: ['GET'])]
    public function getAll(ObjetRepository $objetRepository): JsonResponse
    {
        $objects = $objetRepository->findAll();
        if (!$objects) {
            return new JsonResponse(['message' => 'Aucun film ou série n\'a été trouvé..'], Response::HTTP_NOT_FOUND);
        }

        $data = [];
        foreach ($objects as $object) {
            $data[] = [
                'id' => $object->getId(),
                'name' => $object->getName(),
                'type' => $object->getType(),
                'synopsis' => $object->getSynopsis(),
                'release_date' => $object->getReleaseDate()->format('Y-m-d'),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/get/{id}', name: 'api_get', methods: ['GET'])]
    public function get(int $id, ObjetRepository $objetRepository): JsonResponse
    {
        $objet = $objetRepository->find($id);
        if (!$objet) {
            return new JsonResponse(['message' => 'Le film ou la série n\'a pas été trouvé..'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'name' => $objet->getName(),
            'type' => $objet->getType(),
            'synopsis' => $objet->getSynopsis(),
            'release_date' => $objet->getReleaseDate()->format('Y-m-d')
        ], Response::HTTP_OK);
    }
}
