const app = new Vue({
    el: '#search-app',
    data: {
        searchString: '',
        results: []
    },
    watch: {
        searchString: function() {
            this.search();
        }
    },
    methods: {
        search: function() {
            if(this.searchString === '') {
                this.results = [];
                return;
            }
            this.$http.get('/wp-json/kcapi/v1/pages/' + this.searchString).then(response => {
                this.results = response.body;
            }, () => {
                this.results = [];
            });
        }
    }
});