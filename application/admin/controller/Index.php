<?php
namespace app\admin\controller;
use think\Request;
use think\Cookie;
use think\Session;

class Index extends Base
{
	private $rule = [
	        ['user','require','账号必须填写!'],
	        ['pass','require','密码必须填写!'],
        ];
	public function __construct(){
		parent::__construct();
		$this->param = Request::instance()->param();
	}
	public function index(){

        $user = Cookie::get('user');
        $pass = Cookie::get('pass');
        $this->assign('user',$user);
        $this->assign('pass',$pass);
        return $this->fetch();
    }

    public function login()
    {
    	if ($_POST) 
    	{
    		$username = htmlspecialchars(trim($this->param['user']));
    		$password = htmlspecialchars(trim($this->param['pass']));

    		$verify = parent::verify($this->param,$this->rule);
    		if ($verify) 
    		{
    			$this->error($verify);
    		}

    		var_dump($_POST);die;
    	}
    	return $this->fetch('Index/index');
    }

    // 后台管理员session
    public function AdminSession() {
        $admin=Session::get('userwy');
        if(empty($admin)){
            echo '<script language="javascript">top.location="'.url('Index/index').'";</script>';
        }
        $url = $_SERVER['HTTP_HOST'];
        $url = $url . $_SERVER['REQUEST_URI'];
        $rolelist = Session::get("rolelist");
        
        if ($rolelist == null) {
            $this->error('您的账号未分配任何权限', url('Index/login'));
        }
   
        $result = $this->CheckAuthority($url);
        
        $admin=Session::get('userwy');
        $userid = $admin['c_id'];
        $username = $admin['c_username'];
        $parameter = $this->GetParameter();
        
        if (!$result) {
            $this->error('您没有权限访问', url('Index/right'));
        } else {
            //写入日志
            //$this->InsertLog($userid, $username, $url, $parameter);
            return true;
        }
    }
}
