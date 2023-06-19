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
class  Memberinformation extends BaseMember {

    public function _initialize() {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'home/lang/' . config('default_lang') . '/memberhome.lang.php');
    }

    /**
     * 我的资料【用户中心】
     *
     * @param
     * @return
     */
    public function index() {
        $member_model = model('member');

        if (request()->isPost()) {
            $member_array = array();
            $member_array['member_nickname'] = input('post.member_nickname');
            $member_array['member_sex'] = input('post.member_sex');
            $member_array['member_qq'] = input('post.member_qq');
            $member_array['member_ww'] = input('post.member_ww');
            $member_array['member_areaid'] = input('post.area_id');
            $member_array['member_cityid'] = input('post.city_id');
            $member_array['member_provinceid'] = input('post.province_id');
            $member_array['member_areainfo'] = input('post.area_info');
            if (strlen(input('post.birthday')) == 10) {
                $member_array['member_birthday'] = strtotime(input('post.birthday'));
            }
            $member_array['member_privacy'] = serialize(input('post.privacy/a'));
            $update = $member_model->editMember(array('member_id' => session('member_id')), $member_array);

            $message = $update ? lang('ds_common_save_succ') : lang('ds_common_save_fail');

            if ($update) {
                ds_json_encode(10000, $message);
            } else {
                ds_json_encode(10001, $message);
            }
        }
        if ($this->member_info['member_privacy'] != '') {
            $this->member_info['member_privacy'] = unserialize($this->member_info['member_privacy']);
        } else {
            $this->member_info['member_privacy'] = array();
        }
        $this->assign('member_info', $this->member_info);
        /* 设置买家当前菜单 */
        $this->setMemberCurMenu('member_information');
        /* 设置买家当前栏目 */
        $this->setMemberCurItem('member');
        $this->assign('menu_sign', 'profile');
        $this->assign('menu_sign_url', url('Memberinformation/index'));
        $this->assign('menu_sign1', 'baseinfo');
        return $this->fetch($this->template_dir . 'index');
    }


    public function upload() {
        if (!request()->isPost()) {
            $this->redirect('memberinformation/avatar');
        }
        $member_id = session('member_id');

        //上传图片

        if (!empty($_FILES['pic']['tmp_name'])) {
            $file_object = request()->file('pic');
            $base_url = BASE_UPLOAD_PATH . '/' . ATTACH_AVATAR . '/';
            $ext = strtolower(pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION));
            $file_name = 'avatar_' . $member_id . '_new' . ".$ext";
            $info = $file_object->rule('uniqid')->validate(['ext' => ALLOW_IMG_EXT])->move($base_url, $file_name);
            if ($info) {
                $name_dir = BASE_UPLOAD_PATH . '/' . ATTACH_AVATAR . '/' . $info->getFilename();

                $imageinfo = getimagesize($name_dir);
                /* 设置买家当前菜单 */
                $this->setMemberCurMenu('member_information');
                /* 设置买家当前栏目 */
                $this->setMemberCurItem('avatar');
                $this->assign('menu_sign', 'profile');
                $this->assign('menu_sign_url', "{:url('Memberinformation/index')}");
                $this->assign('menu_sign1', 'avatar');
                $file_dir = UPLOAD_SITE_URL . '/' . ATTACH_AVATAR . '/' . $info->getFilename();

                $this->assign('newfile', $file_dir);
                $this->assign('height', $imageinfo[1]);
                $this->assign('width', $imageinfo[0]);
                return $this->fetch($this->template_dir . 'avatar');
            } else {
                $this->error($file_object->getError());
            }
        } else {
            $this->error(lang('upload_failed_replace_pictures'));
        }
    }

    /**
     * 裁剪
     *
     */
    public function cut() {
        if (request()->isPost()) {
            $x1 = input('post.x1');
            $y1 = input('post.y1');
            $x2 = input('post.x2');
            $y2 = input('post.y2');
            $w = input('post.w');
            $h = input('post.h');

            $newfile = str_replace(str_replace('/index.php', '', BASE_SITE_URL) . '/uploads', BASE_UPLOAD_PATH, input('post.newfile'));

            $avatarfile = BASE_UPLOAD_PATH . '/' . ATTACH_AVATAR . '/' . "avatar_" . session('member_id') . ".jpg";
            $image_av = \think\Image::open($newfile);
            $image_av->crop($w, $h, $x1, $y1)->save($avatarfile);
            @unlink($newfile);
            model('member')->editMember(array('member_id' => session('member_id')), array('member_avatar' => 'avatar_' . session('member_id') . '.jpg'));
            $avatar_url = 'avatar_' . session('member_id') . '.jpg';
            session('avatar', $avatar_url);
            $this->redirect('memberinformation/avatar');
        }
    }

    /**
     * 更换头像
     *
     * @param
     * @return
     */
    public function avatar() {
        $member_info = model('member')->getMemberInfoByID(session('member_id'));
        $this->assign('member_avatar', $member_info['member_avatar']);
        /* 设置买家当前菜单 */
        $this->setMemberCurMenu('member_information');
        /* 设置买家当前栏目 */
        $this->setMemberCurItem('avatar');

        $this->assign('menu_sign', 'profile');
        $this->assign('menu_sign_url', url('Memberinformation/index'));
        $this->assign('menu_sign1', 'avatar');
        $this->assign('newfile', '');
        return $this->fetch($this->template_dir . 'avatar');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @return
     */
    public function getMemberItemList() {
        $menu_array = array(
            array(
                'name' => 'member',
                'text' => lang('home_member_base_infomation'),
                'url' => url('Memberinformation/index')
            ),
            array(
                'name' => 'avatar',
                'text' => lang('home_member_modify_avatar'),
                'url' => url('Memberinformation/avatar')
            )
        );

        return $menu_array;
    }

}
