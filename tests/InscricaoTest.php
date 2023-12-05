<?php

class InscricaoTest extends \PHPUnit\Framework\TestCase {
    private $conexao;
    public function setUp(): void {
        try {    
            $this->conexao = new PDO('sqlite:db/teste.sqlite3');
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function testDisciplinaComPreRequisito() {
        
    }


public function testPOST()
{
    // create our http client (Guzzle)
    $client = new Client('http://localhost:8000', array(
        'request.options' => array(
            'exceptions' => false,
        )
    ));

    $nickname = 'ObjectOrienter'.rand(0, 999);
    $data = array(
        'nickname' => $nickname,
        'avatarNumber' => 5,
        'tagLine' => 'a test dev!'
    );

    $request = $client->post('/api/programmers', null, json_encode($data));
    $response = $request->send();
}

}
?>