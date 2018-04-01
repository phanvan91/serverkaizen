<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/18/2017
 * Time: 5:21 PM
 */

namespace App\Exceptions;


use Exception;

class ThieuLinhKienException extends SException
{
    /**
     * @return void
     */
    public function __construct($id=0)
    {
        parent::__construct('duplicate ma', \Config::get('error_code.ma_existed'));
        $this->setData($id);
    }

}