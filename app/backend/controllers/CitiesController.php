<?php

namespace Backend\Controllers;

use Models\Countries;
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

		$items = Tourvisor\Departures::find(['order' => 'name']);

		$formDepartures = [];

		foreach ($items as $item) {
			$formDepartures[$item->id] = $item->name;
		}

		$countries = Countries::find();

		$formCountries = [];

		foreach($countries as $country) {
		    if($country->tourvisorId > 0) {
                $formCountries[$country->tourvisorId] = $country->tourvisor->name;
            }
        }

        $popCountries = $this->request->getPost('popularCountries');


		if($popCountries) {
            $city->popularCountries = $popCountries;
        }

		$countriesSelect = new Select('popularCountries[]', $formCountries, ['multiple' => true]);

        $countriesSelect->setDefault($city->popularCountries);

		$form->add(new Text('name'));
		$form->add(new Text('nameGen'));
		$form->add(new Text('uri'));
		$form->add(new Text('lat'));
		$form->add(new Text('lon'));
		$form->add(new Text('zoom'));
		$form->add(new Select('flightCity', $formDepartures));
        $form->add($countriesSelect);
		$form->add(new Text('phone'));
		$form->add(new Select('main', [0 => 'Выкл', 1 => 'Вкл']));
		$form->add(new Select('active', [0 => 'Выкл', 1 => 'Вкл']));
		$form->add(new Text('metaKeywords'));
		$form->add(new TextArea('metaText'));
		$form->add(new TextArea('metaDescription'));

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
		$form->add(new Text('uri'));
		$form->add(new Text('lat'));
		$form->add(new Text('lon'));
		$form->add(new Text('zoom'));
		$form->add(new Select('flightCity', $formDepartures));
		$form->add(new Text('phone'));
		$form->add(new Select('main', [0 => 'Нет', 1 => 'Да']));
		$form->add(new Select('active', [0 => 'Нет', 1 => 'Да']));
		$form->add(new Text('metaKeywords'));
		$form->add(new TextArea('metaText'));
		$form->add(new TextArea('metaDescription'));


		if ($this->request->isPost()) {
			$city = new Cities();

			$city->name = $this->request->getPost('name');
			$city->uri = $this->request->getPost('uri');
			$city->lat = $this->request->getPost('lat');
			$city->lon = $this->request->getPost('lon');
			$city->zoom = $this->request->getPost('zoom');
			$city->flightCity = $this->request->getPost('flightCity');
			$city->phone = $this->request->getPost('phone');
			$city->main = $this->request->getPost('main');
			$city->active = $this->request->getPost('active');

			$city->metaDescription = $this->request->getPost('metaDescription');
			$city->metaText = $this->request->getPost('metaText');
			$city->metaKeywords = $this->request->getPost('metaKeywords');

			if ($city->save()) {
				$this->flashSession->success('Город успешно добавлен');
				return $this->response->redirect($this->backendUrl->get('cities/city/' . $city->id));
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

		$form->add(new Text('metaKeywords'));
		$form->add(new TextArea('metaText'));
		$form->add(new TextArea('metaDescription'));


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

			$branch->metaDescription = $this->request->getPost('metaDescription');
			$branch->metaText = $this->request->getPost('metaText');
			$branch->metaKeywords = $this->request->getPost('metaKeywords');

			if ($branch->create()) {
				$password = $this->security->getToken();

				$manager = new Users();
				$manager->role = Users::ROLE_MANAGER;
				$manager->name = $this->request->getPost('managerName');
				$manager->email = $branch->email;
				$manager->company = $branch->name;
				$manager->password = $this->security->hash($password);
				$manager->branchId = $branch->id;

				if ($manager->create()) {
					$branch->update([
						'managerId' => $manager->id,
						'managerPassword' => $password
					]);

					$email = new EmailController();
					$email->sendPassword($manager->email, $password);

					$this->flashSession->success('Филиал успешно добавлен');
					return $this->response->redirect($this->backendUrl->get('cities/city/' . $city->id));
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

		$form->add(new Text('metaKeywords'));
		$form->add(new TextArea('metaText'));
		$form->add(new TextArea('metaDescription'));


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
					$manager->branchId = $branch->id;

					if ($manager->create()) {
						$branch->managerId = $manager->id;
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

