<template>
    <div class="row">
        <div class="col-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" v-model="name">
        </div>
        <div class="col-3">
            <label class="form-label">Number of Bedrooms</label>
            <input type="text" class="form-control" v-model="bedrooms">
        </div>
        <div class="col-3">
            <label class="form-label">Price</label>
            <input type="number" class="form-control" v-model="price">
        </div>
        <div class="col-3">
            <label class="form-label">Type</label>
            <select class="custom-select" aria-label="Default select example"  v-model="type_selected">
                <option selected>Select a property type</option>
                <option v-for="option in types" v-bind:value="option.id">
                    {{ option.title }}
                </option>n>
            </select>
        </div>
        <div class="col-3 bottom-row">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="listing_type" id="flexRadioDefault1" value="sale" v-model="listing_type">
                <label class="form-check-label" for="flexRadioDefault1">
                    For sale
                </label>
                </div>
                <div class="form-check">
                <input class="form-check-input" type="radio" name="listing_type" id="flexRadioDefault2" value="rent" v-model="listing_type">
                <label class="form-check-label" for="flexRadioDefault2">
                    For rent
                </label>
            </div>
        </div>
        <div class="col-3 bottom-row">
            <button v-on:click="loadProperties()" class="btn btn-primary">Search</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
     types: []
    },
    data() {
        return {
            name: '',
            bedrooms: '',
            price: 0,
            type_selected: 0,
            listing_type: ''
        };
    },
    methods: {
        getResults() {
            axios.get('/livesearch', { params: { keyword: this.keyword } })
                .then(res => this.Books = res.data)
                .catch(error => {});
        },
        loadProperties() {
            console.log(this.types);
        }
    }
}
</script>