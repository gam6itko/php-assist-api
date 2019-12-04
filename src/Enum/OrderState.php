<?php

namespace Gam6itko\Assist\Enum;

final class OrderState
{
    /** В процессе Заказ создан */
    const IN_PROCESS = 'In Process';

    /** Операция оплаты по данному заказу успешно завершена по двустадийному механизму */
    const DELAYED = 'Delayed';

    /** Операция оплаты по данному заказу успешно завершена */
    const APPROVED = 'Approved';

    /** Операция оплаты проведена на часть суммы заказа (не используется) */
    const PARTIAL_APPROVED = 'PartialApproved';

    /** Подтверждение оплаты совершено на часть суммы оплаты */
    const PARTIAL_DELAYED = 'PartialDelayed';

    /** Отменен на полную сумму оплаты */
    const CANCELED = 'Canceled';

    /** Отменен на часть суммы оплаты */
    const PARTIAL_CANCELED = 'PartialCanceled';

    /** Отклонен Оплата завершена неуспешно */
    const Declined = 'Declined';

    /** Заказ завершен по тайм-ауту */
    const TIMEOUT = 'Timeout';
}
