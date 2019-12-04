<?php

namespace Gam6itko\Assist;

use Gam6itko\Assist\Exception\ConnectionException;
use Gam6itko\Assist\Exception\WebServiceException;
use Gam6itko\Assist\Enum\Format;
use Gam6itko\Assist\Enum\Language;
use Gam6itko\Assist\Enum\ZipFlag;

/**
 * @see http://www.assist.ru/files/TechNew.pdf
 */
class WebService
{
    private const CSV_DELIMITER = ';';

    protected static $host = 'https://test.paysecure.ru';

    /** @var int */
    protected $merchantId;

    /** @var string */
    protected $login;

    /** @var string */
    protected $password;

    public function __construct(int $merchantId, string $login, string $password)
    {
        $this->merchantId = $merchantId;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Generator.
     *
     * @param \DateTime|null $startDate      По-умолчанию. Текущее время минус 3 часа
     * @param \DateTime|null $endDate        По-умолчанию. Текущее время
     * @param null           $meanType
     * @param null           $operationState
     * @param null           $operationType
     *
     * @return \Generator
     *
     * @throws ConnectionException
     * @throws WebServiceException
     */
    public function resultByDate(\DateTime $startDate = null, \DateTime $endDate = null, $meanType = null, $operationState = null, $operationType = null, int $zipFlag = ZipFlag::BROWSE)
    {
        $requestArr = $this->getCredentialsArr();

        //<editor-fold desc="date period magick">
        if ($startDate) {
            $startDate->setTimezone(new \DateTimeZone('UTC'));

            /** @var \DateTime $endDateMax Максимальная дата периода для этого метода */
            $endDateMax = clone $startDate;
            $endDateMax->add(new \DateInterval('P1D'));

            if ($endDate) {
                $endDate->setTimezone(new \DateTimeZone('UTC'));
                if ($endDate > $endDateMax) {
                    throw new \LogicException('Period must be 1 day max');
                }
            } else {
                $endDate = $endDateMax;
            }

            unset($endDateMax);

            $requestArr = array_merge(
                $requestArr,
                $this->getDateArr($startDate, 'Start'),
                $this->getDateArr($endDate, 'End')
            );
        }
        //</editor-fold>

        $requestArr['Format'] = Format::CSV;
        $requestArr['Language'] = Language::EN;

        if (null !== $meanType) {
            $requestArr['MeanType_ID'] = $meanType;
        }
        if (null !== $operationState) {
            $requestArr['Operationstate'] = $operationState;
        }
        if (null !== $operationType) {
            $requestArr['Operationtype'] = $operationType;
        }
        $requestArr['ZipFlag'] = $zipFlag;

//        $requestArr['TestMode'] = 1; // not in protocol

        $url = self::$host.'/resultbydate/resultbydate.cfm';
        $response = $this->sendRequest($url, $requestArr);
        if (empty($response)) {
            throw new ConnectionException("Empty response from `$url`");
        }
        $responseArr = explode(PHP_EOL, $response);
        $header = array_splice($responseArr, 0, 1);
        $header = str_getcsv(trim($header[0], self::CSV_DELIMITER), self::CSV_DELIMITER);

        foreach ($responseArr as $v) {
            if (empty($v)) {
                continue;
            }
            $values = str_getcsv(trim($v, self::CSV_DELIMITER), self::CSV_DELIMITER);
            $result = array_combine($header, $values);
            $result['merchant_id'] = $this->merchantId;
            yield $result;
        }
    }

    protected function getCredentialsArr()
    {
        return [
            'Merchant_ID' => $this->merchantId,
            'Login'       => $this->login,
            'Password'    => $this->password,
        ];
    }

    protected function getDateArr(\DateTime $dt, string $prefix): array
    {
        return array_map('intval', [
            "{$prefix}Year"  => $dt->format('Y'),
            "{$prefix}Month" => $dt->format('m'),
            "{$prefix}Day"   => $dt->format('d'),
            "{$prefix}Hour"  => $dt->format('H'),
            "{$prefix}Min"   => $dt->format('i'),
        ]);
    }

    protected function sendRequest(string $url, array $postData = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);

        $result = curl_exec($ch);

        if (false === $result) {
            $code = curl_errno($ch);
            $message = curl_error($ch);
            curl_close($ch);

            throw new ConnectionException($message, $code);
        }

        if (preg_match('/error;(\d+);(\d+);/', $result, $matches)) {
            $exc = ExceptionFactory::getInstance((int)$matches[1], (int)$matches[2]);
            throw $exc;
        }

        curl_close($ch);

        return $result;
    }
}
