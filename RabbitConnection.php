<?php
/**
 * Created as RabbitConnection.php.
 * Developer: Hamza Waqas
 * Date:      2/21/13
 * Time:      4:51 PM
 */

namespace Logilim\Rabbit;

class RabbitConnection extends \AMQPConnection {

    private static $_instance = null;

    static function getInstance() {
        if ( !self::$_instance instanceof RabbitConnection)
            self::$_instance = new RabbitConnection();

        return self::$_instance;
    }

    public function build() {
        $this->setHost(AMQP_HOST);
        $this->setLogin(AMQP_LOGIN);
        $this->setPassword(AMQP_PASS);
        //$this->setPort(AMQP_PORT);
        return self::$_instance;
    }

    public function start() {
        $this->connect();
        return self::$_instance;
    }

    public function isRunning() {
        return $this->isConnected();
    }

    public function close() {
        $this->disconnect();
    }

}