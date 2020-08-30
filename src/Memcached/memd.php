<?php

// memcached session
ini_set('session.save_handler', 'memcached');
ini_set('session.save_path', '/var/run/memcached/memcached.sock');

{//memd utils
    function getMemdInstance(){
        static $memd;
        if(isset($memd)) return $memd;
        $memd = new Memcached();
        $memd->addServer('/var/run/memcached/memcached.sock', 0);
        return $memd;
    }

    // セットする、キーと、バリュー（内部でシリアライズしているので、配列いれてOK
    function setMemd($key, $val, $expire=0){
        $memd = getMemdInstance();
        if($expire===0)$expire = time()+3600;
        $memd->add($key, serialize($val), $expire);
    }

    // ゲットする、キーが必要、デシリアライズするので、そのままご利用下さい
    function getMemd($key, $default=null){
        $memd = getMemdInstance();
        $res = unserialize($memd->get($key));
        if($res===false) return $default;
        else return $res;
    }

    // flushします ただ、セッションにもつかっているのできをつけて…
    function flushMemd(){
        $memd = getMemdInstance();
        return $memd->flush();
    }

    // delします
    function rmMemd($key){
        $memd = getMemdInstance();
        return $memd->delete($key);
    }

    function hasMemd($key){
        $memd = getMemdInstance();
        return $memd->get($key) !== false;
    }

    // 使い方サンプル
    // setMemd('test', ['a'=>1]);
    // var_dump(getMemd('test'));
    // flushMemd();
}
