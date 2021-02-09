<?php
namespace App\Controller\Back;

use App\Entity\ToDoList;
use App\Service\ToDoListService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/rest/todolist", name="default_index", methods={"GET"})
 */
class TDLController extends AbstractController
{
    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(Request $request, ToDoListService $toDoListService, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $content = json_decode($request->getContent());
        $user = $userRepository->find($content->{'user'});
        $name = $content->{'name'};
        $description = $content->{'content'};
        
        try {
            $tdl = $toDoListService->createToDoList($user, $name, $description);
            $tdl = $serializer->serialize($tdl, 'json', []);
            $response = new JsonResponse(['data' => $tdl]);

        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $response;
    }
}