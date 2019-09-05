<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.css">
        
<style>
.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
    .list-complete-item {
      transition: all 1s;
      display: inline-block;
      margin-right: 10px;
    }
    .list-complete-enter, .list-complete-leave-to
    /* .list-complete-leave-active below version 2.1.8 */ {
      opacity: 0;
      transform: translateY(30px);
    }
    .list-complete-leave-active {
      position: absolute;
    }

</style>
    </head>
    <body>
        
        <div id="app">
            
                <section class="hero is-info">
                  <div class="hero-head">
                    <nav class="navbar">
                      <div class="container">
                        <div class="navbar-brand">
                          <a class="navbar-item">
                            <img src="https://bulma.io/images/bulma-type-white.png" alt="Logo">
                          </a>
                          <span class="navbar-burger burger" data-target="navbarMenuHeroB">
                            <span></span>
                            <span></span>
                            <span></span>
                          </span>
                        </div>
                        <div id="navbarMenuHeroB" class="navbar-menu">
                          <div class="navbar-end">
                            <a class="navbar-item is-active">
                              Home
                            </a>
                            <a class="navbar-item">
                              Examples
                            </a>
                            <a class="navbar-item">
                              Documentation
                            </a>
                            <span class="navbar-item">
                              <a class="button is-info is-inverted">
                                <span class="icon">
                                  <i class="fab fa-github"></i>
                                </span>
                                <span>Download</span>
                              </a>
                            </span>
                          </div>
                        </div>
                      </div>
                    </nav>
                  </div>

                  <div class="hero-body">
                    <div class="container has-text-centered">
                      <p class="title">
                        Title
                      </p>
                      <p class="subtitle">
                        Subtitle
                      </p>
                    </div>
                  </div>

                  <div class="hero-foot">
                    <nav class="tabs is-boxed is-fullwidth">
                      <div class="container">
                        <ul>
                            <router-link to="/" tag="li" exact v-if="$auth.check()">
                                <a>Home</a>
                            </router-link>
                            <router-link to="/Register" tag="li" v-if="!$auth.check()">
                                <a>Register</a>
                            </router-link>
                            <router-link to="/Login" tag="li" v-if="!$auth.check()">
                                <a>Login</a>
                            </router-link>
                            <router-link to="/Contact" tag="li" v-if="$auth.check()">
                                <a>Contact</a>
                            </router-link>     
                        <li v-if="$auth.check()" class="pull-right">
                        <a href="#" @click.prevent="logout();">Logout</a>
                        </li>                                                                                                           
                        </ul>
                      </div>
                    </nav>
                  </div>
                </section>


                <section class="container">
                    <transition name="slide-fade" mode="out-in">
                      <router-view></router-view>
                    </transition>

                </section>

        </div>
        
        <script src="/js/app.js"></script>

    </body>
</html>
