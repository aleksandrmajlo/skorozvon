<template>
    <table class="table  table-bordered">
        <tr v-for="(contactlog,index) in contactlogs" :key="index">
            <td >
                <p class="text-bold">
                    {{contactlog.date}} {{contactlog.user}}
                </p>
            </td>
            <td >
                {{contactlog.type}}
            </td>
            <td>
                {{contactlog.bank}}
            </td>

            <td>
                {{contactlog.status}}
            </td>

            <td>
                <h5>Старые данные</h5>
                <pre>
                {{contactlog.input}}
                 </pre>
            </td>
            <td>
                <h5>Обновленные</h5>
                <pre>
                   {{contactlog.input_new}}
                 </pre>
            </td>
        </tr>
    </table>
</template>

<script>
    export default {
        name: "ContactEdit",
        props: ['id'],
        data() {
            return {
                contactlogs: [],
            }
        },
        created() {
            axios
                .post("/ajax/contacts/log", {id: this.id})
                .then((response) => {
                    this.contactlogs = response.data.contactlogs
                    console.log(response.data);
                })
                .catch((err) => {
                })
                .then(() => {
                });
        }
    }
</script>
