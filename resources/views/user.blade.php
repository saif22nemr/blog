<?php
use Carbon\Carbon;
?>
@extends('layouts.app')

@section('content')
<div class="container user">
	<div class="card">
		<div class="card-header text-center"><h3>Users</h3></div>
		<div class="card-body">
			<h4 class="alert alert-info text-center">For update click on column and edit it.</h4>
			<div class="search">
				<form method="get" action="" id="filter">
					<div class="form-group row">
                    	<div class="col-md-6 filter-part">
                    		<div class="form-group row">
                    			<label class="">Filter by</label>
                    			<select name="filter" class="form-control text-left filter">
	                    			<option value="id" <?php if($filter == 'id') echo 'selected';?>>Id</option>
	                    			<option value="username" <?php if($filter == 'username') echo 'selected';?>>Username</option>
	                    			<option value="name" <?php if($filter == 'name') echo 'selected';?>>Name</option>
	                    			<option value="email" <?php if($filter == 'email') echo 'selected';?>>Email</option>
	                    			<option value="created_at" <?php if($filter == 'created_at') echo 'selected';?>>First Login</option>
	                    			<option value="group" <?php if($filter == 'group') echo 'selected';?>>Group</option>
	                    		</select>
	                    		<select name="order" class="form-control text-left">
	                    			<option value="asc" <?php if($order == 'asc') echo 'selected';?>>Asc</option>
	                    			<option value="desc" <?php if($order == 'desc') echo 'selected';?>>Desc</option>

	                    		</select>
                    		</div>
                    	</div>
                    	<div class="col-md-6 search-part ">
                    		<div class="row text-right">
                    			<div class="col-md-3">
		                    		<select name="searchType" class="form-control">
		                    			<option value="id" <?php if($searchType == 'id') echo 'selected';?>>Id</option>
		                    			<option value="username" <?php if($searchType == 'username') echo 'selected';?>>Username</option>
		                    			<option value="name" <?php if($searchType == 'name') echo 'selected';?>>Name</option>
		                    			<option value="email" <?php if($searchType == 'email') echo 'selected';?>>Email</option>
		                    		</select>
	                    		</div>
	                    		<div class="col-md-8">
	                    			<input type="search" name="search" placeholder="Search ..." class="form-control">
	                    		</div>
                    		</div>

                    	</div>
                    </div>
				</form>
			</div>
			<div class="user-data">
				<table class="table table-bordered">
				  <thead>
				    <tr>
				      <th scope="col">Id</th>
				      <th scope="col">Username</th>
				      <th scope="col">Name</th>
				      <th scope="col">Email</th>
				      <th scope="col">Group</th>
				      <th scope="col">Posts Count</th>
				      <th scope="col">First Login</th>
				      <!-- <th scope="col">Last Update</th> -->
				      <th>Control</th>
				    </tr>
				  </thead>
				  <tbody>
				  	@foreach($users as $user)
					    <tr data-id="{{$user->id}}">
					      <th scope="row">{{$user->id}}</th>
					      <td class="edit" data-column="username">{{$user->username}}</td>
					      <td class="edit" data-column="name">{{$user->name}}</td>
					      <td class="edit" data-column="email">{{$user->email}}</td>
					      <td class="edit" data-column="group" data-group="{{$user->group}}">@if($user->group == 2) Admin @else Client @endif</td>
					      <td class="text-center"><a href="{{route('user.post',$user->id)}}">{{$user->postCount}}</a></td>
					      <td>{{Carbon::parse($user->created_at)->diffForHumans()}}</td>
					      <!-- <td>{{Carbon::parse($user->updated_at)->diffForHumans()}}</td> -->
					      <td class="delete-user text-center"><a href="#" data-id="{{$user->id}}" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
					    </tr>
				    @endforeach
				  </tbody>
				</table>
				<div class="justify-content-center">{{$users->links()}}</div>
			</div>
		</div>
	</div>
</div>
<div class="nofigation">
    <div class="message">
        <p class="">Default Message! </p>
        <a href="#" class=""><i class="fas fa-times"></i></a>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$(function(){
		function makeNotefication(message, time = 3000){
		    $('.nofigation .message p').html(message);
		    $('.nofigation').css('left','14px');
		    setTimeout(function(){
		        $('.nofigation').css('left','-330px');
		    },time);
		}
		//filter-part when click on selectbox , it will send filter automatically
		$('.filter-part ').on('change','select.filter',function(e){
			$('form#filter').submit();
		});
		//delete user
		$('.user').on('click','.delete-user > a',function(e){
			e.preventDefault();
            if(confirm('Are you sure to delete this user?')){
			var thisTag = $(this);
			$.ajax({
				url: "{{url('api/user')}}/"+thisTag.data('id'),
				headers: {'Authorization': 'Bearer {{Auth()->user()->api_token}}','Accept' : 'application/json'},
				data: {'_method': 'DELETE'},
				cache: false,
				method:'POST',
				success: function(data, status, xhr){
					makeNotefication('<h6>Successfull delete</h6>');
					thisTag.parent().parent().fadeOut('500');
				},
				error: function(xhr, status , message){
					makeNotefication('<h6>Fail delete</h6><div>Error Message:  '+message+'</div>');
				},
			});
            }
		});//end delete user
		//start process edit user
		$('tbody tr').on('click','.edit',function(){

            var val = $('input.edit-user');
            console.log(val);
            var column = val.prop('name');
            var data = {_method:'PATCH'};
            data[column] = val.val();
            console.log(val.parent().parent().data('id'));
            $.ajax({
                url:"{{url('api/user')}}/"+val.parent().parent().data('id'),
                method: 'POST',
                headers: {'Authorization': 'Bearer {{Auth()->user()->api_token}}','Accept' : 'application/json'},
                data: data,
                cache: false,
                success: function(data, status, xhr){
                    makeNotefication('<h6>Successfull Update</h6>');
                    val.parent().addClass('edit');
                    val.parent().text(data['data'][val.prop('name')]);
                },
                error: function(xhr, status, message){
                    makeNotefication('<h6>Fail Edit</h6><div>- Error Message: '+message+'</div>',5000);
                    val.parent().addClass('edit');
                    val.parent().text(val.data('back'));
                }
            });
			var thisTag = $(this);
			if(thisTag.data('column') == 'group'){
				console.log('val: '+thisTag.text()+' -> length: '+thisTag.text().length);
				var content = '<select class="input" name="'+thisTag.data('column')+'"  data-back="'+thisTag.text()+'"><option value="2"';
				if(thisTag.data('group') == 2) content += ' selected ';
				content += '>Admin</option><option value="1" ';
				if(thisTag.data('group') == 1) content += ' selected ';
				content += '>Client</option></select>';
			}else{
				var content = '<input type="text" name="'+thisTag.data('column')+'" class="form-control edit-user input" data-back="'+thisTag.text()+'" value="'+thisTag.text()+'">';
			}

			thisTag.html(content);
			thisTag.removeClass('edit');
		});
		$('tbody tr').on('keydown','input.edit-user',function(e){
			var key = e.which;
			if(key == 13){ // if press button enter..
				var thisTag = $(this);
				var column = thisTag.prop('name');
				var data = {_method:'PATCH'};
				data[column] = thisTag.val();
				$.ajax({
					url:"{{url('api/user')}}/"+thisTag.parent().parent().data('id'),
					method: 'POST',
					headers: {'Authorization': 'Bearer {{Auth()->user()->api_token}}','Accept' : 'application/json'},
					data: data,
					cache: false,
					success: function(data, status, xhr){
						makeNotefication('<h6>Successfull Update</h6>');
						thisTag.parent().addClass('edit');
						thisTag.parent().text(data['data'][thisTag.prop('name')]);
					},
					error: function(xhr, status, message){
						makeNotefication('<h6>Fail Edit</h6><div>- Error Message: '+message+'</div>',5000);
						thisTag.parent().addClass('edit');
						thisTag.parent().text(thisTag.data('back'));
					}
				});
			}
		});
        $('body').on('click',function(e){
		});
		$('tbody tr').on('change','select.input',function(e){
			var thisTag = $(this);
			var column = thisTag.prop('name');
			var data = {_method:'PATCH'};
			console.log('change');
			data[column] = thisTag.val();
			$.ajax({
				url:"{{url('api/user')}}/"+thisTag.parent().parent().data('id'),
				method: 'POST',
				headers: {'Authorization': 'Bearer {{Auth()->user()->api_token}}','Accept' : 'application/json'},
				data: data,
				cache: false,
				success: function(data, status, xhr){
					makeNotefication('<h6>Successfull Update</h6>');
					if(data['data'][thisTag.prop('name')] == 2) var group = 'Admin';
					else var group = 'Client';
					thisTag.parent().addClass('edit');
					thisTag.parent().text(group);
				},
				error: function(xhr, status, message){
					makeNotefication('<h6>Fail Edit</h6><div>- Error Message: '+message+'</div>',5000);
					thisTag.parent().addClass('edit');
					thisTag.parent().text(thisTag.data('back'));
				}
			});
		});
	});

</script>
@endsection
