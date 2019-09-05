require('./bootstrap');
//Animation
require('vue2-animate/dist/vue2-animate.min.css')

//Module
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import Notifications from 'vue-notification';


Vue.use(Notifications);
Vue.use(VueRouter);
Vue.use(VueAxios, axios);

//Component
import Dashboard from './components/Dashboard.vue';
import Login from './components/Login.vue';
import Register from './components/Register.vue';
	//Chat component
	import FriendList from './components/FriendList.vue';
//Router Setup

//Setup
axios.defaults.baseURL = 'http://localhost:8000/api';

let routes = [

	{

		path: '',
		name: 'home',
		component: require('./components/Dashboard'),
		meta: {
			auth: true
		}

	},
	{

		path: '/AboutMe',
		component: require('./components/About')

	},
	{

		path: '/Porfolio',
		component: require('./components/About')

	},
	{

		path: '/AboutMe',
		component: require('./components/About')

	},
	//Authentication Route
	{
		path:'/Login',
		name:'login',
		component: Login
	},
	{
		path:'/Register',
		name:'register',
		component: Register
	},
	{
		path: 'Dashboard',
		name: 'dashboard',
		component: Dashboard
		// meta: {
		// 	auth: true
		// }
	}
	
];


let router = new VueRouter({
	routes: routes,
	linkActiveClass: 'is-active'
});

Vue.router = router;

Vue.use(require('@websanova/vue-auth'), {
   auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
   http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
   router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});

Vue.router = routes;

const app = new Vue({
    el: '#app',
    router,
    methods:{
    	logout(){
    		this.$auth.logout();
    		this.$router.push('/Login');
    	}
    }
});
