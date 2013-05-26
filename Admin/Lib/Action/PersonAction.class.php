<?php
class PersonAction extends CommonAction {
    public function password(){
		$this->assign("jumpUrl","__SELF__");
		if(isset($_POST['dosubmit'])) {
			if(!empty($_POST['new_password']) && !empty($_POST['old_password'])) {
				$model = D("Admin"); 
				$row = $model->getById($this->adminid);
				if($row['apwd']!=md5($_POST['old_password'])) $this->error('旧密码错误');
				else {
					$model->where('id='.$row['id'])->setField('apwd',md5($_POST['new_password']));
					$this->success('修改密码成功');
				}
			} else {
				$this->success('没有修改密码');
			}
		} else {
			$this->assign("adminname",$this->adminname);
			$this->display();
		}
    }
}