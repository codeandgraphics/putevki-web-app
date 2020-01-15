<?php

namespace Backend\Controllers;

use Models\Countries;
use Models\Regions;
use Models\Tourvisor;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;

class CountriesController extends ControllerBase
{
    public function indexAction()
    {
        $builder = $this->modelsManager
            ->createBuilder()
            ->columns(['country.*', 'tourvisor.*'])
            ->addFrom(Tourvisor\Countries::name(), 'tourvisor')
            ->leftJoin(
                Countries::name(),
                'country.tourvisorId = tourvisor.id',
                'country'
            )
            ->orderBy('tourvisor.name');

        $countries = $builder->getQuery()->execute();

        $this->view->setVar('countries', $countries);
    }

    public function countryAction()
    {
        $id = $this->dispatcher->getParam(0, 'int');
        $country = Countries::findFirstByTourvisorId($id);

        $form = new Form($country);

        $form->add(new Text('uri'));
        $form->add(new Text('title'));
        $form->add(new File('preview'));
        $form->add(new TextArea('excerpt'));
        $form->add(new TextArea('about'));
        $form->add(new Text('metaKeywords'));
        $form->add(new TextArea('metaDescription'));
        $form->add(new Select('active', [0 => 'Выкл', 1 => 'Вкл']));

        if ($this->request->isPost()) {
            $form->bind($_POST, $country);

            if ($this->request->hasFiles()) {
                $file = $this->request->getUploadedFiles()[0];

                if ($file->getSize() > 0) {
                    $fileName =
                        $country->tourvisorId . '.' . $file->getExtension();
                    $path =
                        $this->config->images->path . 'countries/' . $fileName;
                    $file->moveTo($path);
                    $country->preview = $fileName;
                }
            }

            if ($form->isValid()) {
                $country->save();
                $this->flashSession->success('Страна успешно сохранена');
            }
        }

        $builder = $this->modelsManager
            ->createBuilder()
            ->columns(['region.*', 'tourvisor.*'])
            ->addFrom(Tourvisor\Regions::name(), 'tourvisor')
            ->leftJoin(
                Regions::name(),
                'region.tourvisorId = tourvisor.id',
                'region'
            )
            ->where('tourvisor.countryId = :id:', ['id' => $id])
            ->orderBy('tourvisor.name');

        $regions = $builder->getQuery()->execute();

        $this->view->setVar('regions', $regions);
        $this->view->setVar('country', $country);
        $this->view->setVar('form', $form);
    }

    public function regionAction()
    {
        $id = $this->dispatcher->getParam(0, 'int');
        $region = Regions::findFirstByTourvisorId($id);

        $form = new Form($region);

        $form->add(new Text('uri'));
        $form->add(new Text('title'));
        $form->add(new File('preview'));
        $form->add(new TextArea('about'));
        $form->add(new Text('metaKeywords'));
        $form->add(new TextArea('metaDescription'));

        if ($this->request->isPost()) {
            $form->bind($_POST, $region);

            if ($this->request->hasFiles()) {
                $file = $this->request->getUploadedFiles()[0];

                if ($file->getSize() > 0) {
                    $fileName =
                        $region->tourvisorId . '.' . $file->getExtension();
                    $path =
                        $this->config->images->path . 'regions/' . $fileName;
                    $file->moveTo($path);
                    $region->preview = $fileName;
                }
            }

            if ($form->isValid()) {
                $region->save();
                $this->flashSession->success('Регион успешно сохранен');
            }
        }

        $this->view->setVar('region', $region);
        $this->view->setVar('form', $form);
    }

    public function _setPopularAction()
    {
        $this->view->disable();
        if ($this->request->isAjax()) {
            $type = $this->request->getPost('type', 'string');
            $id = $this->request->getPost('id', 'int');
            $checked = $this->request->getPost('checked', 'int');

            if ($type === 'country') {
                $country = Countries::findFirstByTourvisorId($id);
                $country->popular = $checked;
                $country->save();
            }
            if ($type === 'region') {
                $region = Regions::findFirstByTourvisorId($id);
                $region->popular = $checked;
                $region->save();
            }
            echo 1;
        }
        return false;
    }

    public function _setActiveAction()
    {
        $this->view->disable();
        if ($this->request->isAjax()) {
            $type = $this->request->getPost('type', 'string');
            $id = $this->request->getPost('id', 'int');
            $checked = $this->request->getPost('checked', 'int');

            if ($type === 'country') {
                $country = Countries::findFirstByTourvisorId($id);
                $country->active = $checked;
                $country->save();
            }
            if ($type === 'region') {
                $region = Regions::findFirstByTourvisorId($id);
                $region->active = $checked;
                $region->save();
            }
            echo 1;
        }
        return false;
    }

    public function _setVisaAction()
    {
        $this->view->disable();
        if ($this->request->isAjax()) {
            $id = $this->request->getPost('id', 'int');
            $visa = $this->request->getPost('visa', 'int');

            $country = Countries::findFirstByTourvisorId($id);
            $country->visa = $visa;
            $country->save();
            echo 1;
        }
        return false;
    }

    public function _setHasInfoAction()
    {
        $this->view->disable();
        if ($this->request->isAjax()) {
            $id = $this->request->getPost('id', 'int');
            $hasInfo = $this->request->getPost('hasInfo', 'int');

            $region = Regions::findFirstByTourvisorId($id);
            $region->hasInfo = $hasInfo;
            $region->save();
            echo 1;
        }
        return false;
    }
}
