# Assist Api php client

Незаконченная библиотека работы с API системой обработки платежей [Assist](https://www.assist.ru/).
Дописывается по мере надобности.

## Пример

### получение заказов
```php
$svc = new WebService($merchantId, $login, $pass);
$generator = $svc->resultByDate($dateFrom, $dateTo, null, OperationState::SUCCESED);
foreach ($generator as $paymentData) {
    // do something 
}
```
