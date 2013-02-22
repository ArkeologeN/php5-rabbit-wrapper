<?php
/**
 * Created as RabbitPublisher.php.
 * Developer: Hamza Waqas
 * Date:      2/22/13
 * Time:      4:11 PM
 */


namespace Logilim\Rabbit;

class RabbitPublisher  {

    private $_options = array(
        'exchange.name' => 'noExchange',
        'exchange.type' => 'fanout',
        'exchange.flag' => AMQP_DURABLE,
        'publish.key'   => 'key1'
    );


    private $_isPublished = false;

    private $_connection = null;

    private $_channel = null;

    private $_exchange = null;

    public function __construct($options = array()) {
        $this->_options = array_merge($this->_options, $options);
        $this->_connection = RabbitConnection::getInstance()->build();
    }


    public function publish($message = "") {
        try {
            if ( ! $this->getChannel() instanceof RabbitChannel)
                $this->_makeChannel();

            if ( ! $this->getExchange() instanceof RabbitExchange)
                $this->_makeExchange();


            $this->_isPublished = $this->getExchange()->publish($message, $this->_options['publish.key']);
        } catch (\Exception $ex) {
            echo "<pre>"; print_r($ex); exit;
        }
    }

    private function _makeChannel() {
        if ( $this->_isWorking()) {
            $this->_channel = RabbitFactory::newChannel($this->getConnection());
        }
    }

    private function getConnection() {
        return $this->_connection;
    }

    private function _makeExchange() {
       try {
           if ( $this->_isWorking() ) {
               $this->_exchange = RabbitFactory::newExchange($this->getChannel());
               $this->_exchange->setName($this->_options['exchange.name']);
               $this->_exchange->setType($this->_options['exchange.type']);
               $this->_exchange->setFlags($this->_options['exchange.flag']);
               $this->_exchange->declare();
           }
       } catch (\Exception $ex) {
           echo "<pre>"; print_r($ex); exit;
       }
    }

    private function _isWorking() {
        if ( !$this->getConnection()->isRunning()) {
            $this->getConnection()->start();
        }
        return true;
    }

    public function isPublished() {
        return $this->_isPublished;
    }

    public function __destruct() {
        if ( $this->getConnection()->isRunning()) {
            $this->getConnection()->close();
        }
    }

    private function getChannel() {
        return $this->_channel;
    }

    private function getExchange() {
        return $this->_exchange;
    }
}