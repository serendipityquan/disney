<?php

namespace app\admin\controller;

use think\Lang;
/**
 * ============================================================================
 * DSMall多用户商城
 * ============================================================================
 * 版权所有 2014-2028 长沙德尚网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.csdeshang.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 控制器
 */
class  Config extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/'.config('default_lang').'/config.lang.php');
    }

    public function base() {
        $config_model = model('config');
        if (!request()->isPost()) {
            $list_config = rkcache('config', true);
            $this->assign('list_config', $list_config);
            /* 设置卖家当前栏目 */
            $this->setAdminCurItem('base');
            return $this->fetch();
        } else {
            //上传文件保存路径
            $upload_file = BASE_UPLOAD_PATH . DS . ATTACH_COMMON;
            if (!empty($_FILES['site_logo']['name'])) {
                $file = request()->file('site_logo');
                $info = $file->validate(['ext'=>ALLOW_IMG_EXT])->move($upload_file, 'site_logo');
                if ($info) {
                    $upload['site_logo'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['site_logo'])) {
                $update_array['site_logo'] = $upload['site_logo'];
            }
            if (!empty($_FILES['member_logo']['name'])) {
                $file = request()->file('member_logo');
                $info = $file->validate(['ext'=>ALLOW_IMG_EXT])->move($upload_file, 'member_logo');
                if ($info) {
                    $upload['member_logo'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['member_logo'])) {
                $update_array['member_logo'] = $upload['member_logo'];
            }
            if (!empty($_FILES['seller_center_logo']['name'])) {
                $file = request()->file('seller_center_logo');
                $info = $file->validate(['ext'=>ALLOW_IMG_EXT])->move($upload_file, 'seller_center_logo');
                if ($info) {
                    $upload['seller_center_logo'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['seller_center_logo'])) {
                $update_array['seller_center_logo'] = $upload['seller_center_logo'];
            }
            if (!empty($_FILES['site_mobile_logo']['name'])) {
                $file = request()->file('site_mobile_logo');
                $info = $file->validate(['ext'=>ALLOW_IMG_EXT])->move($upload_file, 'site_mobile_logo.png');
                if ($info) {
                    $upload['site_mobile_logo'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['site_mobile_logo'])) {
                $update_array['site_mobile_logo'] = $upload['site_mobile_logo'];
            }
            if (!empty($_FILES['site_logowx']['name'])) {
                $file = request()->file('site_logowx');
                $info = $file->validate(['ext'=>ALLOW_IMG_EXT])->move($upload_file, 'site_logowx');
                if ($info) {
                    $upload['site_logowx'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['site_logowx'])) {
                $update_array['site_logowx'] = $upload['site_logowx'];
            }
            if (!empty($_FILES['business_licence']['name'])) {
                $file = request()->file('business_licence');
                $info = $file->validate(['ext'=>ALLOW_IMG_EXT])->move($upload_file, 'business_licence');
                if ($info) {
                    $upload['business_licence'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['business_licence'])) {
                $update_array['business_licence'] = $upload['business_licence'];
            }
            
            //首页首次访问悬浮图片
            if (!empty($_FILES['fixed_suspension_img']['name'])) {
                $file = request()->file('fixed_suspension_img');
                $info = $file->validate(['ext'=>ALLOW_IMG_EXT])->move($upload_file, 'fixed_suspension_img');
                if ($info) {
                    $upload['fixed_suspension_img'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['fixed_suspension_img'])) {
                $update_array['fixed_suspension_img'] = $upload['fixed_suspension_img'];
            }
            
            $update_array['baidu_ak'] = input('post.baidu_ak');
            $update_array['site_name'] = input('post.site_name');
            $update_array['icp_number'] = input('post.icp_number');
            $update_array['site_phone'] = input('post.site_phone');
            $update_array['site_tel400'] = input('post.site_tel400');
            $update_array['site_email'] = input('post.site_email');
            $update_array['flow_static_code'] = input('post.flow_static_code');
            $update_array['site_state'] = intval(input('post.site_state'));
            $update_array['cache_open'] = intval(input('post.cache_open'));
            $update_array['closed_reason'] = input('post.closed_reason');
            $update_array['hot_search'] = input('post.hot_search');
            $update_array['fixed_suspension_state'] = input('post.fixed_suspension_state');//首页首次访问悬浮状态
            $update_array['fixed_suspension_url'] = input('post.fixed_suspension_url');
			$result = $config_model->editConfig($update_array);
            if ($result) {
                $this->log(lang('ds_edit').lang('web_set'),1);
                $this->success(lang('ds_common_save_succ'), 'Config/base');
            }else{
                $this->log(lang('ds_edit').lang('web_set'),0);
            }
        }
    }

    /**
     * 防灌水设置
     */
    public function dump(){
        $config_model = model('config');
        if (!request()->isPost()) {
            $list_config = rkcache('config', true);
            $this->assign('list_config', $list_config);
            /* 设置卖家当前栏目 */
            $this->setAdminCurItem('dump');
            return $this->fetch();
        } else {
            $update_array = array();
            $update_array['guest_comment'] = intval(input('post.guest_comment'));
            $update_array['captcha_status_login'] = intval(input('post.captcha_status_login'));
            $update_array['captcha_status_register'] = intval(input('post.captcha_status_register'));
            $update_array['captcha_status_goodsqa'] = intval(input('post.captcha_status_goodsqa'));
            $result = $config_model->editConfig($update_array);
            if ($result === true) {
                $this->log(lang('ds_edit').lang('dis_dump'), 1);
                $this->success(lang('ds_common_save_succ'));
            } else {
                $this->log(lang('ds_edit').lang('dis_dump'), 0);
                $this->error(lang('ds_common_save_fail'));
            }
        }
    }


    /*
     * 设置自动收货时间
     */
    public function auto(){
        $config_model = model('config');
        if (!request()->isPost()) {
            $list_config = rkcache('config', true);
            $this->assign('list_config', $list_config);
            /* 设置卖家当前栏目 */
            $this->setAdminCurItem('auto');
            return $this->fetch();
        } else {
            $order_auto_cancel_day = intval(input('post.order_auto_cancel_day'));
            $order_refund_time = intval(input('post.order_refund_time'));
            $store_bill_cycle = intval(input('post.store_bill_cycle'));
            if($order_auto_cancel_day < 1 || $order_auto_cancel_day>50){
                $this->error(lang('automatic_confirmation_receipt').'1-50'.lang('numerical'));
            }
            if($order_refund_time < 0 || $order_refund_time>100){
                $this->error(lang('exchange_code_refunded_automatically').'0-100'.lang('numerical'));
            }
            if($store_bill_cycle<7){
                $this->error(lang('store_bill_cycle_error'));
            }
            $update_array['order_auto_cancel_day'] = $order_auto_cancel_day;
            $update_array['order_refund_time'] = $order_refund_time;
            $update_array['store_bill_cycle'] = $store_bill_cycle;
            $result = $config_model->editConfig($update_array);
            if ($result) {
                $this->log(lang('ds_edit').lang('auto_set'),1);
                $this->success(lang('ds_common_save_succ'), 'Config/auto');
            }else{
                $this->log(lang('ds_edit').lang('auto_set'),0);
                $this->error(lang('ds_common_save_fail'));
            }
        }
    }
    
    /**
     * 第三方视频存储
     * @return type
     */
    function vod()
    {
        $config_model = model('config');
        if (!request()->isPost()) {
            $list_config = rkcache('config', true);
            $this->assign('list_config', $list_config);
            /* 设置卖家当前栏目 */
            $this->setAdminCurItem('vod');
            return $this->fetch();
        } else {
            $update_array['vod_tencent_secret_id'] = input('post.vod_tencent_secret_id');
            $update_array['vod_tencent_secret_key'] = input('post.vod_tencent_secret_key');
            $result = $config_model->editConfig($update_array);
            if ($result) {
                $this->log(lang('ds_edit').lang('vod_set'),1);
                $this->success(lang('ds_common_save_succ'), 'Config/vod');
            }else{
                $this->log(lang('ds_edit').lang('vod_set'),0);
                $this->error(lang('ds_common_save_fail'));
            }
        }
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'base',
                'text' => lang('ds_base'),
                'url' => url('Config/base')
            ),
            array(
                'name' => 'dump',
                'text' => lang('dis_dump'),
                'url' => url('Config/dump')
            ),
            array(
                'name' => 'vod',
                'text' => lang('vod_setting'),
                'url' => url('Config/vod')
            ),
            array(
                'name' => 'auto',
                'text' => lang('automatic_execution_time_setting'),
                'url' => url('Config/auto')
            ),
        );
        return $menu_array;
    }

}
