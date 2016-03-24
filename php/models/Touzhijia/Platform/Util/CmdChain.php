<?php


class Touzhijia_Platform_Util_CmdChain
{
	// 设置默认动作名, 确保不可能与任何动作名冲突
	const DEFAULT_ACTION_NAME = '@@default_action_name@@';

	private $_arrAction;

	public function setAction($actName, $className, $methodName)
	{
		$actName = strtolower($actName);
		$this->_arrAction[$actName] = array('class' => $className, 'method' => $methodName);
	}

	public function setDefaultAction($className, $methodName)
	{
		$this->setAction(self::DEFAULT_ACTION_NAME, $className, $methodName);
	}

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
