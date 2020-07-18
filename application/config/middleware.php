<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['middleware'] = array(
    'load' => array('login', 'authentication', 'authorization', 'device', 'shift'),
    'handle' => array(
        'middleware' => array('authentication'),
        'exceptions' => array('login', 'user_setting', 'api'),
        'user_setting' => array(
            'middleware' => array('authentication')
        ),
        'login' => array(
            'index' => array(
                'middleware' => array('login')
            ),
            'logout' => array(
                'middleware' => array('authentication')
            )
        ),
        'api' => array(
            'middleware' => array('device')
        ),
	    'transaksi' => array(
		    'mutasi_kasir' => array(
			    'middleware' => array('shift')
		    ),
		    'penjualan' => array(
			    'middleware' => array('shift')
		    )
	    )
    )
);