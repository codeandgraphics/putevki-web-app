<?php

namespace Backend\Controllers;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Backend\Models\Users;
use Backend\Models\Tourists;

class TouristsController extends ControllerBase
{
    //TODO: nationality
    public function indexAction()
    {
        $search = $this->request->get('search');
        $searchAdd = '';

        if ($search) {
            $query = "SELECT * FROM \Backend\Models\Tourists
						WHERE (\Backend\Models\Tourists.passportNumber LIKE :search:
						OR \Backend\Models\Tourists.passportSurname LIKE :search:) ";

            if ($this->user->role === Users::ROLE_MANAGER) {
                $query .= 'AND managerId = ' . $this->user->id . ' ';
            }

            $query .= 'ORDER BY creationDate DESC';

            $tourists = $this->modelsManager->executeQuery($query, [
                'search' => '%' . $search . '%'
            ]);

            $searchAdd = '&search=' . $search;
        } else {
            $query = [
                'order' => 'creationDate DESC'
            ];

            if ($this->user->role === Users::ROLE_MANAGER) {
                $query[] = 'managerId = ' . $this->user->id;
            }

            $tourists = Tourists::find($query);
        }

        $paginator = new PaginatorModel(array(
            'data' => $tourists,
            'limit' => 50,
            'page' => $this->request->get('page')
        ));

        $this->view->setVar('page', $paginator->getPaginate());
        $this->view->setVar('search', $search);
        $this->view->setVar('searchAdd', $searchAdd);
    }

    public function ajaxGetAction()
    {
        if ($this->request->isGet()) {
            $search = mb_strtoupper($this->request->get('term'));

            $query = "SELECT * FROM \Backend\Models\Tourists
						WHERE \Backend\Models\Tourists.passportNumber LIKE :search:
						OR \Backend\Models\Tourists.passportSurname LIKE :search:
						LIMIT 20";
            $tourists = $this->modelsManager->executeQuery($query, [
                'search' => '%' . $search . '%'
            ]);

            $response = [];

            foreach ($tourists as $tourist) {
                $response[] = $tourist->toArray();
            }

            echo json_encode($response);
            $this->view->disable();
        } else {
            $this->response->redirect($this->backendUrl->get('404'));
        }
    }

    public function ajaxEditFieldAction()
    {
        if ($this->request->isPost()) {
            $this->view->disable();

            $fieldName = $this->request->getPost('name');
            $fieldValue = $this->request->getPost('value');
            $fieldId = $this->request->getPost('pk');

            $tourist = Tourists::findFirst($fieldId);

            if ($tourist) {
                $tourist->$fieldName = $fieldValue;

                $tourist->save();
            }
        } else {
            $this->response->redirect($this->backendUrl->get('404'));
        }
    }

    public function ajaxAddAction()
    {
        if ($this->request->isPost()) {
            $response = new \stdClass();
            $tourist = [];

            $tourist['passportSurname'] = $this->request->getPost(
                'tourist-passport-surname'
            );
            $tourist['passportName'] = $this->request->getPost(
                'tourist-passport-name'
            );
            $tourist['passportNumber'] = $this->request->getPost(
                'tourist-passport-number'
            );
            $tourist['passportIssued'] = $this->request->getPost(
                'tourist-passport-issued'
            );
            $tourist['passportEndDate'] = $this->request->getPost(
                'tourist-passport-endDate'
            );
            $tourist['birthDate'] = $this->request->getPost(
                'tourist-birthDate'
            );
            $tourist['phone'] = $this->request->getPost('tourist-phone');
            $tourist['email'] = $this->request->getPost('tourist-email');
            $tourist['gender'] = $this->request->getPost('tourist-gender');
            $tourist['nationality'] = $this->request->getPost(
                'tourist-nationality'
            );

            $query = "SELECT * FROM \Backend\Models\Tourists
						WHERE \Backend\Models\Tourists.passportNumber = :passportNumber:
						AND \Backend\Models\Tourists.passportSurname = :passportSurname:
						AND \Backend\Models\Tourists.passportName = :passportName:
						LIMIT 1";

            $touristModel = $this->modelsManager
                ->executeQuery($query, [
                    'passportNumber' => $tourist['passportNumber'],
                    'passportName' => $tourist['passportName'],
                    'passportSurname' => $tourist['passportSurname']
                ])
                ->getFirst();

            if ($touristModel) {
                $response->tourist = $touristModel->toArray();
            } else {
                $touristModel = new Tourists();

                $touristModel->passportNumber = $tourist['passportNumber'];
                $touristModel->passportSurname = $tourist['passportSurname'];
                $touristModel->passportName = $tourist['passportName'];
                $touristModel->passportIssued = $tourist['passportIssued'];
                $touristModel->passportEndDate = $tourist['passportEndDate'];
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
        } else {
            $this->request->redirect($this->backendUrl->get('404'));
        }
    }

    public function editAction($touristId)
    {
        $tourist = Tourists::findFirst($touristId);

        $form = new Form($tourist);
        $form->add(new Text('passportName'));
        $form->add(new Text('passportSurname'));
        $form->add(new Text('passportNumber'));
        $form->add(new Text('passportEndDate'));
        $form->add(new Text('passportIssued'));
        $form->add(new Select('gender', ['m' => 'Мужской', 'f' => 'Женский']));
        $form->add(new Text('phone'));
        $form->add(new Text('email'));
        $form->add(new Text('birthDate'));
        $form->add(new Text('nationality'));

        if ($this->request->isPost()) {
            $form->bind($_POST, $tourist);
            if ($form->isValid()) {
                $tourist->save();
                $this->flashSession->success(
                    'Данные туриста успешно сохранены'
                );
            }
        }

        $this->view->setVar('form', $form);
        $this->view->setVar('tourist', $tourist);
    }

    public function addAction()
    {
        $form = new Form();

        $form->add(new Text('passportName'));
        $form->add(new Text('passportSurname'));
        $form->add(new Text('passportNumber'));
        $form->add(new Text('passportEndDate'));
        $form->add(new Text('passportIssued'));
        $form->add(new Select('gender', ['m' => 'Мужской', 'f' => 'Женский']));
        $form->add(new Text('phone'));
        $form->add(new Text('email'));
        $form->add(new Text('birthDate'));
        $form->add(new Text('nationality'));

        if ($this->request->isPost()) {
            $tourist = new Tourists();

            $tourist->passportName = $this->request->getPost('passportName');
            $tourist->passportSurname = $this->request->getPost(
                'passportSurname'
            );
            $tourist->passportNumber = $this->request->getPost(
                'passportNumber'
            );
            $tourist->passportEndDate = $this->request->getPost(
                'passportEndDate'
            );
            $tourist->passportIssued = $this->request->getPost(
                'passportIssued'
            );
            $tourist->gender = $this->request->getPost('gender');
            $tourist->phone = $this->request->getPost('phone');
            $tourist->email = $this->request->getPost('email');
            $tourist->birthDate = $this->request->getPost('birthDate');
            $tourist->nationality = $this->request->getPost('nationality');

            if ($tourist->save()) {
                $this->flashSession->success('Турист успешно добавлен');
                return $this->response->redirect(
                    $this->backendUrl->get('tourists/edit/' . $tourist->id)
                );
            }

            foreach ($tourist->getMessages() as $message) {
                $this->flashSession->error($message);
            }
        }

        $this->view->setVar('form', $form);
    }
}
