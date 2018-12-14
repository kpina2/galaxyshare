<template>
    <div class='row'>
        <div class='col-md-6'>
            <h4>Default Messages</h4>
            <table>
                <tbody>
                    <message-form v-for="message in default_messages_sort" v-bind:message="message" :key="message.id"></message-form>
                </tbody>
            </table>
        </div>
        <div class='col-md-6'>
            <h4>Active Messages</h4>
            <table>
                <tbody>
                    <draggable :options="{handle:'.drag-handle'}" v-on:end="update_sort">
                        <message-form v-for="message in active_messages" v-bind:message="message" :key="message.id"></message-form>
                    </draggable>
                </tbody>
            </table>
            <table>
                <tbody>
                    <message-form v-bind:message="new_message" v-bind:message_count="message_count" v-on:message_save="message_save"></message-form>
                </tbody>
            </table>
            <hr>
            <h4>Inactive</h4>
            <table>
                <tbody>
                    <message-form v-for="message in inactive_messages" v-bind:message="message" :key="message.id"></message-form>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import MessageFormComponent from "./MessageFormComponent.vue";
    import draggable from 'vuedraggable';
    
    export default {
        components: {
            "draggable": draggable,
            "message-form": MessageFormComponent
        },
        props: {
            messages: {
                type: Array
            },
            default_messages: {
                type: Array
            }
        },
        data: function () {
            return {
                display_messages: [],
                new_message: {
                    id: null
                }
            }
        },
        created: function(){
            this.display_messages = this.messages;
            this.display_messages.sort((a, b) => a.sort_order - b.sort_order );
        },
        mounted() {
        
        },
        computed: {
            message_count: function(){
                return this.display_messages.length
            },
            default_messages_sort: function(){
                var messages = this.default_messages.filter(function (message) {
                    return message.required == 1;
                });
//                return messages;
                return messages.sort(function(a, b) {
                    return a.sku - b.sku
                });
            },
            active_messages: function(){
                var messages = this.display_messages.filter(function (message) {
                    if(message.required != 1){
                        return message.active == 1;
                    }
                });
//                return messages;
                return messages.sort(function(a, b) {
                    return a.sort_order - b.sort_order
                });
            },
            inactive_messages: function(){
                 var messages =  this.display_messages.filter(function (message) {
                    return message.active == 0;
                });
//                return messages;
                return messages.sort(function(a, b) {
                    return a.sort_order - b.sort_order
                });
            }
        },
        watch: {
            
        },
        methods:{
            update_sort: function(event){
                var recall_name = this.display_messages[event.oldIndex].message;
                this.display_messages[event.oldIndex].sort_order = event.newIndex;
                var count = 0;
                for(var message in this.display_messages){
                    if(this.display_messages[message].required == 1){console.log("required"); continue;}
                   
                    if(event.oldIndex > event.newIndex){
                        if(this.display_messages[message].sort_order >= event.newIndex && this.display_messages[message].sort_order <= event.oldIndex ){
                            count ++;
                            if(recall_name != this.display_messages[message].message){
                                this.display_messages[message].sort_order += 1;
                            }
                        }
                    }else if(event.oldIndex < event.newIndex){
                        if(this.display_messages[message].sort_order <= event.newIndex && this.display_messages[message].sort_order >= event.oldIndex ){
                            if(recall_name != this.display_messages[message].message){
                                this.display_messages[message].sort_order -= 1;
                            }
                        }
                    }
                    if(this.display_messages[message].active == 0){
                        this.display_messages[message].sort_order = 1000;
                    }
                }
                
                // now that all the messageies have their new sort_order re-sort the entire list
                this.display_messages.sort((a, b) => a.sort_order - b.sort_order );
                
                // finally save to the database
                var requestObj = new FormData();
                requestObj.append('form_data', JSON.stringify(this.active_messages));

                var self = this;
                axios.post(AJAX_URI + "messages/batch_sort_update", requestObj).then(function(response){
                    if(response.data.success){
                        toastr.success(response.data.message);
                    }else{
                        toastr.error(response.data.message);
                    }
                });
            },
            message_save: function(new_message){
                this.display_messages.push(new_message);
                this.display_message_messages(new_message);
                this.new_message = {id:null, active: 1};
            },
            display_message_messages: function(selected_message){
                this.current_message = selected_message;
            }
        }
     }
</script>