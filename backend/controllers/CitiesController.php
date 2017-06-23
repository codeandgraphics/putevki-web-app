<?php

namespace Backend\Controllers;

use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Backend\Models\Users;
use Models\Cities;
use Models\Branches;
use Models\Tourvisor;

class CitiesController extends ControllerBase
{

	public function indexAction()
	{
		$cities = Cities::find(['order' => 'main DESC, name']);

		$this->view->setVar('cities', $cities);
	}

	public function cityAction($cityId)
	{

		$city = Cities::findFirst($cityId);
		$branches = Branches::find("cityId='" . $city->id . "'");

		$form = new Form($city);

		$departures = Tourvisor\Departures::find(['order' => 'name']);

		$formDepartures = [];

		foreach ($departures as $departure) {
			$formDepartures[$departure->id] = $departure->name;
		}

		$form->add(new Text('name'));
		$form->add(new Text('name_gen'));
		$form->add(new Text('uri'));
		$form->add(new Text('lat'));
		$form->add(new Text('lon'));
		$form->add(new Text('zoom'));
		$form->add(new Select('flight_city', $formDepartures));
		$form->add(new Text('phone'));
		$form->add(new Select('main', [0 => 'Выкл', 1 => 'Вкл']));
		$form->add(new Select('active', [0 => 'Выкл', 1 => 'Вкл']));
		$form->add(new Text('meta_keywords'));
		$form->add(new TextArea('meta_text'));
		$form->add(new TextArea('meta_description'));

		if ($this->request->isPost()) {
			$form->bind($_POST, $city);
			if ($form->isValid()) {
				$city->save();
				$this->flashSession->success('Город успешно сохранен');
			}
		}

		$this->view->setVar('city', $city);
		$this->view->setVar('form', $form);
		$this->view->setVar('branches', $branches);

	}

	public function addAction()
	{

		$departures = Tourvisor\Departures::find(['order' => 'name']);

		$formDepartures = [];

		foreach ($departures as $departure) {
			$formDepartures[$departure->id] = $departure->name;
		}

		$form = new Form();
		$form->add(new Text('name'));
		$form->add(new Text('name_gen'));
		$form->add(new Text('uri'));
		$form->add(new Text('lat'));
		$form->add(new Text('lon'));
		$form->add(new Text('zoom'));
		$form->add(new Select('flight_city', $formDepartures));
		$form->add(new Text('phone'));
		$form->add(new Select('main', [0 => 'Нет', 1 => 'Да']));
		$form->add(new Select('active', [0 => 'Нет', 1 => 'Да']));
		$form->add(new Text('meta_keywords'));
		$form->add(new TextArea('meta_text'));
		$form->add(new TextArea('meta_description'));


		if ($this->request->isPost()) {
			$city = new Cities();

			$city->name = $this->request->getPost('name');
			$city->name_gen = $this->request->getPost('name_gen');
			$city->uri = $this->request->getPost('uri');
			$city->lat = $this->request->getPost('lat');
			$city->lon = $this->request->getPost('lon');
			$city->zoom = $this->request->getPost('zoom');
			$city->flight_city = $this->request->getPost('flight_city');
			$city->phone = $this->request->getPost('phone');
			$city->main = $this->request->getPost('main');
			$city->active = $this->request->getPost('active');

			$city->meta_description = $this->request->getPost('meta_description');
			$city->meta_text = $this->request->getPost('meta_text');
			$city->meta_keywords = $this->request->getPost('meta_keywords');

			if ($city->save()) {
				$this->flashSession->success('Город успешно добавлен');
				return $this->response->redirect('cities/city/' . $city->id);
			}

			foreach ($city->getMessages() as $message) {
				$this->flashSession->error($message);
			}
		}

		$this->view->setVar('departures', $departures);
		$this->view->setVar('form', $form);
	}

	public function branchAddAction($cityId)
	{
		$city = Cities::findFirst($cityId);

		$yesNoArray = array(0 => 'Нет', 1 => 'Да');

		$form = new Form();
		$form->add(new Text('name'));
		$form->add(new Text('address'));
		$form->add(new Text('addressDetails'));
		$form->add(new Text('timetable'));
		$form->add(new Text('phone'));
		$form->add(new Text('site'));
		$form->add(new Text('email'));
		$form->add(new Text('additionalEmails'));
		$form->add(new Text('lat'));
		$form->add(new Text('lon'));
		$form->add(new Select('main', $yesNoArray));
		$form->add(new Select('active', $yesNoArray));

		$form->add(new Text('managerName'));

		$form->add(new Text('meta_keywords'));
		$form->add(new TextArea('meta_text'));
		$form->add(new TextArea('meta_description'));


		if ($this->request->isPost()) {
			$branch = new Branches();

			$branch->name = $this->request->getPost('name');
			$branch->address = $this->request->getPost('address');
			$branch->addressDetails = $this->request->getPost('addressDetails');
			$branch->timetable = $this->request->getPost('timetable');
			$branch->phone = $this->request->getPost('phone');
			$branch->site = $this->request->getPost('site');
			$branch->email = $this->request->getPost('email');
			$branch->additionalEmails = $this->request->getPost('additionalEmails');
			$branch->lat = $this->request->getPost('lat');
			$branch->lon = $this->request->getPost('lon');
			$branch->main = $this->request->getPost('main');
			$branch->active = $this->request->getPost('active');
			$branch->cityId = $city->id;

			$branch->meta_description = $this->request->getPost('meta_description');
			$branch->meta_text = $this->request->getPost('meta_text');
			$branch->meta_keywords = $this->request->getPost('meta_keywords');

			if ($branch->create()) {
				$password = $this->security->getToken();

				$manager = new Users();
				$manager->role = Users::ROLE_MANAGER;
				$manager->name = $this->request->getPost('managerName');
				$manager->email = $branch->email;
				$manager->company = $branch->name;
				$manager->password = $this->security->hash($password);
				$manager->branch_id = $branch->id;

				if ($manager->create()) {
					$branch->update([
						'manager_id' => $manager->id,
						'managerPassword' => $password
					]);

					$email = new EmailController();
					$email->sendPassword($manager->email, $password);

					$this->flashSession->success('Филиал успешно добавлен');
					return $this->response->redirect('cities/city/' . $city->id);
				}

				$branch->delete();
				foreach ($manager->getMessages() as $message) {
					$this->flashSession->error($message);
				}
			} else {
				foreach ($branch->getMessages() as $message) {
					$this->flashSession->error($message);
				}
			}
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('city', $city);
	}

	public function branchAction($cityId, $branchId)
	{
		$city = Cities::findFirst($cityId);

		$branch = Branches::findFirst($branchId);

		$oldPassword = $branch->managerPassword;

		$yesNoArray = array(0 => 'Нет', 1 => 'Да');

		$form = new Form($branch);
		$form->add(new Text('name'));
		$form->add(new Text('address'));
		$form->add(new Text('addressDetails'));
		$form->add(new Text('timetable'));
		$form->add(new Text('phone'));
		$form->add(new Text('site'));
		$form->add(new Text('email'));
		$form->add(new Text('additionalEmails'));
		$form->add(new Text('lat'));
		$form->add(new Text('lon'));
		$form->add(new Text('managerPassword'));
		$form->add(new Select('main', $yesNoArray));
		$form->add(new Select('active', $yesNoArray));

		$form->add(new Text('meta_keywords'));
		$form->add(new TextArea('meta_text'));
		$form->add(new TextArea('meta_description'));


		if ($this->request->isPost()) {
			$form->bind($_POST, $branch);
			if ($form->isValid()) {
				$branch->save();

				if (!$branch->manager) {
					$manager = new Users();
					$manager->role = Users::ROLE_MANAGER;
					$manager->name = $branch->name;
					$manager->email = $branch->email;
					$manager->company = $branch->name;
					$manager->password = $this->security->hash($branch->managerPassword);
					$manager->branch_id = $branch->id;

					if ($manager->create()) {
						$branch->manager_id = $manager->id;
						$branch->save();
					}
				}

				if ($oldPassword !== $branch->managerPassword) {
					$branch->manager->update([
						'password' => $this->security->hash($branch->managerPassword)
					]);

					$email = new EmailController();
					$email->sendPassword($branch->manager->email, $branch->managerPassword);
				}

				$this->flashSession->success('Филиал успешно сохранен');
			}
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('city', $city);
		$this->view->setVar('branch', $branch);
	}

}

