<?php
namespace App\Controller\Back;

use Exception;
use App\Entity\Item;
use App\Service\ToDoListService;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\ToDoListRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/rest/todolist", name="default_index", methods={"GET"})
 */
class TDLController extends AbstractController
{
    private $toDoListService;
    private $serializer;

    public function  __construct(ToDoListService $toDoListService)
    {
        $this->toDoListService = $toDoListService; 

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getItems();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $this->serializer = new Serializer([$normalizer], [$encoder]);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(Request $request, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $content = json_decode($request->getContent());
        
        try {
            if (empty($content->{'name'}))
                throw new Exception("Undefined property: name (the TodoList name)");
            if (empty($content->{'user'}))
                throw new Exception("Undefined property: user (the TodoList owner id)");
            if (empty($content->{'content'}))
                throw new Exception("Undefined property: content (the TodoList content)");

            $user = $userRepository->find($content->{'user'});
            if (empty($user))
                throw new Exception("No user found with this id.");

            $name = $content->{'name'};
            $description = $content->{'content'};

            $tdl = $this->toDoListService->createToDoList($user, $name, $description);

            $tdl = $serializer->serialize($tdl, 'json', []);
            $response = new JsonResponse($tdl, 201);
        } catch (Exception $e) {
            $e = $serializer->serialize($e->getMessage(), 'json', []);
            return new JsonResponse($e, 400);
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
        
        try {
            if (empty($content->{'name'}))
                throw new Exception("Undefined property: name (the TodoList name)");
            if (empty($content->{'content'}))
                throw new Exception("Undefined property: content (the TodoList content)");
            if (empty($content->{'todolist'}))
                throw new Exception("Undefined property: todolist (the TodoList id)");
                    
            $tdl = $toDoListRepository->find($content->{'todolist'});
            if (empty($tdl))
                throw new Exception("No todolist found with this id.");

            $item = (new Item)
                ->setName($content->{'name'})
                ->setContent($content->{'content'})
                ->setToDoList($tdl);

            $tdl = $toDoListService->addItem($tdl, $item);
            $tdl = $this->serializer->serialize($tdl, 'json');
            
            $response = new JsonResponse($tdl, 201);
            ob_flush();
        } catch (Exception $e) {
            $e = $serializer->serialize($e->getMessage(), 'json', []);
            return new JsonResponse($e, 400);
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

        try {
            if (empty($content->{'item'}))
                throw new Exception("Undefined property: item (the item id)");
            if (empty($content->{'todolist'}))
                throw new Exception("Undefined property: todolist (the TodoList id)");

            $tdl = $toDoListRepository->find($content->{'todolist'});
            if (empty($tdl))
                throw new Exception("No todolist found with this id.");

            $item = $itemRepository->find($content->{'item'});
            if (empty($item))
                throw new Exception("No item found with this id.");

            $tdl = $toDoListService->removeItem($tdl, $item);

            $tdl = $this->serializer->serialize($tdl, 'json');
            $response = new JsonResponse($tdl, 200);
        } catch (Exception $e) {
            $e = $serializer->serialize($e->getMessage(), 'json', []);
            return new JsonResponse($e, 400);
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

            $tdl = $this->serializer->serialize($tdl, 'json');
            $response = new JsonResponse($tdl, 200);
        } catch (Exception $e) {
            $e = $serializer->serialize($e->getMessage(), 'json', []);
            return new JsonResponse($e, 404);
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

            $user = $serializer->serialize($user, 'json', []);
            $response = new JsonResponse($user, 200);
        } catch (Exception $e) {
            $e = $serializer->serialize($e->getMessage(), 'json', []);
            return new JsonResponse($e, 404);
        }

        return $response;
    }
}