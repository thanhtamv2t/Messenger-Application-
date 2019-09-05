@extends('layout.master')

@section('title','User Register')

@section('container')

	<h1>User Register</h1>

	<div class="columns">
		<div class="column is-half">
			<form action="/sessions/login" method="post">
				@csrf
				<div class="field">
					<label for="user_name" class="label">Email: </label>
					<div class="control">
						<input type="email" class="input" name="email" id="user_name" required>
					</div>
				</div>
				<div class="field">
					<label for="user_name" class="label">Password: </label>
					<div class="control">
						<input type="password" class="input" name="password" id="user_name" required>
					</div>
				</div>
				<div class="field">
					<button class="button is-primary">Login</button>
					<button type="reset" class="button">Reset</button>
				</div>												
			</form>			
		</div>
	</div>

@endsection