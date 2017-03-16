<?php
/**
 * 一些辅助函数
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Support/helper
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

function config($key = '', $model = '')
{
    /**
     * @var mixed
     */
    static $store;

    if (isset($store[$model])) {
        $config = $store[$model];
    } else {
        $file          = CFGPATH . $model . '.php';
		if (!file_exists($file)) {
			return null;
		}
        $config        = include $file;
        $store[$model] = $config;
    }

    if (!$key) {
        return $config;
    }

    if (!isset($config[$key])) {
        return null;
    }

    return $config[$key];
}


/**
 * @param $appName
 * @param $uri
 * @param $method
 * @param array      $data
 */
function req($appName, $uri, $method = 'get', $data = [])
{
    $urls = config('domains', 'config');
    if (!isset($urls[$appName])) {
        return null;
    }

	$method = strtoupper($method);
	$getUri = explode('?', $uri)[0];
    $store = Route::$groups;
	if (!isset($store[$method][$appName][$getUri]))
	{
	    return null;
	}
	return request($urls[$appName] . $uri, $data, $method);
}

/**
 * 发送HTTP请求方法 支持REST
 *
 *            请求URL
 *            请求参数
 *            请求头数组
 * @param  string $url
 * @param  array  $params
 * @param  array  $header
 * @return array  $data 响应数据
 */
function request($url, $params, $method = 'get', $multi = null)
{
    $opts = [
        CURLOPT_RETURNTRANSFER    => 1,
        CURLOPT_SSL_VERIFYPEER    => 0,
        CURLOPT_SSL_VERIFYHOST    => 0,
        //CURLOPT_HTTPHEADER => $header,
        CURLOPT_TIMEOUT_MS        => 100, // 执行时间 默认100毫秒
        CURLOPT_CONNECTTIMEOUT_MS => 10, // 请求时间 默认 10毫秒 超时自动断开
        CURLOPT_URL               => $url,
    ];

    $params = !is_array($params) ? [] : $params;

    $params = $multi ? $params : http_build_query($params);

    if ($method == 'POST') {
        $opts[CURLOPT_POST]       = 1;
        $opts[CURLOPT_POSTFIELDS] = $params;
    }
    /* 初始化并执行curl请求 */
    try {
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data     = curl_exec($ch);
        $error    = curl_error($ch);
        $curlInfo = curl_getinfo($ch);
        curl_close($ch);
        //$resultData = json_decode($data, true);
        if ($curlInfo['http_code'] != 200) {
            //log err msg
            return null;
        }

        return $data;
    } catch (Exception $e) {
        // TODO log msg
    }
}
