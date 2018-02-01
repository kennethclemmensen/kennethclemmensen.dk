var app = new Vue({
    el: '#search-app',
    data: {
        searchString: '',
        results: []
    },
    watch: {
        searchString: function() {
            this.search(null);
        }
    },
    methods: {
        search: function(event) {
            if(event !== null) event.preventDefault();
            this.$http.get('/wp-json/kcapi/v1/search/pagesbytitle/' + this.searchString).then(function(response) {
                this.results = response.body;
            }, function() {
                this.results = [];
            });
        }
    }
});