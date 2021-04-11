<template>
    <div>

        <div v-if="is_load">
            <ul >
                <li v-for="url in urls"><span>{{ url.url }}</span></li>
            </ul>
            <form>
                <div class="form-group">
                    <label>Email address</label>
                    <select v-model="selectStatus" name="" id="" class="form-control">
                        <option value="1">查询当日订单</option>
                        <option value="2">查询当月订单数</option>
                        <option value="3">查询一周订单数</option>
                        <option value="4">按范围时间查询订单数</option>
                    </select>
                </div>
                <div v-if="selectStatus==4" class="form-group">
                    <label>从</label>
                    <input type="date" class="form-control">
                    <label>到</label>
                    <input type="date" class="form-control">
                </div>

                <button type="button" v-on:click="selectOrder()" class="btn btn-primary">提交</button>
            </form>
        </div>
        <div v-else>
            <img width="250px" src="load.gif" alt="">
        </div>
    </div>

</template>

<script>
export default {
    name: "index",
    props: ['country'],
    data() {
        return {
            is_load: false,
            selectStatus: 1,
            urls: {},
        }
    },
    mounted() {
        axios.get('/api/admin/web', {
            params: {country:this.country},
        }).then(response => {
            this.urls = response.data;
            this.is_load = true;

        }).catch(error => {

        })
    }, computed: {},
    methods: {
        async selectOrder() {
            let {data} = await axios.get('/api/admin/orders', {
                params: {type: this.selectStatus, country: this.country}
            })

            console.log(data);
        }
    }
}
</script>

<style scoped>

</style>
