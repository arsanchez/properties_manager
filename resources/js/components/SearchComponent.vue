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
                <option selected value=''>Select a property type</option>
                <option v-for="option in types" v-bind:value="option.id">
                    {{ option.title }}
                </option>n>
            </select>
        </div>
        <div class="col-6 bottom-row">
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
        <div class="col-6 bottom-row">
            <button v-on:click="loadProperties()" class="btn btn-primary">Search</button>
        </div>

        <div class="col-12 bottom-row scrollable">
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Type of listing</th>
                    <th scope="col">Type of property</th>
                    <th scope="col">County</th>
                    <th scope="col">Country</th>
                    <th scope="col">Town</th>
                    <th scope="col">Description</th>
                    <th scope="col">Address</th>
                    <th scope="col">Bedrooms</th>
                    <th scope="col">Bathrooms</th>
                    <th scope="col">Price</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="listing in listings">
                        <th scope="row">{{ listing.id }}</th>
                        <td>{{ listing.type }}</td>
                        <td>{{ listing.property_type }}</td>
                        <td>{{ listing.country }}</td>
                        <th>{{ listing.county }}</th>
                        <td>{{ listing.town }}</td>
                        <td>{{ listing.description }}</td>
                        <td>{{ listing.address }}</td>
                        <td>{{ listing.num_bedrooms }}</td>
                        <td>{{ listing.num_bathrooms }}</td>
                        <td>{{ listing.price | formatNumber}}</td>
                        <td><button type="button" v-on:click="deleteProperty(listing)" class="btn btn-danger btn-sm">Delete property</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
var numeral = require("numeral");
const Swal = require('sweetalert2');

Vue.filter("formatNumber", function (value) {
return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
});


export default {
    props: {
     types: []
    },
    data() {
        return {
            name: '',
            bedrooms: '',
            price: '',
            type_selected: 0,
            listing_type: 'rent',
            listings: [],
            page: 1
        };
    },
    methods: {
        loadProperties() {
            axios.get('/search', { params: { name: this.name, 
                                                  bedrooms: this.bedrooms, 
                                                  price: this.price, 
                                                  type: this.type_selected,
                                                  listing_type: this.listing_type,
                                                  page:this.page } })
                .then(res => this.listings = res.data)
                .catch(error => {});
        },
        deleteProperty(listing) {
            Swal.fire({
                title: 'Are you sure you want to delete this property id ' + listing.id +' ?',
                text: 'You will not be able to recover this imaginary file!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
                }).then((result) => {
                if (result.isConfirmed) {
                    // Deleting the property
                    axios.get('/delete', { params: { id: listing.id}})
                    .then(res =>  {
                            Swal.fire(
                                'Deleted!',
                                'The property has been deleted.',
                                'success'
                            );

                            this.listings.splice(this.listings.indexOf(listing), 1);
                        })
                    .catch(error => {});
                }

            });
        }
    }
}
</script>