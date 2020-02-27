<?php
use Carbon\Carbon;
?>
@extends('layouts.app')

@section('content')
<div class="container profile">
    <div class="row justify-content-center">
        <div class="col-md-8">
        @if(Auth::user()->group == 2)	
        <div class="row boxs">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$count['post']}}</h3>

                <p>Posts</p>
              </div>
              <div class="icon">
                <i class="fas fa-paste"></i>
              </div>
              <a href="{{route('post.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$count['comment']}}<sup style="font-size: 20px"></sup></h3>

                <p>Comments</p>
              </div>
              <div class="icon">
                <i class="fas fa-comments"></i>
              </div>
              <a href="{{route('post.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$count['user']}}</h3>

                <p>Clients</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <a href="{{url('user')}}?filter=group&order=asc" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$count['admin']}}</h3>

                <p>Admins</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-lock"></i>
              </div>
              <a href="{{url('user')}}?filter=group&order=desc" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        @endif
        <div class="st"></div>
        	<div class="card">
        		<div class="card-body">
        			<div class="image-profile">
        				<img src="{{asset('storage')}}/{{$user->image}}">
        			</div>
        			<div class="body">
        				<div class="row info">
        					<div class="col-md-6 col-xm-12 text-center">
        						<span>Name: </span><span>{{$user->name}}</span>
        					</div>
        					<div class="col-md-6 col-xm-12 text-center">
        						<span>Username: </span><span>{{$user->username}}</span>
        					</div>
        					<div class="col-md-6 col-xm-12 text-center">
        						<span>Email: </span><span>{{$user->email}}</span>
        					</div>
        					<div class="col-md-6 col-xm-12 text-center">
        						<span>Group: </span><span>@if($user->group == 2) Admin @else User @endif</span>
        					</div>
        					<div class="col-md-6 col-xm-12  text-center">
        						<span>Created at: </span><span>{{$user->created_at}}</span>
        					</div>

        					<div class="col-md-6 col-xm-12  text-center">
        						<span>Last Update: </span><span>{{Carbon::parse($user->updated_at)->diffForHumans()}}</span>
        					</div>
        				</div>
        			</div>
        		</div>
        	</div>

        </div>
    </div>
</div>

@endsection

@section('js')


@endsection