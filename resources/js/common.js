const {
    default: Axios
} = require("axios");
const {
    default: Swal
} = require("sweetalert2");

const {
    ajax
} = require("jquery");

$(document).ready(function () {
    // работа с клиентом
    $('.changeLink').click(function (e) {
        e.preventDefault();
        $(this).parent('.realData').addClass('d-none');
        $(this).parents('td').find('.resetData').addClass('d-flex');
        $('#saveClient').prop('disabled', false);
    });

    $('.resetLink').click(function (e) {
        e.preventDefault();
        $(this).siblings('.dataInp').val($(this).siblings('.dataInp').data('orig'))
        $(this).parent('.resetData').removeClass('d-flex');
        $(this).parents('td').find('.realData').removeClass('d-none');
    });

    $('#saveClient').click(function (e) {
        e.preventDefault();
        $(this).find('.spinner-border').removeClass('d-none');
        $(this).prop('disabled', true);
        //let self=this
        let data = {
            'id': $(this).data('id'),
            'inn': $('[name="inn"]').val(),
            'organization': $('[name="organization"]').val(),
            'fullname': $('[name="fullname"]').val(),
            'email': $('[name="email"]').val(),
            'address': $('[name="address"]').val(),
            'phone': $('[name="phone"]').val(),
        };
        axios.post('/ajax/contacts/update', data)
            .then(function (response) {
                $('#successAlert').removeClass('d-none');
            })
            .catch(function (error) {

            })
            .then(() => {
                console.log('fin')
                $(this).find('.spinner-border').addClass('d-none');
            })
    });

    // банки изменение
    $('[name="city"],[name="tariff"]').change(function () {
        let id = $(this).data('id');
        let city = $('.bank_city_' + id).val();
        let tariff = $('.bank_tariff_' + id).val();
        if (tariff !== '-1' && city !== '-1') {
            $('.button_' + id).prop('disabled', false)
        } else {
            $('.button_' + id).prop('disabled', true)
        }
    });

    // отправка заявки в банк
    $('.send_bank').click(function (e) {
        e.preventDefault();
        $(this).prop('disabled', true);
        let id = $(this).data('id');
        let city = $('.bank_city_' + id).val();
        let tariff = $('.bank_tariff_' + id).val();
        let contact_id = $(this).data('contact_id');
        let comment = $('[name="mes_' + id + '"]').val();
        axios
            .post("/ajax/contact/sendBankContac", {
                bank_id: id,
                city_id: city,
                tariff_id: tariff,
                contact_id: contact_id,
                comment: comment
            })
            .then((response) => {
                if (response.data.suc) {
                    Swal.fire({
                        icon: 'success',
                        text: 'Заявка отправлена!',
                    });
                }
            })
            .catch((err) => {})
            .then(() => {

            });
    });

    // показ в отчетах ошибки
    $('.show_report_erorr').click(function (e) {
        e.preventDefault();
        let href = $(this).attr('href');
        $(href).toggleClass('d-none')
    });

});
