<?
/**
 +------------------------------------------------------------------------------
 * Spring框架 404错误默认控制器
 +------------------------------------------------------------------------------
 * @mobile	13183857698
 * @qq		78252859
 * @author  void <lkf5_303@163.com>
 * @version 3.1.4
 +------------------------------------------------------------------------------
 */
class ErrorAction extends Action
{
	/**
	 * 显示404错误(可在应用层自定义)
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{
		MessageBox::halt('正在开发中...');
	}
}
?>