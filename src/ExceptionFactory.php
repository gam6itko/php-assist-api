<?php

namespace Gam6itko\Assist;

use Gam6itko\Assist\Exception\OrderNotFoundException;
use Gam6itko\Assist\Exception\WebServiceException;

class ExceptionFactory
{
    protected static $codesPrimaryDescription = [
        '0'  => 'Успех',
        '1'  => 'Ошибка',
        '2'  => 'Внутренняя ошибка',
        '3'  => 'Отсутствие обязательного параметра',
        '4'  => 'Ошибка в формате параметра',
        '5'  => 'Неверное значение параметра',
        '6'  => 'Несоответствующая версия системы',
        '7'  => 'Ошибка аутентификации',
        '8'  => 'Ошибка авторизации',
        '9'  => 'Ошибка шифрования',
        '10' => 'Отсутствие объекта',
        '11' => 'Дубликатный объект',
        '12' => 'Объект заблокирован',
        '14' => 'Запрещенный объект',
        '15' => 'Запрещенная операция',
        '16' => 'Истекло время операции',
        '17' => 'Ошибка лимитов',
        '18' => 'Подозрение на мошенничество',
        '19' => 'Доступ запрещен',
        '20' => 'Ошибка авторизации по 3D-Secure',
        '21' => 'Операция отклонена',
    ];

    protected static $codesSecondaryDescription = [
        '0'   => 'Дополнительной информации нет',
        '1'   => 'Непредвиденная ошибка',
        '2'   => 'Сгенерированный документ слишком большой',
        '3'   => 'Частота запроса интерфейса превышена',
        '4'   => 'Интервал выборки слишком большой',
        '5'   => 'Ошибка шифрования ключом',
        '6'   => 'Ошибка расшифровки ключом',
        '100' => 'Параметр MERCHANT_ID',
        '101' => 'Параметр LOGIN',
        '102' => 'Параметр PASSWORD',
        '103' => 'Параметр FORMAT',
        '104' => 'Параметр DATE',
        '105' => 'Параметр CURRENCY',
        '106' => 'Параметр MEANNUMBER',
        '107' => 'Параметр ORDERNUMBER',
        '108' => 'Параметр AMOUNT',
        '109' => 'Параметр DELAY',
        '110' => 'Параметр COMMENT',
        '111' => 'Параметр MEANTYPE',
        '112' => 'Параметр EXPIREMONTH',
        '113' => 'Параметр EXPIREYEAR',
        '114' => 'Параметр CARDHOLDER',
        '115' => 'Параметр CSC2',
        '116' => 'Параметр CLIENTIP',
        '117' => 'Параметр LASTNAME',
        '118' => 'Параметр FIRSTNAME',
        '119' => 'Параметр MIDDLENAME',
        '120' => 'Параметр EMAIL',
        '121' => 'Параметр ADDRESS',
        '122' => 'Параметр PHONE',
        '123' => 'Параметр CITY',
        '124' => 'Параметр STATE',
        '125' => 'Параметр ZIP',
        '126' => 'Параметр LIMITTYPE',
        '127' => 'Параметр LANGUAGE',
        '128' => 'Параметр COUNTRY',
        '129' => 'Параметры STARTDAY и/или STARTMONTH и/или STARTYEAR',
        '130' => 'Параметры ENDDAY и/или ENDMONTH и/или ENDYEAR',
        '131' => 'Параметр SUCCESS',
        '132' => 'Параметр ZIPFLAG',
        '133' => 'Параметр HEADER',
        '134' => 'Параметр HEADER1',
        '135' => 'Параметр DELIMITER',
        '136' => 'Параметр OPENDELIMITER',
        '137' => 'Параметр CLOSEDELIMITER',
        '138' => 'Параметр ROWDELIMITER',
        '139' => 'Параметр FIELDS',
        '140' => 'Параметр SSL',
        '141' => 'Параметры LOGIN и/или PASSWORD',
        '142' => 'Параметры EXPIREMONTH и/или EXPIREYEAR',
        '143' => 'Параметр BILLNUMBER',
        '144' => 'Параметр PROTECTCODE',
        '145' => 'Параметр OPTYPE',
        '146' => 'Параметр OPSTATE',
        '147' => 'Параметр RPSERIES',
        '148' => 'Параметр RPNUMBER',
        '149' => 'Параметр ASSISTID',
        '150' => 'Параметр PIN',
        '153' => 'Параметр TICKET_NUMBER, PNR',
        '154' => 'Параметр URL',
        '155' => 'Параметр TRANSACT_ID',
        '156' => 'Параметр TID',
        '157' => 'Параметр MID',
        '159' => 'Параметр BIN',
        '161' => 'Параметр BillingNumber',
        '163' => 'Параметр TRANSACTSTATE',
        '164' => 'Параметр ORDERSTATE',
        '165' => 'Параметр TRANSACTTYPE',
        '167' => 'Параметр Currency RATE',
        '170' => 'Параметр ResponseCode',
        '173' => 'Параметр IP-ADDRESS',
        '176' => 'Параметр PNR',
        '177' => 'Параметр PaymentMode',
        '179' => 'Параметр CHEQUE',
        '185' => 'Параметр BILLSENDTYPE',
        '186' => 'Параметр HASHTYPE',
        '187' => 'Параметр BILLNO',
        '188' => 'Параметр BILLNOTEMPLATE',
        '189' => 'Параметр BILL_ID',
        '190' => 'Параметр BILLSTATE',
        '200' => 'Объект Предприятие',
        '201' => 'Объект Заказ', //нет данных за период
        '202' => 'Объект Покупатель',
        '203' => 'Объект Кредитная карта',
        '204' => 'Объект Банк',
        '205' => 'Объект Процессинг',
        '206' => 'Объект Терминал',
        '207' => 'Объект Страна',
        '208' => 'Объект Валюта',
        '209' => 'Объект Курс валюты',
        '210' => 'Объект Комисcия',
        '211' => 'Объект Лимит',
        '212' => 'Параметр TestMode',
        '213' => 'Параметр PaymentType',
        '214' => 'Объект Template',
        '215' => 'Объект SOAP PACKET',
        '216' => 'Объект Операция',
        '217' => 'Объект Тип платежного средства',
        '218' => 'Объект Платежное средство',
        '220' => 'Объект транзакция',
        '221' => 'Объект Пользователь',
        '225' => 'Объект Юридическое лицо',
        '226' => 'Объект Компания',
        '228' => 'Объект Счет',
        '300' => 'Отмена авторизации',
        '301' => 'Возврат средств (refund)',
        '302' => 'Финансовое подтверждение (deposit)',
        '305' => 'Отмена финансовой транзакции',
        '306' => 'Операция оплаты',
        '307' => 'Операция подтверждения',
        '308' => 'Операция отмены',
        '309' => 'Операция аннулирования счета',
        '320' => 'Рекуррентный платеж',
        '350' => 'Веб-сервис',
        '400' => 'Ошибка Directory Server',
        '402' => 'Ожидание авторизации по 3D-Secure',
        '403' => 'Авторизация запрещена DS',
    ];

    protected static $help = [
        '10' => [
            '201' => 'За данный период не было платежей',
        ],
        '14' => [
            '200' => 'Не верный merchant_id либо пользователь не имеет к нему доступ. Проверьте настройки пользователей.',
        ],
    ];

    /**
     * @var array Список уникальных испключений для ошибок
     */
    protected static $exceptionMap = [
        10201 => OrderNotFoundException::class,
    ];

    /**
     * На основе переданных данных генерирует исключение, это может быть как базовый так и уникальный класс
     *
     * @throws \ReflectionException
     */
    public static function getInstance(int $codePrimary, int $codeSecondary): WebServiceException
    {
        $codePrimary = intval($codePrimary);
        $codeSecondary = intval($codeSecondary);

        $m1 = array_key_exists($codePrimary, self::$codesPrimaryDescription) ? self::$codesPrimaryDescription[$codePrimary] : "Неизвестная ошибка №$codePrimary";
        $m2 = array_key_exists($codeSecondary, self::$codesSecondaryDescription) ? self::$codesSecondaryDescription[$codeSecondary] : "Неизвестная ошибка №$codeSecondary";

        $uniqueCode = $codePrimary * 1000 + $codeSecondary;

        $message = "$m1 ($m2)";
        if (isset(self::$help[$codePrimary][$codeSecondary])) {
            $message = self::$help[$codePrimary][$codeSecondary];
        }

        if (isset(self::$exceptionMap[$uniqueCode])) {
            $refClass = new \ReflectionClass(self::$exceptionMap[$uniqueCode]);

            return $refClass->newInstance($message, $uniqueCode);
        }

        return new WebServiceException($message, $uniqueCode);
    }
}
