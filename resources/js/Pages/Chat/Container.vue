<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
</script>

<template>
  <Head title="Dashboard" />

  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <chat-room-selection
            v-if="currentRoom.id"
            :rooms="chatRooms"
            :currentRoom="currentRoom"
            v-on:roomChanged="setRoom($event)"
        />
    </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <message-container-vue :messages="messages"/>
            <input-message-vue :room="currentRoom" v-on:messageSent="getMessages()"/>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
<script>
import MessageContainerVue from "./MessageContainer.vue";
import InputMessageVue from "./InputMessage.vue";
import ChatRoomSelection from './ChatRoomSelection.vue';
export default {
  components: {
    MessageContainerVue,
    InputMessageVue,
    ChatRoomSelection,
  },
  data:function(){
    return{
        chatRooms: [],
        currentRoom:[],
        messages: []
    }
  },
  watch:{
    currentRoom(val, oldVal){
      if(oldVal.id){
        this.disconnect(oldVal); 
      }
      this.connect();
    }
  },  
  methods:{
    connect(){
      if(this.currentRoom.id){
        let vn = this;
        this.getMessages();
        window.Echo.private("chat." + this.currentRoom.id)
        .listen('.message.new', e =>{
          vn.getMessages();
        })
      }
    },  
    disconnect(room){
      window.Echo.leave("chat." + room.id);
    },
    getRooms(){
        axios.get('/chat/rooms')
            .then(response=>{
                this.chatRooms = response.data;
                this.setRoom(response.data[0]);
            })
            .catch(error =>{
                console.error(error);
            })
    },
    setRoom(room){
        this.currentRoom = room;
    },
    getMessages(){
        axios.get('/chat/room/'+ this.currentRoom.id + '/messages')
            .then(response=>{
                this.messages = response.data;
            })
            .catch(error=>{
                console.log(error);
            })
    }
  },
  created(){
    this.getRooms();
  }
};
</script>
