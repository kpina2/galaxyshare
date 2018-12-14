<template>
    <tr>
        <td style='min-width:30px;'><div v-if="message.id && message.active == 1 && message.required != 1" class="drag-handle"></div></td>
        
        <td><input v-model="message.message" placeholder="Message" name='message' v-validate="'required'" :class="{ error: errors.has('message') }" :disabled="message.active == 0 || message.required == 1"></td>
        
        <td><input v-model="message.sku" placeholder="SKU" name='sku' v-validate="'required'" :class="{ error: errors.has('sku') }" :disabled="message.active == 0 || message.required == 1"></td>
        
        
        <td v-if="message.id > 0 && message.active == 1 && message.required != 1"><button class="btn btn-primary btn-sm" v-on:click="submit_changes">Save</button></td>
        <td v-if="message.id > 0 && message.active == 1 && message.required != 1"><button type="button" class="btn btn-danger btn-sm" v-on:click="toggle_activate">Deactivate</button></td>
        
        <td v-if="message.id > 0 && message.required == 1" colspan="2" class='text-center'></td>
        
        <td v-if="message.id > 0 && message.active == 0" colspan="2" class='text-center'><button type="button" class="btn btn-primary btn-sm" v-on:click="toggle_activate">Activate</button></td>
        
        <td v-if="message.id === null" class='text-center' colspan="2"><button class="btn btn-success btn-sm" v-on:click="add_new">Save New</button></td>
    </tr>
</template>

<script>
    export default {
        props: {
            message: {
                type: Object
            },
            message_count: {
                type: Number
            }
        },
        data: function () {
            return {
                message_clean: {}
            }
        },
        created: function(){
            this.message_clean = this.message
        },
        mounted() {
        
        },
        methods:{
            submit_changes: function(){
                this.$validator.validate().then(result => {
                    if (!result) {
                        toastr.error("Please verfiy all fields are filled out correctly.");
                    }else{
                        var requestObj = new FormData();
                
                        requestObj.append('form_data', JSON.stringify(this.message));
                        requestObj.append('_method', 'PUT')

                        var self = this;
                        axios.post(AJAX_URI + "messages", requestObj).then(function(response){
                            if(response.data.success){
                                toastr.success(response.data.message);
                                self.password = "";
                            }else{
                                toastr.error(response.data.message);
                            }
                        });
                    }
                });
            },
            add_new: function(){
                this.$validator.validate().then(result => {
                    if (!result) {
                        toastr.error("Please verfiy all fields are filled out correctly.");
                    }else{
                        var requestObj = new FormData();
                        this.message.sort_order = this.message_count;
                        requestObj.append('form_data', JSON.stringify(this.message));
                        
                        var self = this;
                        axios.post(AJAX_URI + "messages", requestObj).then(function(response){
                            if(response.data.success){
                                toastr.success(response.data.message);
                                self.$emit('message_save', response.data.data);
                            }else{
                                toastr.error(response.data.message);
                            }
                        });
                    }
                });
                
            },
            toggle_activate: function(){
                this.message_clean.active = (this.message_clean.active == 1 ? 0 : 1);
                
                var requestObj = new FormData();
                
                if(this.message_clean.active == 0){
                    this.message_clean.sort_order = 1000;
                }
                
                requestObj.append('form_data', JSON.stringify(this.message_clean));
                requestObj.append('_method', 'PUT')
                
                var self = this;
                axios.post(AJAX_URI + "messages", requestObj).then(function(response){
                    if(response.data.success){
                        toastr.success(response.data.message);
                        self.password = "";
                        self.$emit('message_save', response.data.data);
                    }else{
                        toastr.error(response.data.message);
                    }
                });
            },
            display_message_messages: function(){
                this.$emit('display_message_messages', this.message);
            }
        }
    }
</script>
