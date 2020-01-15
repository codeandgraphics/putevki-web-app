<?php

namespace Backend\Controllers;

use Models\Tourvisor;

class PopularsController extends ControllerBase
{
    public function indexAction()
    {
        $countries = Tourvisor\Countries::find([
            'order' => 'popular DESC, name'
        ]);

        $this->view->setVar('countries', $countries);
    }

    public function countryAction($id)
    {
        $country = Tourvisor\Countries::findFirst($id);

        $this->view->setVar('country', $country);
    }

    public function _setPopularAction()
    {
        $this->view->disable();
        if ($this->request->isAjax()) {
            $type = $this->request->getPost('type', 'string');
            $id = $this->request->getPost('id', 'int');
            $checked = $this->request->getPost('checked', 'int');

            if ($type === 'country') {
                $country = Tourvisor\Countries::findFirst($id);
                $country->popular = $checked;
                $country->save();
            }
            if ($type === 'region') {
                $country = Tourvisor\Regions::findFirst($id);
                $country->popular = $checked;
                $country->save();
            }
            echo 1;
        }
        return false;
    }
}
