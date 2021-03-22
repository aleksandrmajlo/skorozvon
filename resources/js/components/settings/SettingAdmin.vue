<template>
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="form-inline">
                <label  class="pr-2 font-weight-bold">Двойная сессия:</label>
                <select class="form-control mb-2 mr-sm-2" v-model="session">
                     <option value="1">Да</option>
                     <option value="-1">Нет</option>
                </select>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="form-inline">
                <label class="pr-2 font-weight-bold">IP белый список:</label>
                <textarea placeholder="через запятую" class="form-control mb-2 mr-sm-2 w-100" v-model="ip"></textarea>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <button @click="save" :disabled="disabled" class="btn btn-primary">
                <span  v-show="disabled" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Сохранить
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SettingAdmin",
        data(){
            return{
                disabled:false,
                ip:'',
                session:''
            }
        },
        created() {
            axios
                .post("/ajax/settings/setting")
                .then((response) => {
                    this.session=response.data.setting1
                    this.ip=response.data.setting2

                })
                .catch((err) => {
                });

        },
        methods:{
            save() {
                this.disabled = true;
                axios
                    .post("/ajax/settings/setting_send", {
                        setting2: this.ip,
                        setting1: this.session,
                    })
                    .then((response) => {
                    })
                    .catch((err) => {
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
            },
        }
    }
</script>

