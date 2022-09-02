<template>
    <div class="container">
        <label>Don't have an event? <router-link :to="{name:'newEvent'}">List Event</router-link></label>
        <div class="row  ">
             
               
                               
                             
                    <form>        
                    <div class="col">            
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Main Event Image</label>
                                    
                                <div id="preview">
                                    <img v-if="url" :src="url" />
                                </div>
                                    <input
                                        ref="fileInput"
                                        type="file"
                                        @change="onFileChange">
                                </div>
                            <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="title">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                                    <QuillEditor theme="snow" />
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="address" placeholder="Address">
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Start Time</label>
                                    <datepicker v-model="start_date" />
                                    <div class="timepicker-ui" ref="tm">
                                        <input v-model="start_time" type="text" class="timepicker-ui-input" />
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">End Time</label>
                                    <datepicker v-model="end_date" />
                                    <div class="timepicker-ui" ref="etm">
                                        <input v-model="end_time" type="text" class="timepicker-ui-input" />
                                    </div>
                                </div>
                       
                    </div>
                    <div class="col">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item active">Options</li>
                            <li class="list-group-item">
                                <label>Set min/max number of people</label>
                                <vue-toggle title="Toggle me" name="people-no"/>
                            </li>
                            <li class="list-group-item">
                                <label>Set RSVP deadline</label>
                                <vue-toggle title="Toggle me" name="rsvp-deadline"/>
                            </li>
                            <li class="list-group-item">
                                <label>Online</label>
                                <vue-toggle title="Toggle me" name="online"/>
                            </li>
                             
                            </ul>
                    </div>  
                    <div class="col">
                        <div class="mb-3">
                            <vue-tags-input
                            v-model="tag"
                            :tags="tags"
                            @tags-changed="newTags => tags = newTags"
                            />
                        </div>
                    <button type="button" class="btn btn-primary">Primary</button>
                    <button type="button" class="btn btn-secondary">Secondary</button> 
                </div>
                </form> 
            </div>
             

        </div>
    
</template>

<script>
import Datepicker from 'vue3-datepicker'
import Auth from '../../Auth';
import { TimepickerUI } from 'timepicker-ui';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import VueToggle from 'vue-toggle-component';
import VueTagsInput from "@sipec/vue3-tags-input";

export default {
    name:"Create Event",
    components: {
        Datepicker,
        QuillEditor,
        VueToggle,
        VueTagsInput
    },
    data(){
        return {
            start_date: new Date(),
            end_date: new Date(),
            start_time: "10:10 PM",
            end_time: "12:10 PM",
            url:null ,
            tag: '',
            tags: [],
        }
    },
    methods:{
        onFileChange(e) {
        const file = e.target.files[0];
        this.url = URL.createObjectURL(file);
        }
        
    },
    mounted: function() {
        const test = new TimepickerUI(this.$refs.tm, { enableSwitchIcon: true });
        test.create();

        const end_time = new TimepickerUI(this.$refs.etm, { enableSwitchIcon: true });
        end_time.create();

        this.$refs.tm.addEventListener("accept", ({ detail }) => {
             this.start_time = `${detail.hour}:${detail.minutes} ${detail.type}`;
              
        });

        this.$refs.etm.addEventListener("accept", ({ detail }) => {
            
             this.end_time = `${detail.hour}:${detail.minutes} ${detail.type}`;
        });
    }
}
</script>
<style>
    
#preview {
  display: flex;
  justify-content: center;
  align-items: center;
}

#preview img {
  max-width: 100%;
  max-height: 500px;
}
</style>