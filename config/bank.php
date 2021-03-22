<?php

return [

    '2'=>[
        'token'=>env('BANK_OTKRYTIE'),
         'host'=>'https://openpartners.ru',

         'add'=>'/api/v2/request/add',
         'test_add'=>'/api/v2/request/add/test',

         'get_status'=>'/api/v2/request/status?id=',
         'get_status_test'=>'/api/v2/request/status/test?id=',

         'inn_dublicate'=>'/api/v2/request/getduplicates',
         'inn_dublicate_test'=>'/api/v2/request/getduplicates/test',

         'inn_dublicate_get'=>'/api/v2/request/getduplicates?id=',
         'inn_dublicate_get_test'=>'/api/v2/request/getduplicates/test?id=',

         'city'=>'/api/v2/dictionaries/city',
         'tariff'=>'/api/v2/dictionaries/tariff',


         'statusText'=>[

              'not'=>[
                  'text'=>'Проверка отсутствует',
                  'type'=>'',
                  'inReport'=>false
              ],

               'success'=>[
                   'text'=>'ПРОВЕРКА В БАНКЕ ВЫПОЛНЕНА',
                   'type'=>'Проверка на дубль',
                   'status'=>0,
                   'inReport'=>false
               ] ,

               'fail'=>[
                   'text'=>'Заявка есть. Проверка не пройдена(Отклонена. Дубль)',
                   'type'=>'Проверка на дубль',
                   'status'=>1,
                   'inReport'=>false
               ] ,

               'error'=>[
                   'text'=>'Ошибка при проверке',
                   'type'=>'Проверка на дубль',
                   'status'=>1,
                   'inReport'=>false
               ] ,


               'inqueue'=>[
                   'text'=>'Отправляем в банк',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>3,
                   'inReport'=>true,
                   'statusReport'=>1
               ] ,


               'new'=>[
                   'text'=>'Обрабатывается',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>3,
                   'inReport'=>true,
                   'statusReport'=>1
               ] ,

               'exported'=>[
                   'text'=>'В работе',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>4,
                   'inReport'=>true,
                   'statusReport'=>1
               ] ,


               'export_error'=>[
                   'text'=>'Отклонена. Ошибка',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>10,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'export_error_inn_duplicate'=>[
                   'text'=>'Отклонена. Дубль',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>9,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'created'=>[
                   'text'=>'Счет открыт',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>6,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'closed'=>[
                   'text'=>'Счет закрыт',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>7,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,


               'activated'=>[
                   'text'=>'Счет активирован',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>9,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'process_client_meeting_at_bank'=>[
                   'text'=>'Встреча с клиентом в банке',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>11,
                   'inReport'=>true,
                   'statusReport'=>1
               ] ,

               'process_client_meeting_outside_bank'=>[
                   'text'=>'Встреча с клиентом',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>12,
                   'inReport'=>true,
                   'statusReport'=>1
               ] ,

               'process_metting_waiting'=>[
                   'text'=>'Ожидание встречи с клиентом',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>13,
                   'inReport'=>true,
                   'statusReport'=>1
               ] ,


               'process_not_call'=>[
                   'text'=>'Недозвон',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>14,
                   'inReport'=>true,
                   'statusReport'=>1
               ] ,

               'process_recall'=>[
                   'text'=>'Клиент просил перезвонить',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>15,
                   'inReport'=>true,
                   'statusReport'=>1
               ] ,

               'process_active_stops'=>[
                   'text'=>'Наличие действующих приостановок',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>16,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'process_opening'=>[
                   'text'=>'Открытие счета',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>6,
                   'inReport'=>true,
                   'statusReport'=>1
               ] ,

               'process_client_info_waiting'=>[
                   'text'=>'Ожидание инф. от клиента',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>17,
                   'inReport'=>true,
                   'statusReport'=>1
               ] ,

               'created_error_invalid_region'=>[
                   'text'=>'Отказ. В регионе нет отделения банка',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>18,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'created_error_bank_canceled'=>[
                   'text'=>'Отказ банка',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>19,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,


               'created_error_client_canceled'=>[
                   'text'=>'Отказ клиента',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>20,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'created_error_client_canceled_not_request'=>[
                   'text'=>'Отказ клиента. Не оставлял заявку',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>21,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'created_error_not_call'=>[
                   'text'=>'Закрыта. Недозвон',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>22,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'created_error_invalid_phone'=>[
                   'text'=>'Закрыта. Номер не существует',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>23,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'created_error_duplicate'=>[
                   'text'=>'Закрыта. Дубль',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>24,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,

               'created_error_expired'=>[
                   'text'=>'Предконтакт с истекшим сроком',
                   'type'=>'ПОЛУЧЕНИЕ СТАТУСА ЗАЯВКИ',
                   'status'=>25,
                   'inReport'=>true,
                   'statusReport'=>2
               ] ,


           ]

    ]

];
