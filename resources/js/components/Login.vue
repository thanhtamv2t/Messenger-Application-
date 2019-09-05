<template>
    <div class="login-form">
        <div class="alert alert-danger" v-if="error">
            <p>There was an error, unable to sign in with those credentials.</p>
        </div>        
        <form autocomplete="off" @submit.prevent="login" method="post">

              <div class="control has-icons-left has-icons-right">
                <input class="input" type="email" placeholder="Email" v-model="email" required>
                <span class="icon is-large is-left">
                  <i class="fas fa-envelope"></i>
                </span>
              </div>
              <div class="control has-icons-left has-icons-right">
                <input class="input" type="password" placeholder="Password" v-model="password" required>
                <span class="icon is-large is-left">
                  <i class="fas fa-lock"></i>
                </span>
              </div>

            <button type="submit" class="button is-fullwidth" style="margin-top: 12px">Login</button>
            <div class="columns" style="margin-top:12px; margin-bottom: -12px;">
                <div class="column is-left">Register</div>
                <div class="column is-right">Forgot password ?</div>
            </div>
            <hr>
            <a class="button is-fullwidth is-info" style="margin-top: 12px" @click="loginWithGoogle"><i class="fab fa-google"></i> Login With Google</a>
        </form>
        <notifications group="msg" position="top center" width="50%"/>
    </div>
</template>

<script>
var provider = new firebase.auth.GoogleAuthProvider();
provider.addScope('https://www.googleapis.com/auth/contacts.readonly');
firebase.auth().languageCode = 'vi';
export default {

  name: 'Login',

  data () {
    return {
        email: null,
        password: null,
        error: false
    }
  },
  methods:{
    login(){
        var app = this
        this.$auth.login({
            params: {
              email: app.email,
              password: app.password
            }, 
            success: function () {
                  this.$notify({
                  group: 'msg',
                  type: 'success',
                  title: 'Notification',
                  text: 'Login Success'
                });            
                setTimeout(function(){
                  app.$router.push({name: 'home'});
                },500);
                
            },
            error: function (e) {
                  this.$notify({
                  group: 'msg',
                  type: 'error',
                  title: 'Notification',
                  text: 'Invalid Credential'
                });              
            },
            rememberMe: true,
            redirect: '/Home',
            fetchUser: true
        });        
    },
    loginWithGoogle(){
        var app = this;
        firebase.auth().signInWithPopup(provider).then(function(result) {
          // This gives you a Google Access Token. You can use it to access the Google API.
          var token = result.credential.accessToken;
          var user = result.user;
          user.token = token;
            app.$auth.login({
                params: {
                    type: 'Google',
                    user: {
                        name: user.displayName,
                        email: user.email,
                        password: user.token
                    }
                },
                success: function(){
                    this.$router.push({name: 'home'});
                },
                error: function(){
                    alert('Please try again later!');
                },
                redirect: '/Home',
                fetchUser: true,
                rememberMe: true
            });
        }).catch(function(error) {
            console.log(error);
          // Handle Errors here.
          var errorCode = error.code;
          var errorMessage = error.message;
          // The email of the user's account used.
          var email = error.email;
          // The firebase.auth.AuthCredential type that was used.
          var credential = error.credential;
          // ...
        });        
    }
  },
  created(){
    if(this.$auth.check()){
        this.$router.push({name: 'home'});
    }
  }
}
</script>

<style lang="css" scoped>
</style>