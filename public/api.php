<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App;
$app->get(
    '/hello/{name}',
    function (Request $request, Response $response, array $args) {
        $name = $args['name'];
        $response->getBody()->write("Hello, $name");
        return $response;
    }
);

$app->get(
    '/api/participants',
    function (Request $request, Response $response, array $args) {

        class MyDB extends SQLite3 {
            function __construct() {
                $this->open('../participants.db');
            }
        }
        $db = new MyDB();
        if(!$db) {
            echo $db->lastErrorMsg();
            exit();
        }

        $sql = "SELECT id, name, surname FROM customer";
        $ret = $db->query($sql);
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
            echo "id = ". $row['id'] . ", ";
            echo "name = ". $row['name'] . ", ";
            echo "surname = ". $row['surname'] ."<br>";
        }
        $db->close();

        $participants = [
            ['id' => 1, 'firstname' => 'John', 'lastname' => 'Doe'],
            ['id' => 2, 'firstname' => 'Kate', 'lastname' => 'Pig'],
            ['id' => 3, 'firstname' => 'Chris', 'lastname' => 'Lua'],
        ];
        return $response->withJson($participants);
    }
);

$app->get(
    '/api/participants/$id',
    function (Request $request, Response $response, array $args){
        $participants = [
            ['id' => 1, 'firstname' => 'John', 'lastname' => 'Doe'],
            ['id' => 2, 'firstname' => 'Kate', 'lastname' => 'Pig'],
            ['id' => 3, 'firstname' => 'Chris', 'lastname' => 'Lua'],
        ];
        return $response->withJson($participants/'$id');
    }
);

$app->run();

