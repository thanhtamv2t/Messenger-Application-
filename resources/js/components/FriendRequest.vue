<template>
	<div>
          <article class="media friend-item" v-for="friend in friends" @click="openChat(friend.friend_id)">
            <figure class="media-left">
              <p class="image is-64x64">
                <img src="https://bulma.io/images/placeholders/128x128.png" class="is-rounded">
              </p>
            </figure>
            <div class="media-content">
              <div class="content">
                <p style="margin-top:7px;">
                  <strong>{{friend.name}}</strong> <br><small>{{friend.email}}</small>
                </p>
              </div>
            </div>
            <div class="media-right" style="padding-top:12px;">
              <button @click="friend_response(true,friend.user_id)" class="no-border is-primary" style="padding-left: 10px;padding-right: 10px;"><i class="fas fa-check"></i></button>
              <button @click="friend_response(false,friend.user_id)" class="no-border is-danger"><i class="fas fa-times"></i></button>
            </div>
          </article>	
          <notifications position="bottom right" group="friend_request" />
	</div>          
</template>

<script>
export default {

  name: 'FriendList',
  props: ['requestedList'],
  data () {
    return {
    	friends: [],
    	isActive: 0,
      count: 0
    }
  },
  methods: {
    fetchRequest(){
      axios.get('/friends_request').then(response=>{
          this.friends = response.data.requestList;
          this.count = response.data.count;
          this.$emit('request',response.data.count);
      });
    },
    openChat: function(f){
    	this.$emit('openchat', f);
    	return true;
    },
    friend_response: function(action,id){
      var $this = this;
      axios.post('/friend_request_repsonse',{
        'response': action,
        'user_id': id
      }).then(response=>{
          if(response.data.status == 'sucess' && action){
            $this.$emit('friendresponse');
          }
      });
    }
  },
  created(){
  	this.fetchRequest();
  	//console.log(this.friends);

    //Listen to Friend Request
    //Friends Request;
    Echo.private('friend_request_'+this.$auth.user().id)
      .listen('FriendsRequest', (e)=>{
          this.friends.push(e.from_user);
          this.$emit('request',Number(this.count) + 1);
                  this.$notify({
                    group: 'friend_request',
                    type: 'success',
                    title: 'Acitivity Notification',
                    text:  e.from_user.name +' want to be your friends'
                  });           
      });    

  }
}
</script>