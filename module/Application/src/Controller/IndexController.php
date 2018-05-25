<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

use RedisClient\RedisClient;

class IndexController extends AbstractActionController
{
    public $sessionManager;

    protected $config = [
        'server'    => '127.0.0.1:6379',
        'timeout'   => 2,
        'connection' => [
            'timeout'   => 2,
            'flags'     => STREAM_CLIENT_CONNECT
        ],

        'password' => 'haslojakiegonieznacie',

        'database' => 0,

        'cluster' => [
            'enabled' => false,

            'clusters' => [
                5460  => '127.0.0.1:7001', // slots from 0 to 5460
                10922 => '127.0.0.1:7002', // slots from 5461 to 10922
                16383 => '127.0.0.1:7003', // slots from 10923 to 16383
            ],

            'init_on_start'             => false,
            'init_on_error_moved'       => true,
            'timeout_on_error_tryagain' => 0.25, // sec
        ]
    ];

    public function indexAction()
    {
        $client = new RedisClient($this->config);
        $client->ping();
//        $client->clientList();

   //     echo $client->get('not-exists');
/*
        $client->lPush('fruits','Jablko');
        $client->lPush('fruits','Gruszka');
        $client->lPush('fruits','Banan');
        $client->rPush('fruits','Mango');
        $client->rPush('fruits','Ogorek');
        $client->rPush('fruits','Pomarancza');
        $client->rPush('fruits','Dynia');
        $client->rPush('fruits','Marchew');
        $client->rPush('fruits','Agrest');
        $client->rPush('fruits','Śliwka');
        $client->rPush('fruits','Czereśnia');
        $client->rPush('fruits','Wiśnia');
*/
        return new ViewModel([
            'client'    => $client,
            'session'   => session_id()
        ]);
   }

    public function subscribeAction()
    {
        $this->config['timeout'] = 0;

        $client = new RedisClient($this->config);

        $client->subscribe('channel', function($type, $channel, $message) {
            if ($type === 'subscribe') {
                echo 'Subscribed to channel "', $channel, '"<br/>';
            } elseif ($type === 'message') {
                echo 'Message "', $message, '" from channel "', $channel, '"<br/>';
                if ($message === 'quit') {
                    echo 'quit<br/>';
                    return false;
                }
            }
            return true;
        });
    }
}
