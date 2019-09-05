<template>

<div>
    <div class="columns">
        
      <div class="column is-one-third">
      <ul>
          <article class="media" v-for="friend in friends" @click="openChat(friend)">
            <figure class="media-left">
              <p class="image is-64x64">
                <img src="https://bulma.io/images/placeholders/128x128.png">
              </p>
            </figure>
            <div class="media-content">
              <div class="content">
                <p>
                  <strong>{{friend.name}}</strong> <small>{{friend.email}}</small>
                  <br>
                  {{friend.lastMessage}}
                </p>
              </div>
            </div>
            <div class="media-right">
              <button class="delete"></button>
            </div>
          </article>          
      </ul>
      <form action="#" class="form" @submit.prevent="addFriend()">
        <div class="field">
            <input type="text" class="input" placeholder="Your friends email" v-model="friend_email" required>
        </div>

        <div class="field">
            <button class="button is-info">Add Friend</button>
        </div>
      </form>
      </div>
        <div class="column">
          
                <ul class="chat" id="chatchat" style="height:400px; overflow-y:scroll;">
                  <transition-group name="list-complete" tag="li">
                    <li class="left clearfix" v-for="(message, index) in messages" :key="index">
                        <div class="chat-body clearfix">
                            <div class="header">
                                <strong class="primary-font">
                                    {{ message.user.name }}
                                </strong>
                            </div>
                            <p>
                                {{ message.message }}
                            </p>
                        </div>
                    
                    </li>
                  </transition-group>
                </ul>
                <div class="input-group">
                    <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">

                    <span class="input-group-btn">
                        <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                            Send
                        </button>
                    </span>
                </div>  


        </div>


    </div>


</div>

</template>

<script>
export default {

  name: 'Home',

  data () {
    return {
    	messages:[],
    	newMessage: '',
      friend_email: '',
      friends:[],
      curOpen: null,
      typing: null
    }
  },
  methods:{
  	sendMessage(){
  		//console.log('userToken');
  		//console.log(this.$auth.user());
  		let msg = {
  			user: this.$auth.user(),
  			message: this.newMessage
  		};
  		this.messages.push(msg);
      msg.curOpen = this.curOpen;
  		axios.post('/message',msg)
  		.then(success=>{
  			//console.log(success);
        this.scrollToBottom();  
  		}).catch(errs=>{
  			//console.log(errs);
  		});
		this.newMessage ='';  
      
  	},
  	fetchMessage(){
  		axios.post('/get_message', {
        curOpen: this.curOpen
      }).then(response=>{
  			response.data.map(mess=>{
	  			this.messages.push({
	  				user : mess.user,
	  				message: mess.message
	  			});  				
  			});
        setTimeout(()=>{
          this.scrollToBottom();
        },500);
        

  		});
  	},
    fetchFriends(){
      axios.get('/friends').then(response=>{
          response.data.map(friend=>{
              friend.lastMessage = '';
              this.friends.push(friend);
              Echo.join('private_chat_'+friend.friend_id+'_'+this.$auth.user().id)
              .listenForWhisper('typing', (e) => {
                  console.log(e.name);
              })
              .listen('PrivateMessenger', e=>{
                  console.log('presense');
                  console.log(e);
                  let msg = {
                      user: e.from,
                      message: e.message.message
                  }
                  if(e.message.user_id == this.curOpen){
                    this.messages.push(msg);
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
              });
          });
      });
    },
    addFriend(){
      axios.post('/friends',{
        user_mail: this.friend_email
      }).then(response=>{
          let res = response.data;
          if(res.status != 'errs')
          {

          }else{
              alert(res.msg);
          }

      });
    },
    openChat($friend){
      //console.log($friend);
      this.messages = [];
      this.curOpen = $friend.friend_id;
      this.fetchMessage();
    },
    scrollToBottom(){
      var container = this.$el.querySelector("#chatchat");
      container.scrollTop = container.scrollHeight;
    }

  },
  created(){
    //Init Pusher
    Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer '+this.$auth.token();      
    //Friends Request;
    Echo.private('friend_request_'+this.$auth.user().id)
      .listen('FriendsRequest', (e)=>{
          console.log(e);   
          if(confirm("Do you want to add: " + e.from_user.name + " as friend ?")){
              alert('friend_added');
              this.friends.push(e.from_user);
          }else
          {
            alert('denied');
          }
      });
  //Global Message;
  Echo.private('chat')
	  .listen('MessageSent', (e) => {
	  	console.log('event Received');
	  	console.log(e);
	    this.messages.push({
	      message: e.message.message,
	      user: e.user
	    });
      this.scrollToBottom();
	  });  	
    //Init app
    //this.fetchMessage();
    this.fetchFriends();    

  }
}
</script>
