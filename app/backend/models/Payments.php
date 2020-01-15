<?php

namespace Backend\Models;

use Models\BaseModel;
use Phalcon\Di;
use Phalcon\Mvc\Model;

class Payments extends BaseModel
{
    public $id;
    public $requestId;
    public $sum;
    public $payDate;
    public $status;
    public $approvalCode;
    public $billNumber;
    public $authConfirmed;
    public $totalPaid;
    public $creationDate;

    const PAID = 'paid';
    const AUTHORIZED = 'authorized';
    const NOT_AUTHORIZED = 'not authorized';
    const CANCELED = 'canceled';
    const WAITING = 'waiting';
    const NEW = 'new';

    public function initialize()
    {
        $this->addBehavior(
            new Model\Behavior\Timestampable(array(
                'beforeCreate' => array(
                    'field' => 'creationDate',
                    'format' => 'Y-m-d H:i:s'
                )
            ))
        );

        $this->belongsTo('requestId', Requests::name(), 'id', array(
            'alias' => 'request'
        ));
    }

    public function beforeSave()
    {
        if (
            $this->status === Payments::AUTHORIZED ||
            $this->status === Payments::PAID
        ) {
            //Send Email
            // Utils\Email::send
        }
    }

    public function isSuccess()
    {
        return $this->status === Payments::AUTHORIZED ||
            $this->status === Payments::PAID;
    }

    public function getOrder()
    {
        return Di::getDefault()->get('config')->uniteller->orderPrefix .
            $this->id;
    }

    /**
     * @param null $parameters
     * @return Payments|Model
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param null $parameters
     * @return Payments|Model
     */
    public static function findFirstById($parameters = null)
    {
        return parent::findFirstById($parameters);
    }
}
