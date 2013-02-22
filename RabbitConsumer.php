<?php
/**
 * Created as RabbitConsumer.php.
 * Developer: Hamza Waqas
 * Date:      2/22/13
 * Time:      5:36 PM
 */

namespace Logilim\Rabbit;

class RabbitConsumer {

    private $_options = array(
        'exchange.name' => 'noExchange',
        'exchange.type' => 'fanout',
        'exchange.flag' => AMQP_DURABLE,
        'publish.key'   => 'key1',
        'queue.name'    => '',
    );

    private $_isPublished = false;

    private $_connection = null;

    private $_channel = null;

    private $_exchange = null;

    private $_queue = null;



    public function __construct($options = array()) {
        $this->_options = array_merge($this->_options, $options);
        $this->_connection = RabbitConnection::getInstance()->build();
    }

    private function _makeChannel() {
        if ( $this->_isWorking()) {
            $this->_channel = RabbitFactory::newChannel($this->getConnection());
        }
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

    public function prepare() {
        try {
            if  ( ! $this->_channel instanceof RabbitChannel)
                $this->_makeChannel();


            if ( ! $this->_exchange instanceof RabbitExchange)
                $this->_makeExchange();


            $this->_makeQueue();
        } catch (Exception $ex) {
            echo "<pre>"; print_r($ex);
        }
    }

    private function _makeQueue() {
        try {

            $this->_queue = RabbitFactory::newQueue($this->getChannel());
            $this->getQueue()->setName($this->_options['queue.name']);
            $this->getQueue()->declare();
            $this->_bindServices();
        } catch (\Exception $ex) {
            echo "<pre>"; print_r($ex); exit;
        }
    }

    private function getChannel() {
        return $this->_channel;
    }

    private function getExchange() {
        return $this->_exchange;
    }

    private function getQueue() {
        return $this->_queue;
    }

    private function _bindServices() {
        $this->getQueue()->bind($this->_options['exchange.name'], $this->_options['publish.key']);
    }

    public function receive($callback) {
        while ($envelop = $this->getQueue()->get(AMQP_AUTOACK)) {
            $callback($envelop);
        }
    }

    private function _isWorking() {
        if ( !$this->getConnection()->isRunning()) {
            $this->getConnection()->start();
        }
        return true;
    }

    public function __destruct() {
        if ( $this->getConnection()->isRunning()) {
            $this->getConnection()->close();
        }
    }
    private function getConnection() {
        return $this->_connection;
    }

}