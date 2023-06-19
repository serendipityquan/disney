<?php

namespace app\home\controller;

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
class  Memberconnect extends BaseMember {

    public function _initialize() {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'home/lang/'.config('default_lang').'/memberconnect.lang.php');
    }

    /**
     * QQ绑定
     */
    public function qqbind() {
        //获得用户信息
        if (trim($this->member_info['member_qqinfo'])) {
            $this->member_info['member_qqinfoarr'] = unserialize($this->member_info['member_qqinfo']);
        }
        $this->assign('member_info', $this->member_info);
        //信息输出
        $this->setMemberCurItem('qq_bind');
        $this->setMemberCurMenu('member_connect');
        return $this->fetch($this->template_dir . 'member_qqbind');
    }

    /**
     * QQ解绑
     */
    public function qqunbind() {
        //修改密码
        $member_model = model('member');
        $update_arr = array();
        if (input('post.is_editpw') == 'yes') {
            /**
             * 填写密码信息验证
             */
            $data = array(
                'new_password' => input('post.new_password'),
                'confirm_password' => input('post.confirm_password')
            );
            $memberconnect_validate = validate('memberconnect');
            if (!$memberconnect_validate->scene('qqunbind')->check($data)) {
                $this->error($memberconnect_validate->getError());
            }

            $update_arr['member_password'] = md5(trim(input('post.new_password')));
        }
        $update_arr['member_qqopenid'] = '';
        $update_arr['member_qqinfo'] = '';
        $edit_state = $member_model->editMember(array('member_id' => session('member_id')), $update_arr);
        if (!$edit_state) {
            $this->error(lang('member_qqconnect_password_modify_fail'),url('Memberconnect/qqbind'));
        }
        session(null);
        $this->success(lang('member_qqconnect_unbind_success'), HOME_SITE_URL.'/login/login.html?ref_url=' . urlencode(url('Memberconnect/qqbind')));
    }

    /**
     * 新浪绑定
     */
    public function sinabind() {
        //获得用户信息
        if (trim($this->member_info['member_sinainfo'])) {
            $this->member_info['member_sinainfoarr'] = unserialize($this->member_info['member_sinainfo']);
        }
        $this->assign('member_info', $this->member_info);
        //信息输出
        $this->setMemberCurItem('sina_bind');
        $this->setMemberCurMenu('member_connect');
        return $this->fetch($this->template_dir . 'member_sinabind');
    }

    /**
     * 新浪解绑
     */
    public function sinaunbind() {
        //修改密码
        $member_model = model('member');
        $update_arr = array();
        if (input('post.is_editpw') == 'yes') {
            /**
             * 填写密码信息验证
             */
            $data = array(
                'new_password' => input('post.new_password'),
                'confirm_password' => input('post.confirm_password')
            );
            $memberconnect_validate = validate('memberconnect');
            if (!$memberconnect_validate->scene('sinaunbind')->check($data)) {
                $this->error($memberconnect_validate->getError());
            }

            $update_arr['member_password'] = md5(trim(input('post.new_password')));
        }
        $update_arr['member_sinaopenid'] = '';
        $update_arr['member_sinainfo'] = '';
        $edit_state = $member_model->editMember(array('member_id' => session('member_id')), $update_arr);

        if (!$edit_state) {
            $this->error(lang('member_sconnect_password_modify_fail'));
        }
       session(null);
        $this->success(lang('member_sconnect_unbind_success'), HOME_SITE_URL.'/Login/login.html?ref_url=' . urlencode(url('Memberconnect/sinabind')));
    }

    /**
     * 微信绑定
     */
    public function weixinbind() {
        //获得用户信息
        if (trim($this->member_info['member_wxinfo'])) {
            $this->member_info['weixin_infoarr'] = unserialize($this->member_info['member_wxinfo']);
        }
        $this->assign('member_info', $this->member_info);
        //信息输出
        $this->setMemberCurMenu('member_connect');
        $this->setMemberCurItem('weixin_bind');
        return $this->fetch($this->template_dir . 'member_weixinbind');
    }

    /**
     * 微信解绑
     */
    public function weixinunbind() {
        //修改密码
        $member_model = model('member');
        $update_arr = array();
        if (input('post.is_editpw') == 'yes') {
            /**
             * 填写密码信息验证
             */
            $data=[
                'new_password'=>input('post.new_password'),
                'confirm_password'=>input('post.confirm_password')
            ];

            $memberconnect_validate = validate('memberconnect');
            if (!$memberconnect_validate->scene('weixinunbind')->check($data)) {
                $this->error($memberconnect_validate->getError());
            }

            $update_arr['member_password'] = md5(trim(input('post.new_password')));
        }
        $update_arr['member_wxunionid'] = '';
        $update_arr['member_wxopenid'] = '';
        $update_arr['member_wxinfo'] = '';
        $edit_state = $member_model->editMember(array('member_id' => session('member_id')), $update_arr);
        if (!$edit_state) {
            $this->error(lang('ds_common_save_fail'));
        }
       session(null);
       $this->success(lang('wechat_was_unbound_successfully'), HOME_SITE_URL.'/Login/login.html?ref_url='. urlencode(url('Memberconnect/weixinbind')));
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string	$menu_type	导航类型
     * @param string 	$name	当前导航的name
     * @param array 	$array		附加菜单
     * @return
     */
    protected function getMemberItemList() {
        $menu_array = array(
            array('name' => 'qq_bind', 'text' => lang('ds_member_path_qq_bind'), 'url' => url('Memberconnect/qqbind')),
            array('name' => 'sina_bind', 'text' => lang('ds_member_path_sina_bind'), 'url' => url('Memberconnect/sinabind')),
            array('name' => 'weixin_bind', 'text' => lang('micro_letter_binding'), 'url' => url('Memberconnect/weixinbind')),
        );
        return $menu_array;
    }

}