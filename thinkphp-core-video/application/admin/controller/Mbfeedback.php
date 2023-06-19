<?php

namespace app\admin\controller;

use think\Lang;/**
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
class  Mbfeedback extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'admin/lang/'.config('default_lang').'/mobile.lang.php');
    }
    /**
     * 意见反馈
     */
    public function flist(){
        $mbfeedback_model = model('mbfeedback');
        $mbfeedback_list = $mbfeedback_model->getMbfeedbackList(array(), 10);

       $this->assign('mbfeedback_list', $mbfeedback_list);
       $this->assign('show_page', $mbfeedback_model->page_info->render());
       $this->setAdminCurItem('index');
       return $this->fetch('index');
    }

    /**
     * 删除
     */
    public function del(){
        $mbfeedback_model = model('mbfeedback');
        $feedback_id = input('param.feedback_id');
        $feedback_id_array = ds_delete_param($feedback_id);
        $condition = array('mbfb_id' => array('in', $feedback_id_array));
        $result = $mbfeedback_model->delMbfeedback($condition);
        if ($result){
            ds_json_encode(10000, lang('ds_common_op_succ'));
        }else {
            ds_json_encode(10001, lang('ds_common_op_fail'));
        }
    }
    protected function getAdminItemList() {
        $menu = array(
            array(
                'text' => lang('feedback_mange_title'), 'name' => 'index', 'url' => ''
            ),
        );
        return $menu;
    }
}