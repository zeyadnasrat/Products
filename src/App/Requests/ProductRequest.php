<?php

namespace App\Requests;

class ProductRequest
{
    private $data;
    private $errors = [];
    private static $fields = ['sku', 'name', 'price', 'productType', 'size', 'weight', 'height', 'width', 'length'];

    public function __construct($postData)
    {
        $this->data = $postData;
    }

    public function validate()
    {
        foreach (self::$fields as $field) {
            if (!isset($this->data[$field]) || empty($this->data[$field])) {
                if (in_array($field, ['sku', 'name', 'price', 'productType'])) {
                    $this->addError($field, 'Please submit required data');
                }
            } if (isset($this->data[$field]) && empty($this->data[$field])) {
                if (in_array($field, ['size', 'weight', 'height', 'width', 'length'])) {
                    $this->addError($field, 'Please submit required data');
                }
            }
        }

        if (!empty($this->data['price']) && !is_numeric($this->data['price'])) {
            $this->addError('price', 'Please provide the data of indicated type');
        }

        $numericFields = ['size', 'weight', 'height', 'width', 'length'];
        foreach ($numericFields as $field) {
            if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
                $this->addError($field, 'Please provide the data of indicated type');
            }
        }

        return $this->errors;
    }

    private function addError($key, $message)
    {
        $this->errors[$key] = $message;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
