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
        search: function(event = null) {
            if(event !== null) event.preventDefault();
            this.$http.get('/wp-json/kcapi/v1/pages/' + this.searchString + '?per_page=30').then(response => {
                this.results = response.body;
            }, () => {
                this.results = [];
            });
        }
    }
});