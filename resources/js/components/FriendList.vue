<template>
	<div>
          <form action="#" class="form" style="display: flex;margin-bottom: 24px" @submit.prevent="addFriend">
              <input type="text" @keyup="friendSearch" class="input" placeholder="Your Friend Email or Name" v-model="friend_email">           
                        <button class="no-border" style="padding-left: 10px; padding-right: 7px;" type="submit"><i class="fas fa-user-plus"></i></button>
          </form>
          <transition-group name="bounce">
            <article class="media friend-item" :class="{'is-active': user == friend.friend_id}" v-for="friend in friends" @click="openChat(friend.friend_id)" :key="friend.friend_id">
              <figure class="media-left">
                <p class="image is-64x64">
                  <img src="https://bulma.io/images/placeholders/128x128.png" class="is-rounded">
                </p>
              </figure>
              <div class="media-content">
                <div class="content">
                  <p style="margin-top:7px;">
                    <!-- <strong>{{friend.name}}</strong> <br><small>@{{friend.email.split('@').splice(0,1).join('@')}}</small>  -->
                    <strong>{{friend.name}}</strong> <br><small>{{friend.email}}</small>
                    <!-- <small>31m</small> -->
                  </p>
                </div>
              </div>
              <div class="media-right" :class="{'media-right is-online': friend.isOnline, 'media-right is-offline': !friend.isOnline}">
                <i class="far fa-grin-beam"></i>
              </div>
            </article>	
          </transition-group>
          <!-- Notification -->
          <notifications position="bottom right" group="friendOnline" />
          <notifications position="bottom right" group="friendOffline" />
	</div>          
</template>

<script>
export default {

  name: 'FriendList',
  props:['user','messages'],
  data () {
    return {
    	friends: [],
    	isActive: 0,
      friend_email: null,
      friend_filter: null,
      msg: this.messages
    }
  },
  methods: {
    fetchFriends(){
      var $that = this;
      axios.get('/friends').then(response=>{          
          response.data.forEach(friend=>{
              friend.lastMessage = '';
              friend.isOnline = false;
              
              //Join your friends channel
              Echo.join('private_chat_'+this.$auth.user().id+'_'+friend.friend_id)
              .here(mb=>{
                if(mb.length > 1){
                    friend.isOnline = true;
                }
                $that.friends.push(friend);
              }).joining(mb=>{
                  var $index = $that.friends.findIndex(el=>{
                    return el.friend_id == mb.id
                  });
                  $that.friends[$index].isOnline = true;
                  this.$notify({
                    group: 'friendOnline',
                    type: 'success',
                    title: 'Acitivity Notification',
                    text:  $that.friends[$index].name +' is Online',
                  });                  
              }).leaving(mb=>{
                  var $index = $that.friends.findIndex(el=>{
                    return el.friend_id == mb.id
                  });
                  $that.friends[$index].isOnline = false;             
                  this.$notify({
                    group: 'friendOffline',
                    type:'warn',
                    title: 'Acitivity Notification',
                    text: $that.friends[$index].name +' is Offline'
                  });                           
              });
              
              Echo.join('private_chat_'+friend.friend_id+'_'+this.$auth.user().id)
              .listenForWhisper('typing', (e) => {
                  console.log(e.name);
              })
              .listen ('PrivateMessenger', e=>{
                  console.log('presense');
                  console.log(e);
                  var d = new Date();
                  d = d.getTime() + '_' + e.message.user_id;
                  let msg = {
                      user: e.from,
                      message: e.message.message,
                      mid: d
                  }
                  console.log(e.message.user_id);
                  console.log($that.msg);
                  if(e.message.user_id == $that.user){
                    var test = this.messages;
                    
                    test.push(msg);
                    console.log(test);
                    $that.$emit('update:messages',test);
                    setTimeout(()=>{
                      this.scrollToBottom();
                    },500);
                  }
                    //Find Friends in friendlish and update lasst message
                    this.friends.some((friend,index)=>{
                        if(friend.friend_id == e.from.id){
                            this.friends[index].lastMessage = e.message.message;
                        }
                    })
              }).joining(mb=>{
                  console.log('someone is joining');
                  console.log(mb);
              }).here(mb=>{
                console.log('here: ');
                console.log(mb);
              }).leaving(mb=>{
                console.log('some one is leaving');
                console.log(mb);
              });              
          });
          this.friend_filter = this.friends;
      });
    },
    addFriend(){
        if(this.friend_email != null){
          axios.post('/friends',{
            user_mail: this.friend_email
          }).then(response=>{
              let res = response.data;
              if(res.status != 'errs')
              {

              }else{
                  alert(res.msg);
              }
              this.friend_email = '';
          });
        }else{
          alert('Are you forgot something ?');
        }
    },
    openChat: function(f){
    	this.$emit('openchat', f);
    	return true;
    },
    scrollToBottom(){
      var container = document.querySelector("#chatbox");
      container.scrollTop = container.scrollHeight;
    },
    friendSearch: function(){
        this.friends = this.friend_filter;
            this.friends = this.friends.filter(f=>{
                return f.email.indexOf(this.friend_email) >=0 || f.name.indexOf(this.friend_email);
            });          

    }
  },
  mounted(){
  	this.fetchFriends();
  	//console.log(this.friends);
    console.log(this.msg);
  }
}
</script>