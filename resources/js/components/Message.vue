<template>
  <div class="main">
      <div class="messages" id="chatbox"> 
        <transition-group name="fade" tag="ul">
          <!-- <ul> -->
            <li class="message" v-for="mess in message" :class="{my: $auth.user().id == mess.user.id, f: $auth.user().id != mess.user.id}" :key="mess.mid">
              <p class="image is-64x64">
                <img src="https://bulma.io/images/placeholders/128x128.png" class="is-rounded" :title="$auth.user().name">
              </p>
              <div class="msg"><p v-html="mess.message"></p></div>
              <div class="timer"><!-- 26/11/2018 --></div>
            </li>            
          <!-- </ul> -->
          </transition-group>
      </div>
      <form action="#" class="form" @submit.prevent="sendMessage()">
          <input ref="focusme" class="input" type="text" v-model="msg" placeholder="Type your message here">
          <label class="no-border" style="overflow: hidden; cursor: pointer; height: 35px; padding: 7px;margin-left: 7px;"><i class="fas fa-paperclip"></i>
                <input @change="fileHandle" ref="send_file" type="file" name="send_file" id="send_file" accept="image/*" style="position: absolute; left:-1000px;">
          </label>
          <span type="none" @click="openEmoji" style="cursor: pointer; height: 35px; padding: 7px;margin-left: 7px; " class="no-border"><i class="fas fa-smile-wink"></i></span>
      </form>
      <ul v-if="fileInp">
          <li style="padding-left: 36px; line-height: 30px;"><img :src="fileInp.icon" style="width:12px; height:12px;" alt=""> {{fileInp.name}} <i @click="removeFile" class="fas fa-times"></i></a></li>

      </ul>
      <picker v-show="emojiOpen" @select="addEmotion" set="emojione" title="Hola :)"></picker>  
	</div>
</template>

<script>
import { Picker } from 'emoji-mart-vue';  
import { Emoji } from 'emoji-mart-vue';
export default {

  name: 'Message',
  components:{
   Picker, Emoji
  },
  props: ['user','messages'],
  data () {
    return {
      msg: '',
      message: this.messages,
      emojiOpen: false,
      fileInp: null,
      f: null
    }
  },
  computed:{
  	isMyMessage: function(userId){
  		if(userId != this.$auth.user().id){
  			return 'f';
  		}
  		return 'my';
  	}
  }
  ,
  methods:{
  	fetchMessage: function(){
      var app = this;
  		axios.post('/get_message', {
        curOpen: this.user
      }).then(response=>{
        console.log(response);
        let data = response.data.reverse();
  			data.forEach(mess=>{
          mess.message = this.replaceEmoji(mess.message);
	  			this.message.push({
	  				user : mess.user,
	  				message: mess.message,
            mid: mess.id
	  			});  				
          this.$emit('update:messages', this.message);
  			});
        setTimeout(()=>{
          app.scrollToBottom();
          console.log(this.message);
        },500);
        

  		});
  	},
    sendMessage: function(){
        this.emojiOpen = false;
        var app = this;
        var d = new Date();
        d = d.getTime()+ '_' + this.$auth.user();
        if(this.msg != '' || this.fileInp != ''){
          let newMsg = {
            user: this.$auth.user(),
            message: this.replaceEmoji(this.msg),
            mid: d
          }
          var test = newMsg;
          //test.message = this.replaceEmoji(test.message);
          
          newMsg.curOpen = this.user;
          this.message.push(newMsg);
          ///////Handling File 
          let formData = new FormData();
          if(this.fileInp != '')
          formData.append('file',this.f);

          formData.append('user', this.$auth.user());
          formData.append('message', this.replaceEmoji(this.msg));
          formData.append('mid',d);
          formData.append('curOpen',this.user);


          axios.post('/message',formData,{
            headers: {
              'Content-Type':'multipart/form-data'
            }
          })
          .then(success=>{
            
            console.log(success);

            if(this.fileInp){
                  this.fileInp = null;
                  this.f = null;
                  // var t2 = test;
                  // t2.mid = success.data.test.id;
                  // t2.message = success.data.test.message;
                  // app.message.push(t2);
                  this.message.push({
                      user: app.$auth.user(),
                      message: success.data.test.message,
                      mid: success.data.test.id
                  });
            }

            //console.log(success);
            setTimeout(function(){
              app.scrollToBottom();  
            },300);
            
          }).catch(errs=>{
                  this.fileInp = null;
                  this.f = null;
            //console.log(errs);
          });          
          this.msg = '';
          var that = this;          
        }
    },
    scrollToBottom(){
      var container = this.$el.querySelector("#chatbox");
      container.scrollTop = container.scrollHeight;
    },
    addEmotion: function($emoji){
        
        console.log($emoji);
        this.msg+=' '+ $emoji.colons + ' ';
        this.$refs.focusme.focus();
        //this.$refs.focusme.$el.focus();

    },
    openEmoji: function(){
        this.emojiOpen = !this.emojiOpen;
    },
    replaceEmoji: function($msg){
      return $msg.replace(/:[\w\-]+:/g,function(x){
          x = x.split(':').join('');
          return '<i class="em em-'+x+'"></i>';
      });
    },
    fileHandle: function(e){


        //Img type:
        var $img = ['png','jpeg','jpg','gif','svg'];


        if(e.target.value){
            //Extract file name
            var fileName = e.target.value.split('\\');
            fileName = fileName[fileName.length-1];
            var fileType = fileName.split('.');
            fileType = fileType[fileType.length-1];
            var icon = '/img/file_type/file.png';
            var type = 'file';
            if($img.indexOf(fileType.toLowerCase()) >=0){
                icon = '/img/file_type/image.png';
                type = 'image';

            }
            this.fileInp = {
                name: fileName,
                icon: icon,
                type: type
            };
            
        }
        this.f = this.$refs.send_file.files[0];
        console.log(this.f);
    },
    removeFile: function(e){
        this.$refs.send_file.value = '';
        this.fileInp = null;


    }
  },
  watch: {
  	user: function(newV,oldV){
      console.log(oldV);
  		console.log(newV);
  		this.message = [];
  		this.fetchMessage();
      setTimeout(function(){
          this.scrollToBottom();
      },500);
  	}
  }
}
</script>

<style lang="css" scoped>
</style>