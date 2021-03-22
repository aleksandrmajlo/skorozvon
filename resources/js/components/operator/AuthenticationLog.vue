<template>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Дата / Время</th>
                <th scope="col">IP</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="item in items">
                    <td>{{item.date}}</td>
                    <td>{{item.ip}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        name: "AuthenticationLog",
        data() {
            return {
                items: [],
                // offset: 0,
                // limit: 2,
                // LoadBlock: true,
                // loader: true
            };
        },
        props: ["id"],
        created() {
            axios
                .post("/ajax/operators/authenticationlogs", {
                    id:this.id,
                    offset: this.offset
                })
                .then((response) => {
                    this.items=response.data;
                })
                .catch((err) => {
                });
        },
    };
</script>

