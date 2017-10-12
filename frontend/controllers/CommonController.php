<?php
namespace frontend\controllers;

use yii\web\Controller;

class CommonController extends Controller
{
    /*
      * 操作成功显示提示信息
      * @param string $info,显示的信息提示
      * @param array $url,二维数组，将要跳转的链接，格式：[[urlText1,url1],[urlText2,url2]......]
      * @param int $jumpseconds,自动跳转到第一个链接的秒数，-1：不自动跳转；0：当即跳转；大于0的整数：信息显示的秒数
      * @return
     * sample:
     * $this->success('Submit success,Thank you!',[['首页','/'],['购物车','/shopping-cart/index']],3);
      * */
    public function success($info, $url = [], $jumpSeconds = -1)
    {
        if (!empty($url) && empty($jumpSeconds)) {
            return $this->redirect($url[0]);
        } else {
            $this->layout = 'main';
            echo $this->render('@frontend/views/PromptInformation', [
                'info' => $info,
                'url' => $url,
                'jumpSeconds' => $jumpSeconds,
            ]);
        }
        exit;
    }

    /*
     * 操作失败显示提示信息
     * @param string $info,显示的信息提示
     * @param array $url,二维数组，将要跳转的链接，格式：[[urlText1,url1],[urlText2,url2]......]
     * @param int $jumpseconds,自动跳转到第一个链接的秒数，-1：不自动跳转；0：当即跳转；大于0的整数：信息显示的秒数
     * @return
     * */
    public function error($info, $url = [], $jumpSeconds = -1)
    {
        if (!empty($url) && empty($jumpSeconds)) {

            return $this->redirect($url[0]);
        } else {
            echo $this->render('@frontend/views/PromptInformation', [
                'info' => $info,
                'url' => $url,
                'jumpSeconds' => $jumpSeconds,
            ]);
        }
        exit;
    }

    /*
     * 调用信息提示页面
     * @param string $info,显示的信息提示
     * @param array $url,二维数组，将要跳转的链接，格式：[[urlText1,url1],[urlText2,url2]......]
     * @param int $jumpseconds,自动跳转到第一个链接的秒数，-1：不自动跳转；0：当即跳转；大于0的整数：信息显示的秒数
     * @return
     * */
    public function info($info, $url = [], $jumpSeconds = -1)
    {
        if (!empty($url) && empty($jumpSeconds)) {
            return $this->redirect($url[0]);
        } else {
            echo $this->render('@frontend/views/PromptInformation', [
                'info' => $info,
                'url' => $url,
                'jumpSeconds' => $jumpSeconds,
            ]);
        }
        exit;
    }
}