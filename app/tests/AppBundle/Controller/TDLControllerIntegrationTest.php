<?php 

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TDLControllerIntegrationTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->client = static::createClient();
    }

    /** 
     * Test de la création d'un TDL - succès
     * 
     * @test 
     */
    public function testCreateTDLSucces()
    {
        $this->client->request(
            'POST',
            '/rest/todolist/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "name":"My TDL",
                "user": 2,
                "content": "My content"
            }'
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(201);
    }
    
    /** 
     * Test de la création d'un TDL - fail: user n'existe pas
     * 
     * @test 
     */
    public function testCreateTDLFailWhenUserDoesntExist()
    {
        $this->client->request(
            'POST',
            '/rest/todolist/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "name":"My TDL",
                "user": 30,
                "content": "My content"
            }'
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(400);
    }
    
    /** 
     * Test de la création d'un TDL - fail: name n'est pas renseigné
     * 
     * @test 
     */
    public function testCreateTDLFailWhenNameIsNotFound()
    {
        $this->client->request(
            'POST',
            '/rest/todolist/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "user": 30,
                "content": "My content"
            }'
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(400);
    }
    
    /** 
     * Test de la création et l'ajout d'un item - success
     * 
     * @test 
     */
    public function testAddItemSuccess()
    {
        $this->client->request(
            'POST',
            '/rest/todolist/add-item',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "name":"Mon item",
                "todolist": 1,
                "content": "My content"
            }'
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(201);
    }

    /** 
     * Test de la création et l'ajout d'un item - fail: todolist n'existe pas 
     * 
     * @test 
     */
    public function testAddItemFailWhenTDLDoesntExist()
    {
        $this->client->request(
            'POST',
            '/rest/todolist/add-item',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "name":"My TDL",
                "content": "My content"
                "todolist": 30
            }'
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(400);
    }

    /** 
     * Test de la création et l'ajout d'un item - fail: content n'est pas renseigné
     * 
     * @test 
     */
    public function testAddItemFailWhenContentIsNotFound()
    {
        $this->client->request(
            'POST',
            '/rest/todolist/add-item',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "name":"My TDL",
                "todolist": 30
            }'
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(400);
    }

    /** 
     * Test de la suppression d'un item - success
     * 
     * @test 
     */
    public function testRemoveItemSuccess()
    {
        $this->client->request(
            'POST',
            '/rest/todolist/remove-item',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "item":1,
                "todolist":1
            }'
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(200);
    }

    /** 
     * Test de la suppression d'un item - fail: item n'existe pas 
     * 
     * @test 
     */
    public function testRemoveItemFailWhenItemDoesntExist()
    {
        $this->client->request(
            'POST',
            '/rest/todolist/remove-item',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "item":54,
                "todolist":1
            }'
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(400);
    }

    /** 
     * Test de la suppression d'un item - fail: item non renseigné
     * 
     * @test 
     */
    public function testRemoveItemFailWhenItemNotFound()
    {
        $this->client->request(
            'POST',
            '/rest/todolist/remove-item',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "todolist":1
            }'
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(400);
    }

    /** 
     * Test de la récupération d'une tdl - success
     * 
     * @test 
     */
    public function testGetTDLSuccess()
    {
        $this->client->request(
            'GET',
            '/rest/todolist/get?todolist=1',
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(200);
    }
   
    /** 
     * Test de la récupération d'une tdl - fail: tdl n'existe pas
     * 
     * @test 
     */
    public function testGetTDLFailWhenTDLDoesntExist()
    {
        $this->client->request(
            'GET',
            '/rest/todolist/get?todolist=1000',
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(404);
    }

    /** 
     * Test de la récupération d'une tdl - success
     * 
     * @test 
     */
    public function testGetUserTDLSuccess()
    {
        $this->client->request(
            'GET',
            '/rest/todolist/get-todolist-user?todolist=1',
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(200);
    }

    /** 
     * Test de la récupération d'une tdl - fail: tdl n'existe pas
     * 
     * @test 
     */
    public function testGetUserTDLFailWhenTDLDoesntExist()
    {
        $this->client->request(
            'GET',
            '/rest/todolist/get-todolist-user?todolist=1000',
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseStatusCodeSame(404);
    }
}