<?php
# -------------------------- #
#  Name: chaz6chez           #
#  Email: admin@chaz6chez.cn #
#  Date: 2018/9/18           #
# -------------------------- #
namespace Mine\Base;


use Mine\Core\Output;
use Mine\Core\Request;
use Mine\Helper\Tools;

abstract class Controller {
    public $_now = 0;

    protected $_output;
    protected $_request;
    protected $_apiRequestId = false;

    /**
     * @param $name
     * @param $arguments
     * @throws \Exception
     */
    public function __call($name, $arguments){
        Tools::Http404('action ' . $name . ' was not found');
    }

    /**
     * Controller constructor.
     */
    public function __construct() {
        $this->_now = isset($GLOBALS['NOW_TIME']) ? $GLOBALS['NOW_TIME'] : time();    # 设置时间
        $this->_preInit();
        $this->_init();
    }

    /**
     * 预初始化
     */
    protected function _preInit() {

    }

    /**
     * 初始化
     */
    protected function _init() {
    }

    /**
     * 获取request对象
     * @return Request
     */
    protected function request() {
        if (!$this->_request or !$this->_request instanceof Request) {
            $this->_request = new Request();
        }
        return $this->_request;
    }

    /**
     * 获取输出器对象
     * @param string $pattern
     * @return Output
     */
    protected function output(string $pattern = Output::TYPE_JSON) {
        if (
            !$this->_output or
            !$this->_output instanceof Output
        ) {
            $this->_output = new Output();
        }
        $this->_output->_apiRequestId = $this->_apiRequestId;
        if(is_string($pattern)){
            $this->_output->setPattern($pattern);
        }
        if (is_array($pattern)) {
            if (
                isset($pattern['errCode']) and
                isset($pattern['message']) and
                isset($pattern['data'])
            ) {
                $this->_output->output($pattern['errCode'], $pattern['message'], $pattern['data']);
            } else {
                $this->_output->success($pattern);
            }
        }
        return $this->_output;
    }

    /**
     * 获取输出器对象
     * @param string $pattern
     * @return Output
     */
    protected function http(string $pattern = Output::TYPE_HTTP) {
        if (
            !$this->_output or
            !$this->_output instanceof Output
        ) {
            $this->_output = new Output();
        }
        $this->_output->_apiRequestId = $this->_apiRequestId;
        if(is_string($pattern)){
            $this->_output->setPattern($pattern);
        }
        return $this->_output;
    }
}