<?php

class Chats{
    public function send($data){
        session_start();

        $id = $_SESSION['auth'][0]['id'];
        ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
        $pdo = new PDO('mysql:host=localhost;dbname=bsit202_bautista_chatroom','root','');

        $query = 'INSERT INTO messages (account_id,message) VALUES(:account_id,:message)';
        $insert = $pdo->prepare($query);
        $insert->bindValue('account_id',$id);
        $insert->bindValue('message',$data['message']);
        $insert->execute();

        echo 'success';
    }

    public function collect(){
        session_start();

        $id = $_SESSION['auth'][0]['id'];
        $pdo = new PDO('mysql:host=localhost;dbname=bsit202_bautista_chatroom','root','');

        $stmt = $pdo->prepare('
                    SELECT a.id,a.name,ms.message FROM messages as ms
                        INNER JOIN accounts as a ON a.id = ms.account_id
                        ORDER BY ms.timestamp ASC
                    ');
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $html = '';
        foreach($data as $message){
            if($id == $message['id']){
                $html.='<div>
                <div class="col-md-4 bg-info p-1 float-end mb-2">
                    <p class="text-white">You</p>
                    <p>'.$message['message'].'</p>
                </div>
            </div>';
            }else{
                $html.='
                <div>
                    <div class="col-md-4 bg-primary p-1 mb-2">
                        <p class="text-muted">'.$message['name'].'</p>
                        <p>'.$message['message'].'</p>
                    </div>
                </div>';
            }
            
        }
        echo $html;
    }
}