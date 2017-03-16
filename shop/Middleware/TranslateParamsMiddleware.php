<?php
/**
 * Middleware
 * @version   SVN:$Id: TranslateParamsMiddleware.php 22779 2016-12-14 09:40:38Z kakaxi $
 * @package   Shop/Middleware
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

use Turbo\Middleware\Middleware as BaseMiddle;

class TranslateParamsMiddleware extends BaseMiddle
{
    /**
     * 配置文件地址
     * @var mixed
     */
    protected $cfgPath;

    /**
     * request object
     * @var mixed
     */
    protected $passable;

    /**
     * @param  $passable
     * @param  $stack
     * @return mixed
     */
    public function handle($passable, $stack)
    {
		//now passable is request
        $this->passable = $passable;
        $get            = $this->transGet();
        $post           = $this->transPost();
        $passable       = $passable->duplicate($get);

        return $stack($passable);
    }

    /**
	 * 重新替换响应输出
     * @param  $passable
     * @param  $stack
     * @return mixed
     */
    public function handleOut($passable, $stack)
    {
		// now passable is response
		$this->passable = $this->app->make(\Turbo\Http\Request::class);
		$content = $passable->getContent();

		$data = json_decode($content, true);

		//var_dump($data);
		//判断要处理的数据是否OK
		if (is_array($data) && isset($data['data']) && is_array($data['data'])) {
			//翻译数据
			$trans = $this->getTransData('', $way = 'out');
			$data['data'] = $this->trans($data['data'], $trans);
			$passable->setContent(json_encode($data));
		}
        return $stack($passable);
    }
    /**
     * 核心翻译功能
     */
    public function trans(&$data, &$trans = [], $direct = true)
    {
        if ($data && $trans) {
            if (!$direct) {
                $trans = array_flip($trans);
            }
            $ret = [];
            foreach ($data as $k => $v) {
                if (isset($trans[$k])) {
                    $ret[$trans[$k]] = $v;
                } else {
                    $ret[$k] = $v;
                }
            }

            return $ret;
        }

        return $data;
    }

    /**
     * 翻译get参数
     */
    public function transGet($direct = true)
    {
        $data  = $this->passable->query->all();
        $trans = $this->getTransData('get');

        return $this->trans($data, $trans, $direct);
    }

    /**
     * 翻译 post参数
     */
    public function transPost($direct = true)
    {
        $data  = $this->passable->request->all();
        $trans = $this->getTransData('post');

        return $this->trans($data, $trans, $direct);
    }

    /**
     * 对照数据来源
     */
    private function getTransData($key, $way = 'in')
    {
        if (!$this->cfgPath) {
            $this->cfgPath = self::getDataPath($this->passable->getPathInfo());
        }
        $data = config($way, 'transparams/' . $this->cfgPath);

        return isset($data[$key]) ? $data[$key] : (array)$data;
    }

    /**
     * @param $uri
     */
    public static function getDataPath($uri)
    {
        return str_replace('/', '-', substr($uri, 1));
    }

}
