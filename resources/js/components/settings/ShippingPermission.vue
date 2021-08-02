<template>
    <div class="row">
        <div class="col-md-12 py-5">
            <div class="loaderWrap" v-if="loader">
                <vue-loaders-ball-beat
                    color="#3490dc"
                    scale="1"
                ></vue-loaders-ball-beat>
            </div>
            <h2 class="mb-3">РАЗРЕШЕНИЯ НА ОТПРАВКУ В БАНКИ:</h2>


            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                       <tr>
                           <th>Оператор/Банки</th>
                           <td v-for="bank in banks" :key="bank.id+'td'">
                               {{ bank.name }}
                           </td>
                       </tr>
                    </thead>

                    <tbody>
                         <tr v-for="operator in operators" :key="operator.id">
                             <th>
                                 {{ operator.fio }}
                             </th>
                             <td v-for="bank in banks" :key="bank.id">
                                 <div class="form-check">
                                     <input
                                         type="checkbox"
                                         class="form-check-input"
                                         v-model="check"
                                         :value="bank.id + '_' + operator.id"
                                         :id="'check' + bank.id + '_' + operator.id"
                                     />

                                 </div>
                             </td>
                         </tr>
                    </tbody>
                </table>
            </div>
            <button @click.prevent="save" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ShippingPermission",
        data() {
            return {
                loader: false,
                operators: [],
                banks: [],
                check: [],
                res_bank_users: null,
            };
        },
        created() {
            this.loader = true;
            axios
                .post("/ajax/settings/shippingpermission")
                .then((response) => {
                    this.operators = response.data.operators;
                    this.banks = response.data.banks;
                    this.res_bank_users = response.data.res_bank_users;
                    this.checked();
                    setTimeout(() => {
                        this.loader = false;
                    }, 100);
                })
                .catch((err) => {
                    this.loader = false;
                });
        },
        methods: {
            checked() {
                let self=this;
                _.forEach(this.res_bank_users, function(banks, user_id) {
                    _.forEach(banks,(bank_id)=>{
                        self.check.push(bank_id+ '_' +user_id)
                    })
                });
            },
            save() {
                this.loader = true;
                axios
                    .post("/ajax/settings/shippingpermission_send", {
                        check: this.check,
                    })
                    .then((response) => {
                    })
                    .catch((err) => {
                    })
                    .finally(() => {
                        this.loader = false;
                    });
            },
            inArray(needle, haystack) {
                var length = haystack.length;
                for (var i = 0; i < length; i++) {
                    if (haystack[i] == needle) return true;
                }
                return false;
            },
        },
    };
</script>

