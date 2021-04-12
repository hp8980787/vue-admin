<template>
    <div>
        <loading :active.sync="isLoading"
                 :can-cancel="true"

                 :is-full-page="fullPage"></loading>
        <div v-if="is_load">
            <h4> {{ headerText }} <span class="badge badge-success">{{ todayCounts }}</span></h4>
            <ul class="list-inline">
                <li v-for="url in urls"><a :href="url.url">{{ url.url }} <span class="badge badge-info">{{ url.count }}</span>
                </a>
                    <a :href="'/web/orders/'+url.database_id" >查看详情</a>
                </li>
            </ul>
            <form>
                <div class="form-group">
                    <label>查询条件</label>
                    <select v-model="selectStatus" name="" id="" class="form-control">
                        <option value="1">查询当日订单</option>
                        <option value="2">查询当月订单数</option>
                        <option value="3">查看上月订单数</option>
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
// Import component
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "index",
    props: ['country'],
    components: {
        Loading
    },
    data() {
        return {
            is_load: false,
            selectStatus: 1,
            urls: {},
            todayOrders: {},
            todayCounts: 0,
            isLoading: false,
            fullPage: true,

        }
    },
    mounted() {
        axios.get('/api/admin/web', {
            params: {country: this.country},
        }).then(response => {
            this.todayCounts = response.data.counts;
            this.urls = response.data.data;
            this.is_load = true;

        }).catch(error => {

        })
    }, computed: {
        headerText() {
            switch (parseInt(this.selectStatus)) {
                case 1:
                    return '今日订单新增';
                case 2:
                    return '当月截至目前订单';
                case 3:
                    return '上月订单';
            }
        }
    },
    methods: {
        async selectOrder() {
            this.isLoading = true;
            let {data} = await axios.get('/api/admin/orders', {
                params: {type: this.selectStatus, country: this.country}
            })
            this.isLoading = false;
            this.todayCounts = data.counts;
            this.urls = data.data;

            console.log(data, this.selectStatus);
        }
    }
}
</script>

<style scoped>

</style>
