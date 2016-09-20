<?php

namespace Backend\Controllers;

use \Phalcon\Forms\Form;
use \Phalcon\Forms\Element\Text;
use \Phalcon\Forms\Element\Select;
use \Phalcon\Paginator\Adapter\Model	as PaginatorModel;
use \Backend\Models\Users;
use \Backend\Models\Tourists;

class TouristsController extends ControllerBase
{
	//TODO: nationality
	public function indexAction()
	{
		$search = $this->request->get('search');
		$searchAdd = '';

		if($search)
		{
			$query = "SELECT * FROM \Backend\Models\Tourists
						WHERE (\Backend\Models\Tourists.passport_number LIKE :search:
						OR \Backend\Models\Tourists.passport_surname LIKE :search:) ";

			if($this->user->role == Users::ROLE_MANAGER)
			{
				$query .= 'AND manager_id = ' . $this->user->id . ' ';
			}

			$query .= 'ORDER BY creationDate DESC';

			$tourists =$this->modelsManager->executeQuery($query, ['search' => '%'.$search.'%']);

			$searchAdd = '&search=' . $search;
		}
		else
		{
			$query = [
				'order'	=> 'creationDate DESC',
			];

			if($this->user->role == Users::ROLE_MANAGER)
			{
				$query[] = 'manager_id = ' . $this->user->id;
			}

			$tourists = Tourists::find($query);
		}

		$paginator = new PaginatorModel(
			array(
				'data'  => $tourists,
				'limit' => 50,
				'page'  => $this->request->get('page')
			)
		);

		$this->view->setVar('page', $paginator->getPaginate());
		$this->view->setVar('search', $search);
		$this->view->setVar('searchAdd', $searchAdd);
	}

	public function ajaxGetAction()
	{
		if($this->request->isGet())
		{
			$search = mb_strtoupper($this->request->get('term'));

			$query = "SELECT * FROM \Backend\Models\Tourists
						WHERE \Backend\Models\Tourists.passport_number LIKE :search:
						OR \Backend\Models\Tourists.passport_surname LIKE :search:
						LIMIT 20";
			$tourists =$this->modelsManager->executeQuery($query, ["search" => '%'.$search.'%']);

			$response = [];

			foreach($tourists as $tourist)
			{
				$response[] = $tourist->toArray();
			}

			echo json_encode($response);
			$this->view->disable();
		}
		else
		{
			$this->response->redirect('404');
		}
	}

	public function ajaxEditFieldAction()
	{
		if($this->request->isPost())
		{
			$this->view->disable();

			$fieldName	= $this->request->getPost('name');
			$fieldValue	= $this->request->getPost('value');
			$fieldId	= $this->request->getPost('pk');

			$tourist = Tourists::findFirst($fieldId);

			if($tourist)
			{
				$tourist->$fieldName = $fieldValue;

				$tourist->save();
			}
		}
		else
		{
			$this->response->redirect('404');
		}


	}

	public function ajaxAddAction(){

		if($this->request->isPost())
		{
			$response = new \stdClass();
			$tourist = [];

			$tourist['passport_surname'] = $this->request->getPost('tourist-passport-surname');
			$tourist['passport_name'] = $this->request->getPost('tourist-passport-name');
			$tourist['passport_number'] = $this->request->getPost('tourist-passport-number');
			$tourist['passport_issued'] = $this->request->getPost('tourist-passport-issued');
			$tourist['passport_endDate'] = $this->request->getPost('tourist-passport-endDate');
			$tourist['birthDate'] = $this->request->getPost('tourist-birthDate');
			$tourist['phone'] = $this->request->getPost('tourist-phone');
			$tourist['email'] = $this->request->getPost('tourist-email');
			$tourist['gender'] = $this->request->getPost('tourist-gender');
			$tourist['nationality'] = $this->request->getPost('tourist-nationality');

			$query = "SELECT * FROM \Backend\Models\Tourists
						WHERE \Backend\Models\Tourists.passport_number = :passport_number:
						AND \Backend\Models\Tourists.passport_surname = :passport_surname:
						AND \Backend\Models\Tourists.passport_name = :passport_name:
						LIMIT 1";

			$touristModel = $this->modelsManager->executeQuery($query, [
				'passport_number' => $tourist['passport_number'],
				'passport_name' => $tourist['passport_name'],
				'passport_surname' => $tourist['passport_surname']
			])->getFirst();

			if($touristModel)
			{
				$response->tourist = $touristModel->toArray();
			}
			else
			{
				$touristModel = new Tourists();

				$touristModel->passport_number = $tourist['passport_number'];
				$touristModel->passport_surname = $tourist['passport_surname'];
				$touristModel->passport_name = $tourist['passport_name'];
				$touristModel->passport_issued = $tourist['passport_issued'];
				$touristModel->passport_endDate = $tourist['passport_endDate'];
				$touristModel->birthDate = $tourist['birthDate'];
				$touristModel->phone = $tourist['phone'];
				$touristModel->email = $tourist['email'];
				$touristModel->gender = $tourist['gender'];
				$touristModel->nationality = $tourist['nationality'];

				$touristModel->save();

				$response->tourist = $touristModel->toArray();
			}

			echo json_encode($response);

			$this->view->disable();
		}
		else
		{
			$this->request->redirect('404');
		}


	}

	public function editAction($touristId)
	{
		$tourist = Tourists::findFirst($touristId);

		$form = new Form($tourist);
		$form->add(new Text("passport_name"));
		$form->add(new Text("passport_surname"));
		$form->add(new Text("passport_number"));
		$form->add(new Text("passport_endDate"));
		$form->add(new Text("passport_issued"));
		$form->add(new Select("gender", ['m'=>'Мужской', 'f'=>'Женский']));
		$form->add(new Text("phone"));
		$form->add(new Text("email"));
		$form->add(new Text("birthDate"));
		$form->add(new Text("nationality"));

		if($this->request->isPost())
		{
			$form->bind($_POST, $tourist);
			if($form->isValid())
			{
				$tourist->save();
				$this->flashSession->success('Данные туриста успешно сохранены');
			}
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('tourist', $tourist);
	}

	public function addAction()
	{
		$form = new Form();

		$form->add(new Text("passport_name"));
		$form->add(new Text("passport_surname"));
		$form->add(new Text("passport_number"));
		$form->add(new Text("passport_endDate"));
		$form->add(new Text("passport_issued"));
		$form->add(new Select("gender", ['m'=>'Мужской', 'f'=>'Женский']));
		$form->add(new Text("phone"));
		$form->add(new Text("email"));
		$form->add(new Text("birthDate"));
		$form->add(new Text("nationality"));

		if($this->request->isPost())
		{
			$tourist = new Tourists();

			$tourist->passport_name = $this->request->getPost('passport_name');
			$tourist->passport_surname = $this->request->getPost('passport_surname');
			$tourist->passport_number = $this->request->getPost('passport_number');
			$tourist->passport_endDate = $this->request->getPost('passport_endDate');
			$tourist->passport_issued = $this->request->getPost('passport_issued');
			$tourist->gender = $this->request->getPost('gender');
			$tourist->phone = $this->request->getPost('phone');
			$tourist->email = $this->request->getPost('email');
			$tourist->birthDate = $this->request->getPost('birthDate');
			$tourist->nationality = $this->request->getPost('nationality');

			if($tourist->save())
			{
				$this->flashSession->success('Турист успешно добавлен');
				return $this->response->redirect('tourists/edit/' . $tourist->id);
			}
			else
			{
				foreach($tourist->getMessages() as $message)
				{
					$this->flashSession->error($message);
				}
			}
		}

		$this->view->setVar('form', $form);

	}
}