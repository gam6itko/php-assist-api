<?php

namespace Gam6itko\Assist;

class SignatureChecker
{
    /**
     * Проверяет подпись данных пришедших с Assist.
     *
     * @see http://www.assist.ru/files/TechNew.pdf
     *
     * @param string $salt - секретное слово
     *
     * @return bool
     */
    public static function checkSignature(array $paymentData, $salt = '')
    {
        $x = implode('', [$paymentData['merchant_id'], $paymentData['ordernumber'], $paymentData['orderamount'], $paymentData['ordercurrency'], $paymentData['orderstate']]);
        $myCheckvalue = mb_strtoupper(md5(mb_strtoupper(md5($salt).md5($x))));

        return $myCheckvalue === $paymentData['checkvalue'];
    }
}
