<?php

use krinfreschi\AsyncStreams\AsyncStreamWrapper;

if (!defined('ASYNC_STREAMS_FUNCTIONS')) {
    define('ASYNC_STREAMS_FUNCTIONS', true);

    function stream_get_wrapper($handle){
        $meta = stream_get_meta_data($handle);
        if(!isset($meta["wrapper_data"])){
            return false;
        }
        return $meta["wrapper_data"];
    }

    function stream_wrapper_get_context($handle){
        $wrapper = stream_get_wrapper($handle);
        if(!$wrapper){
            return false;
        }
        return isset($wrapper->context) ? $wrapper->context : false;
    }

    function stream_get_async_wrapper($handle){
        $wrapper = stream_get_wrapper($handle);
        if(!$wrapper instanceof AsyncStreamWrapper){
            return false;
        }
        return $wrapper;
    }

    function async_stream_register_read($handle, $callable){
        $wrapper = stream_get_async_wrapper($handle);
        if(!$wrapper || !is_callable($callable)){
            throw new InvalidArgumentException();
        }
        $wrapper->setOptions("read_callback", $callable);
    }

    function async_stream_register_write($handle, $callable){
        $wrapper = stream_get_async_wrapper($handle);
        if(!$wrapper && !is_callable($callable)){
            throw new InvalidArgumentException();
        }
        $wrapper->setOptions("write_callback", $callable);
    }

    function async_stream_remove_read($handle){
        $wrapper = stream_get_async_wrapper($handle);
        if(!$wrapper){
            throw new InvalidArgumentException();
        }
        $wrapper->setOptions("read_callback", null);
    }

    function async_stream_remove_write($handle){
        $wrapper = stream_get_async_wrapper($handle);
        if(!$wrapper){
            throw new InvalidArgumentException();
        }
        $wrapper->setOptions("write_callback", null);
    }


}