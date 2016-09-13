<?php

namespace Backend\Plugins;

use Phalcon\Acl,
	Phalcon\Acl\Role,
	Phalcon\Acl\Resource,
	Phalcon\Acl\Adapter\Memory as AclList,
	Phalcon\Events\Event,
	Phalcon\Mvc\User\Plugin,
	Phalcon\Mvc\Dispatcher,
	Backend\Models\Users;

class Security extends Plugin
{

	private function _getAcl()
	{
		$acl = new AclList();

		$acl->setDefaultAction(Acl::DENY);

		$roles = [
			Users::ROLE_GUEST	=> new Role(Users::ROLE_GUEST),
			Users::ROLE_MANAGER	=> new Role(Users::ROLE_MANAGER),
			Users::ROLE_ADMIN	=> new Role(Users::ROLE_ADMIN)
		];

		foreach($roles as $role)
		{
			$acl->addRole($role);
		}

		$publicResources = [
			'index'	=> ['error404'],
			'users'	=> ['login', 'logout']
		];

		foreach($publicResources as $resource => $actions)
		{
			$acl->addResource(new Resource($resource), $actions);
		}

		foreach ($roles as $role)
		{
			foreach ($publicResources as $resource => $actions)
			{
				$acl->allow($role->getName(), $resource, $actions);
			}
		}

		$managerResources = [
			'index'		=> ['index'],
			'requests'	=> ['index','add','edit'],
			'tourists'	=> ['index','add','edit'],
			'payments'	=> ['index']
		];


		foreach($managerResources as $resource => $actions)
		{
			$acl->addResource(new Resource($resource), $actions);
		}

		foreach ($managerResources as $resource => $actions)
		{
			foreach ($actions as $action)
			{
				$acl->allow(Users::ROLE_MANAGER, $resource, $action);
			}
		}

		$acl->allow(Users::ROLE_ADMIN, '*', '*');

		return $acl;

	}

	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	{
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$acl = $this->_getAcl();

		$auth = $this->session->get('auth');

		if (!$auth)
		{
			$role = Users::ROLE_GUEST;
		}
		else
		{
			$role = $auth['role'];
		}

		$allowed = $acl->isAllowed($role, $controller, $action);

		if ($allowed != Acl::ALLOW) {

			$this->flash->error("You don't have access to this module");

			if($role == Users::ROLE_GUEST)
			{
				$dispatcher->forward(
					array(
						'controller' => 'users',
						'action'     => 'login'
					)
				);
			}
			else
			{
				$dispatcher->forward(
					array(
						'controller' => 'index',
						'action'     => 'index'
					)
				);
			}

			return false;
		}

	}

}