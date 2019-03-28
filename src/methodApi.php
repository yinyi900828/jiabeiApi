<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 13:43
 */

namespace jiabeiApi\src;

class methodApi
{
    public function postApi($url, $secret, $bizId, $requestId, $bizContent, $signType = 'MD5', $version = '1.0')
    {
        $data = array(
            'biz_content' => $bizContent,
            'biz_id' => $bizId,
            'request_id' => $requestId,
            'sign_type' => $signType,
            'version' => $version,
        );
        $data['sign'] = $this->getSign($secret, $data, $signType);

        $result = $this->curl_request($url, $data);
        return $result;
    }

    private function getSign($secret, $data, $signType)
    {
        switch ($signType){
            case 'MD5':
                return $this->getSignByMD5($data, $secret);
                break;
            case 'RSA':
                return '';
                break;
            default:
                return '';
        }
    }

    private function getSignByMD5($data, $secret)
    {
        $str = join('', $data). $secret;
        return md5($str);
    }

    private function curl_request($url, $post = '')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if($post) {
            curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: application/json; charset=utf-8'));
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        return $data;
    }
}