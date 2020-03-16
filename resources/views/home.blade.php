<?php
use Carbon\Carbon;
?>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>Create Post</h4></div>
                <form id="create-post" method="POST" action="{{route('post.index')}}" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                           <textarea name="body" required="" class="form-control body" rows="4" placeholder="What's on your mind, {{ucfirst(explode(' ',Auth()->user()->name)[0])}}?"></textarea>
                        </div>
                    </div>
                    <div class="card-footer create-footer">
                       <input type="file" name="image"  class="image" required=""> 

                       <input type="submit" name="createPost" class="btn btn-primary" value="Post">
                    </div>
                </form>
            </div>
            <section class="post-section">
                    @if(isset($posts) and count($posts) != 0)
                    @foreach($posts as $post)
                    <div class="post">
                        <div class="post-header">
                            <div class="owner">
                                <img src="{{asset('storage')}}/{{$post->user->image}}">
                                <div class="">
                                    <div class="name">{{$post->user->name}}</div>
                                    <div>{{Carbon::parse($post->created_at)->diffForHumans()}}</div>
                                </div>
                                
                            </div>
                            @if(Auth()->user()->group == 2 || $post->user->id == Auth()->user()->id) 
                            <div class="edit">
                                <span>
                                    <a href="#" class="post-edit" data-id="{{$post->id}}"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="post-delete" data-id="{{$post->id}}"><i class="fas fa-trash"></i></a>
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="post-content clearfix">{{$post->body}}</div>
                        <div class="post-image">
                            <img src="{{asset('storage')}}/{{$post->image}}">
                        </div>
                        <hr>
                        <div class="comment">
                            <div class="comment-header">
                                <div>Comment</div>
                                <div class="comments-count clearfix">{{count($post->comments)}}</div>
                            </div>
                            <div class="comment-body clearfix">
                                <div class="row add-comment">
                                <img src="{{asset('storage')}}/{{Auth()->user()->image}}" alt="img">
                                <div class="content col-sm-10">
                                    <form class="form" id="create-comment" method="post" action="">
                                        @csrf

                                        <input type="text" name="comment" data-post="{{$post->id}}" data-user="{{Auth()->user()->id}}" class="form-control comment" placeholder="Write a comment ...">
                                    </form>
                                </div>
                            </div>
                            <hr>
                                @foreach($post->comments as $comment)
                                    <div class="row">
                                        <img src="{{asset('storage')}}/{{$comment->user->image}}" alt="img">
                                        <div class="content col-sm-10">
                                            <div>
                                                <div>
                                                    <span>{{$comment->user->name}}</span> 
                                                    <span>{{Carbon::parse($comment->created_at)->diffForHumans()}}</span>
                                                </div>
                                                
                                                <div>
                                                    @if(Auth()->user()->group == 2 || $comment->user->id == Auth()->user()->id || $post->user->id == Auth()->user()->id)
                                                    <a href="#" class="comment-edit" data-id="{{$comment->id}}"><i class="fas fa-edit"></i></a>
                                                    <a href="#" class="comment-delete" data-id="{{$comment->id}}"><i class="fas fa-trash"></i></a>
                                                    @endif
                                                </div>
                                                
                                            </div>
                                            <p> {{$comment->comment}} </p>
                                        </div>
                                    </div>
                                @endforeach
                                
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div>{{ $posts->links() }}</div>
                @else
                    <h2 class="alert alert-warning text-center"> There not posts !!</h2>
                @endif
            </section>
            
        </div>
    </div>
</div>
<div class="update-post">
    <div class="container col-md-8 col-sm-10">
        <div class="card">
            <div class="card-header"><h4>Update Post</h4> <a href="#" class="close-update-post"><i class="fas fa-times"></i></a> </div>
            <form id="update-post" method="POST" action="{{route('post.index')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="card-body">
                    <div class="form-group">
                       <textarea name="body" required="" class="form-control body" rows="4" placeholder="What's on your mind, {{ucfirst(explode(' ',Auth()->user()->name)[0])}}?"></textarea>
                    </div>
                </div>
                <div class="card-footer create-footer">
                   <input type="file" name="image"  class="image"> 

                   <input type="submit" name="createPost" class="btn btn-primary" value="Save  Post">
                </div>
            </form>
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
        $('.nofigation .message > a').on('click',function(e){
            e.preventDefault();
            $('.nofigation').css('left','-330px');
        });
        function makeNotefication(message, time = 3000){
            $('.nofigation .message p').html(message);
            $('.nofigation').css('left','14px');
            setTimeout(function(){
                $('.nofigation').css('left','-330px');
            },time);
        }
        //$('.nofigation').css('left','14px');
        //create new post
        $('#create-post input[type=submit]').on('click',function(e){
            //first validate form
            e.preventDefault();
            var message = '';
            if($('textarea.body').val().length < 10){
                message += '<div>- It\'s must be content of post and at least 10 character</div>';
            }
            if(!$('input[type=file].image').val()){
                message += '<div>- The image is required</div>';
            }
            if(message != ''){
                
                makeNotefication(message);
            }
            else{
                var header = {
                        'Authorization': 'Bearer {{Auth()->user()->api_token}}',
                        'Accept' : 'application/json',
                };
                var formData = new FormData($('#create-post')[0]);
                $.ajax({
                    url: '{{route("post.store")}}',
                    method: 'POST',
                    headers:header,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data, status, xhr){
                        message += '<h4 class="text-center">Successfull Create</h4>';
                        makeNotefication(message)
                        window.location.href = "{{route('post.index')}}";
                    },
                    error:function(xhr,status,data){
                        makeNotefication('<div>- Error Status: '+status+'</div> <div>- Message: '+data+'</div>',6000);
                        console.log(xhr);
                    },
                });
            }
        });
        //start edit post
        $('body').on('click','a.post-edit',function(e){
            e.preventDefault();
            var postId = $(this).data('id');
            var postBody = $(this).parent().parent().parent().siblings('.post-content').text();
            $('.update-post').show(400);
            $('.update-post .card').css('margin-top','100px');
            $('#update-post').prop('action','{{route("post.index")}}/'+postId);
            $('#update-post textarea.body').text(postBody);
        });
        //close post update
        $('.card').on('click','a.close-update-post',function(e){
            e.preventDefault();
            $('.update-post').hide(500);
        });
        //end edit post
        //delete post
        $('.post-delete').on('click',function(e){
            e.preventDefault();
            var t = $(this);
            var id = $(this).data('id');
            var header = {
                    'Authorization': 'Bearer {{Auth()->user()->api_token}}',
                    'Accept' : 'application/json',
            };
            var message = '';
            
            $.ajax({
                url: '{{route("post.store")}}/'+id,
                method: 'POST',
                headers:header,
                data: {'_method':'DELETE'},
                cache:false,
                success: function(data, status, xhr){
                    if(!data['data']['id']) {
                        makeNotefication('<h4>Fail Delete</h4>');
                        return 0;
                    }
                    message += '<h4 class="text-center">Successfull Delete</h4>';
                    makeNotefication(message)
                    t.parent().parent().parent().parent().fadeOut(500);
                    
                },
                error:function(xhr,status,data){
                    makeNotefication('<h4 class="text-center">Fail Delete</h4>',6000);
                    console.log(xhr);
                    
                },
            });
        }); // end delete post
        //start create comment
        $('#create-comment .comment').on('keypress',function(e){
            var key = e.which;//when click on key Enter
            if(key == 13){
                e.preventDefault();
                var thisTag = $(this);
                //first check if the input is empty
                if(thisTag.val().length == 0){
                    makeNotefication('<div>- The Comment is empty !!</div>');
                }else{
                    //create comment by ajax
                    $.ajax({
                        url:"{{route('comment.store')}}",
                        method:'POST',
                        cache:false,
                        headers: {'Authorization': 'Bearer {{Auth()->user()->api_token}}','Accept' : 'application/json',},
                        data:{
                            comment: thisTag.val(),
                            post_id: thisTag.data('post'),
                            user_id: thisTag.data('user'),
                        },
                        success: function(data, status, xhr){
                            makeNotefication('<h7>- Create Comment Successfully</h7>');
                            var content = '<div class="row"><img src="{{asset("storage")}}/{{Auth()->user()->image}}" alt="img"><div class="content col-sm-10"><div>'+
                                '<div><span>{{Auth()->user()->name}}</span> <span>2 seconds ago</span></div>'+
                                '<div><a href="#" class="comment-edit" data-id="'+data['data']['id']+'"><i class="fas fa-edit"></i></a><a href="#" class="comment-delete" data-id="'+data['data']['id']+'"><i class="fas fa-trash"></i></a></div>'+
                                '</div><p>'+data['data']['comment']+'</p></div></div>';
                            $('.comment-body hr').after(content);
                                    //the next part to increase count of comments
                            var count = thisTag.parent().parent().parent().parent().siblings('.comment-header').children('.comments-count');//
                            count.text(parseInt(count.text())+1);
                            thisTag.val('');
                        },
                        error: function(xhr, status, message){
                            makeNotefication('<h7>- Error: '+message+'</h7>',5000);
                        }
                    });
                }
                
            }
            
        });//end create comment
        //start edit comment
        var g = '';
        $('.comment').on('click','a.comment-edit',function(e){
            e.preventDefault();
            var thisTag = $(this);
            var paragraph = thisTag.parent().parent().siblings('p');
            g = paragraph.val();
            var content = '<input type="text" data-id="'+thisTag.data('id')+'" data-comment="'+paragraph.text()+'" name="comment" placeholder="Enter comment!" value="'+paragraph.text()+'" class="form-control comment edit-comment">';
            paragraph.html(content);
        });
        $('.comment').on('keypress','.comment.edit-comment',function(e){
            var key = e.which;
            if(key == 13){
                var thisTag = $(this);
                if(thisTag.val().length <= 1){
                    makeNotefication('<div>- The comment is empty !!</div>');
                }else{
                    $.ajax({
                        url:"{{route('comment.store')}}/"+thisTag.data('id'),
                        method: 'POST',
                        cache:false,
                        headers: {'Authorization': 'Bearer {{Auth()->user()->api_token}}','Accept' : 'application/json'},
                        data: {'_method':'PATCH','comment': thisTag.val()},
                        success: function(data, status, xhr){
                            makeNotefication('<h5>Successfull update comment.</h5>');
                            thisTag.parent().text(data['data']['comment']);
                        },
                        error: function(xhr, status, message){
                            makeNotefication('<div>Fail to update comment.</div><div>- Error message: '+message+'</div>',5000);
                            thisTag.parent().text(thisTag.data('comment'));
                        },
                    });
                }
            }
                
        });
        //end edit comment
        //start delete comment
        $('.comment-body').on('click','a.comment-delete',function(e){
            e.preventDefault();
            var thisTag = $(this);
            $.ajax({
                url:"{{route('comment.store')}}/"+thisTag.data('id'),
                method:'POST',
                cache:false,
                headers: {'Authorization': 'Bearer {{Auth()->user()->api_token}}','Accept' : 'application/json',},
                data: {'_method':'DELETE'},
                success: function(data, status, xhr){
                    makeNotefication('<div>- Successfull comment delete.</div>');
                    thisTag.parent().parent().parent().parent().fadeOut(300);
                    //the next part to decrease count of comments
                    var count = thisTag.parent().parent().parent().parent().parent().siblings('.comment-header').children('.comments-count');
                    if(count.text() != '0'){
                        count.text(''+parseInt(count.text())-1);
                    }
                    
                },
                error: function(xhr, status, message){
                    makeNotefication('<div>- Fail delete comment.</div><div>- Error: '+message+'</div>',7000);
                    console.log(xhr);
                }
            });
        });
        //end delete comment
    });
</script>

@endsection
