<?php

/**
 * 命令分发器
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Util
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:18:56
 */
class Touzhijia_Platform_Util_CmdChain
{
	/**
	 * 设置默认动作名, 确保不可能与任何动作名冲突
	 */
	const DEFAULT_ACTION_NAME = '@@default_action_name@@';


	/**
	 * @var array 动作映射器数组
	 */
	private $_arrAction;


	/**
	 * 设定动作执行器
	 *
	 * @param string $actName    动作名
	 * @param string $className  执行器类名
	 * @param string $methodName 执行器方法
	 * @return null
	 */
	public function setAction($actName, $className, $methodName)
	{
		$actName = strtolower($actName);
		$this->_arrAction[$actName] = array('class' => $className, 'method' => $methodName);
	}


	/**
	 * 设定默认动作执行器
	 *
	 * @param string $className  执行器类名
	 * @param string $methodName 执行器方法
	 * @return null
	 */
	public function setDefaultAction($className, $methodName)
	{
		$this->setAction(self::DEFAULT_ACTION_NAME, $className, $methodName);
	}


	/**
	 * 动作分发
	 *
	 * @param string $actName  动作名
	 * @param mix    $argValue 要传递给执行器的参数
	 * @return mix
	 */
	public function dispatch($actName, $argValue)
	{
		$actName = strtolower($actName);

		// 如果无法分发, 则执行默认动作
		if (!array_key_exists($actName, $this->_arrAction)) {
			return $this->dispatch(self::DEFAULT_ACTION_NAME, $argValue);
		}

		// 否则, 执行预设动作
		$className  = $this->_arrAction[$actName]['class'];
		$methodName = $this->_arrAction[$actName]['method'];

		$ins = new $className();
		return $ins->$methodName($argValue);
	}

}
