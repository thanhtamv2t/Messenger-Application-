<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Create Project Form</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css" integrity="sha256-2pUeJf+y0ltRPSbKOeJh09ipQFYxUdct5nTY6GAXswA=" crossorigin="anonymous" />
</head>
<body>
	
	<div id="app">
		
		<div class="container">
			<ul>
				@foreach($projects as $project)
				<li>{{$project->name}}</li>
				@endforeach
			</ul>
			<form action="/project" class="form" method="post" @submit.prevent="onSubmit($event)" @keydown="errors.clear($event.target.name)">
				
				<div class="field">
				  <label class="label">Name</label>
				  <div class="control">
				    <input name="name" class="input" type="text" placeholder="Text input" v-model="name">

				    <span class="help is-danger" v-if="errors.has('name')" v-text="errors.get('name')"></span>
				  </div>
				</div>
				<div class="field">
				  <label class="label">Description</label>
				  <div class="control">
				    <input name="description" class="input" type="text" placeholder="Text input" v-model="description">
				  </div>
				  <span class="help is-danger" v-if="errors.has('description')" v-text="errors.get('description')"></span>
				</div>
				<div class="field is-grouped">
				  <div class="control">
				    <button class="button is-link">Submit</button>
				  </div>
				  <div class="control">
				    <button class="button is-text">Cancel</button>
				  </div>
				</div>

			</form>

		</div>

	</div>

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://unpkg.com/vue@2.5.17/dist/vue.js"></script>
        <script src="/js/app.js"></script>
</body>
</html>