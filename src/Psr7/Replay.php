<?php

{ // ======================
    function logPsr7Req(ServerRequestInterface $request)
    {
        $req = [
            '_REQUEST_ID' => defined("REQUEST_ID") ? REQUEST_ID : '-',
            '_METHOD' => $request->getServerParams()['REQUEST_METHOD'],
            '_PATH' => (explode("?", $request->getServerParams()['REQUEST_URI'], 2))[0],
            '_QUERY' => $request->getServerParams()['QUERY_STRING'],
            '_BODY_STR' => substr($request->getBody()->getContents(), 0, 1024),
            'serverParams' => $request->getServerParams(),
            // 'body' => $request->getBody(),
            'uploadFiles' => $request->getUploadedFiles(),
            'cookieParams' => $request->getCookieParams(),
            'queryParams' => $request->getQueryParams(),
            'parsedBody' => $request->getParsedBody(),
            // 'attributes'=>$request->getAttributes(),
        ];

        // 不要なパラメタを落とす
        unset($req['serverParams']['DB_PASS']);
        unset($req['serverParams']['DB_USER']);
        unset($req['serverParams']['DB_PORT']);
        unset($req['serverParams']['DB_HOST']);
        unset($req['serverParams']['DB_DATABASE']);
        unset($req['serverParams']['PATH']);
        unset($req['serverParams']['USER']);
        unset($req['serverParams']['HOME']);
        unset($req['serverParams']['PATH_TRANSLATED']);
        unset($req['serverParams']['ORIG_SCRIPT_FILENAME']);
        unset($req['serverParams']['SERVER_NAME']);
        unset($req['serverParams']['SERVER_PORT']);
        unset($req['serverParams']['SERVER_ADDR']);
        unset($req['serverParams']['REMOTE_PORT']);
        unset($req['serverParams']['REMOTE_ADDR']);
        unset($req['serverParams']['SERVER_SOFTWARE']);
        unset($req['serverParams']['GATEWAY_INTERFACE']);
        unset($req['serverParams']['FCGI_ROLE']);
        unset($req['serverParams']['argv']);
        unset($req['serverParams']['argc']);

        if (count($req['cookieParams']) === 0) {
            unset($req['cookieParams']);
        }
        if (count($req['queryParams']) === 0) {
            unset($req['queryParams']);
        }
        if (count($req['uploadFiles']) === 0) {
            unset($req['uploadFiles']);
        }
        $log_str = var_export($req, true);
        $log_str = preg_replace("/=> \n([ ]+)array \(/u", "=>[", $log_str);
        $log_str = preg_replace("/\),\n/u", "],\n", $log_str);
        $log_str = preg_replace("/array \(\n/u", "[\n", $log_str);
        $log_str = preg_replace("/\n\)/us", "\n]", $log_str);

        error_log($log_str);
    }

    function logPsr7Res(ResponseInterface $response): ResponseInterface
    {
        $res = [
            '_REQUEST_ID' => defined("REQUEST_ID") ? REQUEST_ID : '-',
            'statusCode' => $response->getStatusCode(),
            'header' => $response->getHeaders(),
            'body' => $response->getBody()->__toString(),
        ];
        $log_str = var_export($res, true);
        $log_str = preg_replace("/=> \n([ ]+)array \(/u", "=>[", $log_str);
        $log_str = preg_replace("/\),\n/u", "],\n", $log_str);
        $log_str = preg_replace("/array \(\n/u", "[\n", $log_str);
        $log_str = preg_replace("/\n\)/us", "\n]", $log_str);

        error_log($log_str);
        return $response;
    }
} // ======================

{ //=========================
    try { 
        { // ここからアプリ本体
            require 'vendor/autoload.php'; { // 乗っ取り
                // Slim のっとります。
                // \Slim\Appの以下だけ書き換えた
                // publuc $container; // TODO PATCH modification
                require(__DIR__ . "/MySlimApp.php");
            }
            $app = new \Uz\Slim\App();
            require 'app.php';
            // { // リプレイ
            //     $container = $app->getContainer();
            //     $container['environment'] = function () {
            //         return Environment::mock([
            //             'REQUEST_METHOD' => 'POST',
            //             'REQUEST_URI' => '/api/actions/login',
            //             'HTTP_CONTENT_TYPE' => 'application/json',
            //             'HTTP_CONTENT_LENGTH' => '52',
            //             'HTTP_USER_AGENT' => 'isucon8q-benchmarker',
            //             'HTTP_HOST' => 'torb.example.com',
            //             'REQUEST_SCHEME' => 'http',
            //             'SERVER_PROTOCOL' => 'HTTP/1.1',
            //             'CONTENT_LENGTH' => '52',
            //             'CONTENT_TYPE' => 'application/json',
            //         ]);
            //     };
            //     $container['request'] = function ($container) {
            //         $request = Request::createFromEnvironment($container->get('environment'));
            //         // POSTはこれで乗っ取れるな
            //         $request = $request->withParsedBody([
            //             'login_name' => 'doi_ryo',
            //             'password' => 'doi_ryooyr_iod',
            //         ]);
            //         return $request;
            //     };
            // }
            $app->run();
        } // ここまでアプリ本体
    } catch (\Throwable $e) {
        throw $e;
        // error_log("ERROR: {$e->getFile()}:{$e->getLine} {$e->getMessage()} {$e->getTraceAsString()}");
    }
}// ========================




