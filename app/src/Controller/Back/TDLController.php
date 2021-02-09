<?php
namespace App\Controller\Back;

use App\Entity\Item;
use App\Entity\ToDoList;
use App\Service\ToDoListService;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\ToDoListRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

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



    /**
     * @Route("/add-item", name="add_item", methods={"POST"})
     */
    public function addItem(Request $request, ToDoListService $toDoListService, 
        ToDoListRepository $toDoListRepository, SerializerInterface $serializer): JsonResponse
    {
        $content = json_decode($request->getContent());

        $tdl = $toDoListRepository->find($content->{'todolist'});

        $item = (new Item)
            ->setName($content->{'name'})
            ->setContent($content->{'content'})
            ->setToDoList($tdl);

        try {
            $tdl = $toDoListService->addItem($tdl, $item);

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

    /**
     * @Route("/remove-item", name="remove_item", methods={"POST"})
     */
    public function removeItem(Request $request, ToDoListService $toDoListService, ItemRepository $itemRepository, 
        ToDoListRepository $toDoListRepository, SerializerInterface $serializer): JsonResponse
    {
        $content = json_decode($request->getContent());

        $tdl = $toDoListRepository->find($content->{'todolist'});
        $item = $itemRepository->find($content->{'item'});

        try {
            $tdl = $toDoListService->removeItem($tdl, $item);
            
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

    /**
     * @Route("/get", name="get", methods={"GET"})
     */
    public function getToDoList(Request $request, ToDoListRepository $toDoListRepository, SerializerInterface $serializer): JsonResponse 
    {
        try {
            $tdl = $toDoListRepository->find($request->query->get('todolist'));
            
            if(empty($tdl)) {
                throw new Exception("This ToDoList doesn't exist");
            }

            $tdl = $serializer->serialize($tdl, 'json', []);
            $response = new JsonResponse(['data' => $tdl]);
        } catch (Exception $e) {
            return $this->json([
                'status' => 404,
                'message' => $e->getMessage()
            ]);
        }

        return $response;
    }



    /**
     * @Route("/get-todolist-user", name="get_todolist_user", methods={"GET"})
     */
    public function getToDoListUser(Request $request, ToDoListRepository $toDoListRepository,
        UserRepository $userRepository, SerializerInterface $serializer): JsonResponse 
    {
        try {
            $tdl = $toDoListRepository->find($request->query->get('todolist'));
            
            if(empty($tdl)) {
                throw new Exception("This ToDoList doesn't exist");
            }

            $user = $userRepository->find($tdl->getOwner());

            $tdl = $serializer->serialize($user, 'json', []);
            $response = new JsonResponse(['data' => $tdl]);
        } catch (Exception $e) {
            return $this->json([
                'status' => 404,
                'message' => $e->getMessage()
            ]);
        }

        return $response;
    }
}