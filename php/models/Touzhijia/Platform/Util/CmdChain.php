<?php


class Touzhijia_Platform_Util_CmdChain
{
	private $_arrAction;

	public function setAction($actName, $className, $methodName)
	{
		$actName = strtolower($actName);
		$this->_arrAction[$actName] = array('class' => $className, 'method' => $methodName);
	}

	public function dispatch($actName, $argValue)
	{
		$actName = strtolower($actName);
		if (!array_key_exists($actName, $this->_arrAction)) {
			print_r($this->_arrAction);
			return false;
		}

		$className  = $this->_arrAction[$actName]['class'];
		$methodName = $this->_arrAction[$actName]['method'];

		$ins = new $className();
		return $ins->$methodName($argValue);
	}

}
