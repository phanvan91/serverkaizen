<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/18/2017
 * Time: 5:28 PM
 */

namespace App\Exceptions;

use Exception;

class SException extends Exception
{
    /**
     * @var string
     */
    protected $data;

    /**
     * @param @string $message
     * @return void
     */
    public function __construct($message,$code)
    {
        parent::__construct($message,$code);
    }

    /**
     * Return the Exception as an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'error_code'     => $this->getCode(),
            'message' => $this->getMessage(),
            'data'  => $this->data
        ];
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}