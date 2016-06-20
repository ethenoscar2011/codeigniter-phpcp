<?php

class asyncRedisProxy {

    private $redisProxy = null;
    private $syncFunArr;
    private $map; //fd=>obj

    public function __construct() {
        $this->redisProxy = new redisProxy();
        $this->syncFunArr = array(
            'connect',
            'pconnect'
            );
        $this->redisProxy->setAsync(1);
    }

    /*
     * every obj have a tcp with pool_server,so close it when __destruct
     */

    public function __destruct() {
        $this->redisProxy->close();
    }

    public function __call($name, $arguments) {
        if (in_array($name, $this->syncFunArr)) {
            if (is_callable(end($arguments))) {
                throw new Exception("the function " . json_encode($this->syncFunArr) . " can not async");
            }
            return call_user_func_array(array($this->redisProxy, $name), $arguments);
        }
        if (is_callable(end($arguments))) {//can async and last args is callback
            $callback = array_pop($arguments);
            $this->redisProxy->setAsync(1);
            $fd = call_user_func_array(array($this->redisProxy, $name), $arguments);
            $this->map[$fd] = $this->redisProxy;
            swoole_event_add($fd, function($fd) use ($callback) {
                swoole_event_del($fd);
                $data = $this->map[$fd]->done(); //set sync
                call_user_func($callback, $this, $data);
            });
        } else {
            return call_user_func_array(array($this->redisProxy, $name), $arguments);
        }
    }

}

class asyncPdoProxy {

    private $pdoProxy = null;
    private $syncFunArr;
    private $map; //fd=>obj

    public function __construct($dns, $user, $pwd, $arr = array()) {
        $this->pdoProxy = new pdoProxy($dns, $user, $pwd, $arr);
        $this->asyncFunArr = array(
            'exec',
            'query',
            'commit'
            );
        $this->pdoProxy->setAsync(1); //set the next cmd async/sync
    }

    /*
     * every obj have a tcp with pool_server,so close it when __destruct
     */

    public function __destruct() {
        $this->pdoProxy->close();
    }

    public function __call($name, $arguments) {
        if (in_array($name, $this->asyncFunArr)) {
            if (is_callable(end($arguments))) {//can async and last args is callback
                $callback = array_pop($arguments);
                $this->pdoProxy->setAsync(1); //set pdo async
                $fd = call_user_func_array(array($this->pdoProxy, $name), $arguments);
                $this->map[$fd] = $this->pdoProxy;
                swoole_event_add($fd, function($fd) use ($callback) {
                    swoole_event_del($fd);
                    $data = $this->map[$fd]->done(); //set pdo sync inner
                    call_user_func($callback, $this, $data);
                });
            } else {
                $this->pdoProxy->setAsync(0); //set pdo sync
                return call_user_func_array(array($this->pdoProxy, $name), $arguments);
            }
        } else {
            if (is_callable(end($arguments))) {
                throw new Exception("only the function " . json_encode($this->syncFunArr) . " can be async");
            }
            $this->pdoProxy->setAsync(0); //set pdo sync
            $data = call_user_func_array(array($this->pdoProxy, $name), $arguments);
            if (is_object($data)) {
                $stmt = new asyncSTMTProxy(); //warpper it
                $stmt->setSTMTObj($data);
                $stmt->setPDOObj($this->pdoProxy);
                $data->setAsync(0); //set stmt async
                return $stmt;
            } else {
                return $data;
            }
        }
    }

}

class asyncSTMTProxy {

    private $stmtProxy = null;
    private $pdoProxy = null;
    private $syncFunArr;
    private $map; //fd=>obj
    private $asyncFunArr = array(
        'execute',
        );

    public function setSTMTObj($obj) {
        $this->stmtProxy = $obj;
    }

    public function setPDOObj($obj) {
        $this->pdoProxy = $obj;
    }
    
    public function __call($name, $arguments) {
        if (in_array($name, $this->asyncFunArr)) {
            if (is_callable(end($arguments))) {//can async and last args is callback
                $callback = array_pop($arguments);
                $this->stmtProxy->setAsync(1); //set pdo async
                $fd = call_user_func_array(array($this->stmtProxy, $name), $arguments);
                $this->map[$fd] = $this->stmtProxy;
                swoole_event_add($fd, function($fd) use ($callback) {
                    swoole_event_del($fd);
                    $data = $this->map[$fd]->done(); //set pdo sync
                    call_user_func($callback, $this->stmtProxy, $data);
                });
            } else {
                $this->stmtProxy->setAsync(0); //set pdo sync
                return call_user_func_array(array($this->stmtProxy, $name), $arguments);
            }
        } else {
            if (is_callable(end($arguments))) {
                throw new Exception("only the function " . json_encode($this->syncFunArr) . " can be async");
            }
            $this->stmtProxy->setAsync(0); //set pdo sync
            return call_user_func_array(array($this->stmtProxy, $name), $arguments);
        }
    }

}
