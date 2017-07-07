<template>
    <div class="container">
        <div class="row assets">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Vue Test!</div>
                    <div class="panel-body">
                        <div class="well">
                            <table class="table">
                                <tr>
                                    <th v-text="t1"></th>
                                    <th v-text="message">2</th>
                                    <th>3</th>
                                </tr>
                                <tr>
                                    <td><a href="javascript:;" @click="testcli">test</a></td>
                                    <td><input v-model="message" class="form-control" :style="etc"></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                t1: 1234,
                etc: 'color: #aaa; width: 50%;',
                message: 'Hello Vue.js...!!!',
                apiUrl: 'https://hello.tyoupub.com/lar/public/cors'
            };
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            'testcli': () => {
                alert('ajax test!');
                var vm = this;
                vm.http.get(vm.apiUrl)
                    .then((response) => {
                        //vm.$set('gridData', response.data)
                        console.log(response.data)
                    },(response) => {
                        console.log(response)
                    });
            }
        }
    }
</script>
