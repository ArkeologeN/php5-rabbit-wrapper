<?php

namespace Logilim\Rabbit;

/**
 *
 *  Creates a Rabbit Connection Singleton connection.
 * @package Logilim\Rabbit
 * @author  Hamza Waqas
 * @version v1.0
 */
class RabbitConnection extends \AMQPConnection {

    private static $_instance = null;


    /**
     * Returns the Instance via Singleton
     * @return RabbitConnection|null
     */
    static function getInstance() {
        if ( !self::$_instance instanceof RabbitConnection)
            self::$_instance = new RabbitConnection();

        return self::$_instance;
    }

    /**
     *  Builds connection to RabbitServer
     * RabbitConnection|null
     */
    public function build() {
        $this->setHost(AMQP_HOST);
        $this->setLogin(AMQP_LOGIN);
        $this->setPassword(AMQP_PASS);
        $this->setVhost(AMQP_VHOST);
        //$this->setPort(AMQP_PORT);
        return self::$_instance;
    }

    /**
     *  Starts / Connects the Rabbit Server.
     * @return RabbitConnection|null
     */
    public function start() {
        $this->connect();
        return self::$_instance;
    }

    /**
     *  Checks if connection made to Rabbit.
     * @return boolean
     */
    public function isRunning() {
        return $this->isConnected();
    }

    /**
     * Disconnects with Rabbit.
     */
    public function close() {
        $this->disconnect();
    }

}