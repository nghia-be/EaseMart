<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NumberPhoneRule implements Rule
{
    private $limit;

    public function __construct()
    {
        $this->limit = 1000000;
    }

    public function passes($attribute, $value)
    {
        if (is_array($value)) {
            $arrPhone = $value;
        } else {
            $arrPhone = explode(',',$value);
        }

        $pattern = '/^(03|05|06|07|08|09)[0-9, ]*$/';
        if ((!is_array($arrPhone) && !is_object($arrPhone)) || count($arrPhone) > $this->limit) {
            return false;
        }

        foreach ($arrPhone as $arPhone) {
            $phone = trim($arPhone);
            if ((strlen($phone)!==10) || !preg_match($pattern, $phone)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Tồn tại phần tử sai định dạng số điện thoại Việt Nam hoặc import quá '. $this->limit .' số';
    }
}
