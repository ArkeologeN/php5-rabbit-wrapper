<?php
/**
 * Created as RabbitFactory.php.
 * Developer: Hamza Waqas
 * Date:      2/22/13
 * Time:      4:12 PM
 */

namespace Logilim\Rabbit;

class RabbitFactory {

    static function newFactory() {
        return new self;
    }

    static function newPublisher($options = array()) {
        return new RabbitPublisher($options);
    }

    static function newExchange(RabbitChannel $channel) {
        return new RabbitExchange($channel);
    }

    static function newChannel(RabbitConnection $connection) {
        return new RabbitChannel($connection);
    }

    static function newConsumer($options = array()) {
        return new RabbitConsumer($options);
    }

    static function newQueue(RabbitChannel $channel) {
        return new RabbitQueue($channel);
    }
}