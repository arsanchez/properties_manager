<template>
    <div class="container">
     
        <div class="row justify-content-center">
              <p v-if="errors.length">
                <b>Please correct the following error(s):</b>
                <ul>
                <li v-for="error in errors">{{ error }}</li>
                </ul>
        </p>
            <form class="form-inline" ref="propertyForm"> 
                <div class="form-group v">
                    <label>County</label>
                    <input type="text" class="form-control" name = "county" placeholder="County" v-model="property.county">
                </div>
                <div class="form-group col-md-3 col-md-3">
                    <label>Country</label>
                    <input type="text" class="form-control" name = "country" placeholder="Country" v-model="property.country">
                </div>
                <div class="form-group col-md-3">
                    <label>Town</label>
                    <input type="text" class="form-control" name = "town" placeholder="Town" v-model="property.town">
                </div>
                <div class="form-group col-md-3">
                    <label>Description</label>
                    <textarea class="form-control" name = "description" rows="3" v-model="property.description">></textarea>
                </div>
                <div class="form-group col-md-3">
                    <label>Displayable Address</label>
                    <input type="text" class="form-control" name = "address" placeholder="" v-model="property.address">
                </div>
                 <div class="form-group col-md-3">
                    <label>Image</label>
                    <input type="file" class="form-control-file" ref="file" v-on:change="uploadFile()">
                </div>
                 <div class="form-group col-md-3">
                    <label>Number of Bedrooms</label>
                    <select class="custom-select" name = "num_bedrooms" v-model="property.num_bedrooms">
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                        <option value='6'>6</option>
                        <option value='7'>7</option>
                        <option value='8'>8</option>
                        <option value='9'>9</option>
                        <option value='10'>10</option>
                        <option value='11'>11</option>
                        <option value='12'>12</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Number of Bathrooms</label>
                    <select class="custom-select" name="num_bathrooms"  v-model="property.num_bathrooms">
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                        <option value='6'>6</option>
                        <option value='7'>7</option>
                        <option value='8'>8</option>
                        <option value='9'>9</option>
                        <option value='10'>10</option>
                        <option value='11'>11</option>
                        <option value='12'>12</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Price</label>
                    <input type="text" class="form-control" name = "price" placeholder="Price" v-model="property.price">
                </div>
                <div class="form-group col-md-3">
                    <label>Property Type</label>
                    <select class="custom-select" name = "property_type_id" v-model="property.property_type_id">
                        <option selected value=''>Select a property type</option>
                        <option v-for="option in types" v-bind:value="option.id">
                            {{ option.title }}
                        </option>n>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="listing_type" value="sale" v-model="property.type">
                        <label class="form-check-label" for="flexRadioDefault1">
                            For sale
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="listing_type"  value="rent" v-model="property.type">
                        <label class="form-check-label" for="flexRadioDefault2">
                            For rent
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <map-location-selector :latitude=property.latitude :longitude=property.longitude
                        @locationUpdated="locationUpdated">
                    </map-location-selector>
                </div>
               
                <a class="btn btn-primary col-md-3 bottom-row" v-on:click="save()" href="#">Save</a>
            </form>
        </div>
    </div>
</template>

<script>
import mapLocationSelector from 'vue-google-maps-location-selector';

    export default {
        props: {
            property: [],
            types: []
        },
        components: {
            mapLocationSelector
        },
        data() {
            return {
                file: '',
                errors: []
            };
        },
        mounted() {
        },
        methods: {
            uploadFile() {
                let formData = new FormData();
                formData.append('file', this.file);
            },
            save() {
                if (this.validate()) {
                    this.file = this.$refs.file.files[0];
                    // Building the form data 
                    let formData = new FormData(this.$refs.propertyForm);
                    formData.append('file', this.file);
                    formData.append('listing_type', this.property.type);

                    //  Posting the data 
                    let id = (this.property.id !== undefined) ? this.property.id : 0;
                    axios.post( '/save/' + id,
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                        ).then(function(){
                            window.location.href = '/';
                        })
                        .catch(function(){
                        });
                    }
            },
            validate: function () {
                this.errors = [];

                if (!this.property.county) {
                    this.errors.push("County is required.");
                }

                if (!this.property.country) {
                    this.errors.push("country is required.");
                }

                if (!this.property.town) {
                    this.errors.push("town is required.");
                }

                if (!this.property.description) {
                    this.errors.push("description is required.");
                }

                if (!this.property.address) {
                    this.errors.push("Displayable address is required.");
                }

                if (this.$refs.file.files[0] === undefined && !this.property.image_thumbnail) {
                    this.errors.push("The image is required.");
                }

                if (!this.property.num_bathrooms) {
                    this.errors.push("Bathrooms is required.");
                }

                if (!this.property.num_bedrooms) {
                    this.errors.push("Bedrooms is required.");
                }

                if (!this.property.price) {
                    this.errors.push("Price is required.");
                }

                if (!this.property.property_type_id) {
                    this.errors.push("Property type is required.");
                }

                if (!this.property.type) {
                    this.errors.push("Listing type is required.");
                }

                if (!this.errors.length) {
                    return true;
                }

                return false;
            },
            locationUpdated: function (latlng) {

            }
        }
    }
</script>
