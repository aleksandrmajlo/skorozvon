<template>
    <datepicker :value="value" :name="name" format="yyyy-MM-dd" input-class="form-control" :calendar-button="true" calendar-button-icon="fa fa-calendar"></datepicker>
</template>
<script>
    import Datepicker from 'vuejs-datepicker';
    export default {
        name: "DatepickerWrap",
        props:[
            'value',
            'name'
        ],
        components: {
            Datepicker
        }
    }
</script>
