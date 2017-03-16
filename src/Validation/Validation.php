<?php
/**
 * 框架底层Validation
 *
 * @version   svn:$id: http.php 4533 2016-11-04 09:39:35z kakaxi $
 * @package   turbo/Validation
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

namespace Turbo\Validation;

class Validation
{
    /**
     * empty  verify
     */
    public function emptyVerify($value, $verify)
    {
        return !empty($value);
    }

    /**
     * fixed verify
     */
    public function fixedVerify($value, $verify)
    {
        return ($verify == $value);
    }

    /**
     * use defined function verify
     */
    public function funcVerify($value, $verify, $data)
    {
        $jValue = json_encode($value);
        $jData  = json_encode($data);
        $str    = '$ret = ' . $verify . '(json_decode(\'' . $jValue . '\', true), json_decode(\'' . $jData . '\', true));';
        eval($str);

        return $ret;
    }

    /**
     * @param $verify
     * @param $data
     * @param $prefix
     * @return mixed
     */
    public function getResult($verify, $data, $prefix = '')
    {
        $this->data   = $data;
        $this->verify = $verify;

        return $this->verify($prefix);
    }

    /**
     * length verify
     */
    public function lengthVerify($value, $verify)
    {
        if (!is_string($value)) {
            return false;
        }

        return ($this->numLimitVerify(strlen($value), $verify));
    }

    /**
     * num limit verify
     */
    public function numLimitVerify($value, $verify)
    {
        if (!is_numeric($value)) {
            return false;
        }
        list($start, $end) = explode('-', $verify);
        $isNumStart        = is_numeric($start);
        $isNumEnd          = is_numeric($end);
        if ($isNumStart && $isNumEnd) {
            return ($value >= $start && $end > $value);
        } else if (!$isNumStart && $isNumEnd) {
            return ($value < $end);
        } else if ($isNumStart && !$isNumEnd) {
            return ($value >= $start);
        }

        return false;
    }

    /**
     * number verify
     */
    public function numVerify($value, $verify)
    {
        return is_numeric($value);
    }

    /**
     * reg verify //preg
     */
    public function regVerify($value, $verify)
    {
        if (preg_match($verify, $value)) {
            return true;
        }

        return false;
    }

    /**
     * select verify
     */
    public function selectVerify($value, $verify)
    {
        if (!is_string($value)) {
            return false;
        }

        return in_array($value, $verify);
    }

    /**
     * 校验开始
     */
    protected function verify($prefix = '')
    {
        $result = [];
        foreach ($this->verify as $name => $value) {
            //是否越过校验
            if (isset($value['pass']) && $value['pass']) {
                continue;
            }
            //必要性判断
            if (isset($value['must']) && $value['must'] && !isset($this->data[$name])) {
                $result[$name] = $prefix . $name . ' parameter must pass';
                continue;
            }
            //独立规则校验
            $func        = $value['type'] . 'Verify';
            $verifyValue = isset($value['value']) ? $value['value'] : '';
            if (!$this->$func($this->data[$name], $verifyValue, $this->data)) {
                $notice        = isset($value['notice']) ? $value['notice'] : $prefix . '[' . $name . '] verify failed!';
                $result[$name] = $notice;
            }
        }

        return $result;
    }
}
