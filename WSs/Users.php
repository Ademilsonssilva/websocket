<?php
namespace WSs;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Users implements MessageComponentInterface {
    protected $clients;
    protected $log;

    public function __construct() {
        $this->clients = new \SplObjectStorage;

        $this->log = fopen('log.txt', 'w+');
    }

    public function onOpen(ConnectionInterface $conn) {
        // Salva a nova conexão para ser usada depois
        $this->clients->attach($conn);

        $log_msg =  "Nova conexão! ({$conn->resourceId})\n";

        $this->log($log_msg);

    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        $log_msg = sprintf('Conexão %d enviando mensagem "%s" para %d outras conexões%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $this->log($log_msg);

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // Envia para todos, menos para a conexão que está enviando
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        $this->log("Conexão {$conn->resourceId} foi desconectada\n");
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $this->log("Ocorreu um erro");

        $conn->close();
    }

    //Função minha criada para logar os eventos
    public function log($texto)
    {
        echo $texto;

        $log_info = " Novo registro (" . Date('d/m/Y H:i:s') . ") - {$texto}";

        fwrite($this->log, $log_info);       
    }
}