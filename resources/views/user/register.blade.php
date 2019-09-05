@extends('layout.master')

@section('title','User Register')

@section('container')

	<h1>User Register</h1>

	<div class="columns">
		<div class="column is-half">
			<form action="/user" method="post">
				@csrf
				<div class="field">
					<label for="user_name" class="label">Name: </label>
					<div class="control">
						<input type="text" class="input" name="user_name" id="user_name" required>
					</div>
				</div>
				<div class="field">
					<label for="user_name" class="label">Email: </label>
					<div class="control">
						<input type="Email" class="input" name="user_email" id="user_name" required>
					</div>
				</div>
				<div class="field">
					<label for="user_name" class="label">Password: </label>
					<div class="control">
						<input type="password" class="input" name="user_password" id="user_name" required>
					</div>
				</div>
				<div class="field">
					<label for="user_name" class="label">Re-enter Password: </label>
					<div class="control">
						<input type="password" class="input" name="user_re_password" id="user_name" required>
					</div>
				</div>
				<div class="field">
					<button class="button is-primary">Register</button>
					<button type="reset" class="button">Reset</button>
				</div>												
			</form>			
		</div>
	</div>

@endsection