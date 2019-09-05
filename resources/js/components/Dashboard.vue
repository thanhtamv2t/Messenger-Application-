<template>
	<div>
   <!-- Friend list size bar -->
    <div class="sidebar">
        <div class="mini_app"></div>
        <div class="tabs">
          <ul>
            <li @click="tab = 0" :class="{'is-active': tab == 0}"><a>Friend List</a></li>
            <li @click="tab = 1" :class="{'is-active': tab == 1}"><a>Friend Request<span class="tag is-danger is-rounded" style="margin-left:7px;">{{requestCount}}</span></a></li>
            <li @click="tab = 2" :class="{'is-active': tab == 2}"><a>Settings</a></li>
          </ul>
        </div>
        <div class="contain" v-show="tab == 0">
            <FriendList :messages.sync="messages" :user="currentChat" @openchat="openChat"></FriendList>                          
        </div>
        <div class="contain" v-show="tab == 1">
            <FriendRequest @request="updateRQcount"></FriendRequest>
        </div>
        <div class="contain" v-show="tab == 2">
          <a @click="logout()">Logout</a>          
        </div>
    </div>
	 <Message :user="currentChat" :messages.sync="messages" v-show="currentChat"></Message>
    
    <div v-if="!currentChat" style="position: absolute; width: calc(100% - 450px); height:100vh;   display: flex;align-items: center;  justify-content: center; font-family: Poppins, sans-serif;">
        <div class="wrap is-center" style="text-align: center;">
          <img src="img/start_chat.svg" alt="">
          <h1 style="text-transform: capitalize; margin-top: 2em;" class="is-size-3">Pick your friends from rightside & start the funny stories</h1>
        </div>
    </div>

    </div>

</template>

<script>
import FriendList from './FriendList.vue';
import Message from './Message.vue';
import FriendRequest from './FriendRequest.vue';

export default {

  name: 'Dashboard',

  components:{
  	FriendList, FriendRequest, Message
  },
  data () {
    return {
    	currentChat: null,
      requestCount: null,
      tab: 0,
      messages: []
    }
  },
  methods:{
  	openChat: function(v){
  		this.currentChat = v;
      //alert(v);
  	},
    updateRQcount: function(v){
      this.requestCount = v;
    },
    logout: function(){
        this.$auth.logout();
        this.$router.push('/Login');
    }
  },
  created(){
    //Init Pusher
    Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer '+this.$auth.token();      
    Echo.connector.pusher.config.auth.params = {
        userid: this.$auth.user().id
    };
  }
}
</script>

<style lang="css" scoped>
</style>