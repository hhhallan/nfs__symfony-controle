<?php

namespace App\Controller;

use App\Entity\Objet;
use App\Repository\ObjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $objetRepository;

    public function __construct(ObjetRepository $objetRepository) {
        $this->objetRepository = $objetRepository;
    }


    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $objets = $this->objetRepository->findAll();
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'objets' => $objets
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/create', name: 'api_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $manager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['synopsis']) || !isset($data['type']) || !isset($data['release_date'])) {
            return new JsonResponse(['error' => 'Les données sont incorrectes'], RESPONSE::HTTP_BAD_REQUEST);
        }

        $object = new Objet();
        $object
            ->setName($data['name'])
            ->setSynopsis($data['synopsis'])
            ->setType($data['type'])
            ->setReleaseDate(new \DateTime($data['release_date']));

        $manager->persist($object);
        $manager->flush();

        return new JsonResponse(['message' => 'Objet créé avec succès!'], RESPONSE::HTTP_CREATED);
    }

    #[Route('/getAll', name: 'api_get_all', methods: ['GET'])]
    public function getAll(ObjetRepository $objetRepository): JsonResponse
    {
        $objects = $this->objetRepository->findAll();
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
        $objet = $this->objetRepository->find($id);
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
