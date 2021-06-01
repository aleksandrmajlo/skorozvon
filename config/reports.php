<?php
/*
 *  bank_id
 * статусы при которых поверяем заявку в банке
 */
return [
    '1'=>[
        'inqueue',
        'SENT',
        'UNDEFINED',
        'IN_PROGRESS',
        
    ],
    '2'=>[
        'inqueue',
        'new',
        'exported',
        'process_client_meeting_at_bank',
        'process_client_meeting_outside_bank',
        'process_metting_waiting',
        'process_not_call',
        'process_recall',
        'process_opening',
        'process_client_info_waiting',

    ],
];
