@extends('layouts.app')

@section('meta')
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php $crumb = 'Groups'; ?>
<title>{{ config('app.name', $crumb) }}</title>
@endsection

@include('layouts.header')
@include('layouts.page-top')
@section('content')
<section class="section section-sm bg-gray-100">
  <div class="container">
    <div class="row row-30">
      <div class="col-lg-8">
        <!-- Heading Component-->
        <article class="heading-component">
          <div class="heading-component-inner">
            <h5 class="heading-component-title">Available Group(s)
            </h5>
          </div>
        </article>
        <div class="row row-30">
          <!-- Product - Grid build-->
          <?php
          $member= false;
          $guest = true;
          if(!($guest = !(\Auth::user()))){
            $member = App\Member::isMember(\Auth::user()->id);
          }
          ?>
          @foreach($groups as $group)
          <div class="col-md-6 col-lg-6">
            <article class="product">
              <header class="product-header">
                <!-- Badge-->
                <div class="badge badge-red">hot<span class="icon material-icons-whatshot"></span>
                </div>
                <div class="product-figure"><img src="{{URL::asset('images/team/logo/'.$GLOBALS['current']->badge)}}" alt=""></div>
                <div class="product-buttons">
                  <div class="product-button product-button-share fl-bigmug-line-share27" style="font-size: 22px">
                    <ul class="product-share">
                      <li class="product-share-item"><span>Share</span></li>
                      <li class="product-share-item"><a class="icon fa fa-facebook" href="#"></a></li>
                      <li class="product-share-item"><a class="icon fa fa-instagram" href="#"></a></li>
                      <li class="product-share-item"><a class="icon fa fa-twitter" href="#"></a></li>
                      <li class="product-share-item"><a class="icon fa fa-google-plus" href="#"></a></li>
                    </ul>
                  </div>
                  <a class="product-button fa fa-eye" data-placement="right" title="View Group" href="{{route('group', [App\Club::toLink($GLOBALS['currentName']), App\Club::toLink($group->name)])}}" style="font-size: 26px"></a>
                  <a id="join_btn{{$group->id}}" class="product-button fa fa-{{(App\Member::isMember(\Auth::user()->id, $group->id)) ? 'remove' : 'plus'}}" data-placement="right" title="{{(App\Member::isMember(\Auth::user()->id, $group->id)) ? 'Leave Group' : 'Join Group'}}" href="{{$guest ? route('auth.sign',['action' => 'joinGroup', 'key' => $group->id, 'club' => $group->club_id]) : '#'}}" style="font-size: 25px"
                  @if(!$guest)
                  onclick="event.preventDefault(); {{(App\Member::isMember(\Auth::user()->id, $group->id)) ? 'leaveGroup('.$group->id.')' : ' joinGroup('.$group->id.')'}};"
                  @endif
                  ></a>
                  @if(!$guest)
                  <form id="{{$group->id}}" method="POST" style="display: none;" onsubmit="event.preventDefault();">
                    <input name="club" type="hidden" value="{{$group->club_id}}"/>
                    <input name="user" type="hidden" value="{{\Auth::user()->id}}"/>
                    <input name="group" type="hidden" value="{{$group->id}}"/>
                      @csrf
                  </form>
                  @endif
                </div>
              </header>
              <footer class="product-content">
                <h6 class="product-title"><a href="{{route('group', ['atletico', implode('-', explode(' ', $group->name))])}}">{{$group->name}}</a></h6>
                <div class="product-price"><span class="">
                  <i class="fa fa-users"></i></span><span class="heading-6 ">{{$group->number_members}}</span>
                </div>
                <ul class="product-rating">
                  <li><span class="material-icons-star"></span></li>
                  <li><span class="material-icons-star"></span></li>
                  <li><span class="material-icons-star"></span></li>
                  <li><span class="material-icons-star"></span></li>
                  <li><span class="material-icons-star_half"></span></li>
                </ul>
              </footer>
            </article>
          </div>
          @endforeach
        </div>
      </div>
      @include('layouts.sidebar')
    </div>
  </div>
</section>
@endsection

@section('js')
<script>
$(document).ready(function(){
    $('#create_group_form').submit(function(event) {
    var confirmed = confirm('confirm to create group');
    var values = {};
    if(confirmed){
      $.each($('#create_group_form').serializeArray(), function(i, field) {
          values[field.name] = field.value;
      });
      // process the form
      $.ajax({
          type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
          url         : "{{route('group.create', 'atletico')}}", // the url where we want to POST
          data        : values, // our data object
          dataType    : 'json', // what type of data do we expect back from the server
          encode      : true
      })
          // using the done promise callback
          .done(function(data) {
            if(data.status){
              // log data to the console so we can see
              console.log(data);
              alert('Group Created');
              // here we will handle errors and validation messages
              window.location.reload();
            }
            else{alert('error occured');}
          });
      // stop the form from submitting the normal way and refreshing the page
      event.preventDefault();
    }
  });
});
function joinGroup(id){
  var confirmed = confirm('Are you sure you want to join the group?');
  var values = {};
  if(confirmed){
      $.each($('#'+id).serializeArray(), function(i, field) {
      values[field.name] = field.value;
    });
    // process the form
    $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : "{{route('group.join', 'atletico')}}", // the url where we want to POST
        data        : values, // our data object
        dataType    : 'json', // what type of data do we expect back from the server
        encode      : true
    })
    // using the done promise callback
    .done(function(data) {
      if(data.status){
        // log data to the console so we can see
        console.log(data);
        alert('Group Joined');
        // here we will handle errors and validation messages
        window.location.reload();
      }
      else if (!data.status) {
        alert(data.reason);
      }
      else{alert('error occured');}
    });
  }
}
function leaveGroup(id){

  var confirmed = confirm('Are you sure you want to leave the group?');
  var values = {};
  if(confirmed){
      $.each($('#'+id).serializeArray(), function(i, field) {
      values[field.name] = field.value;
    });
    // process the form
    $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : "{{route('group.leave', 'atletico')}}", // the url where we want to POST
        data        : values, // our data object
        dataType    : 'json', // what type of data do we expect back from the server
        encode      : true
    })
    // using the done promise callback
    .done(function(data) {
      if(data.status){
        // log data to the console so we can see
        console.log(data);
        alert('Group left');
        window.location.reload();
      }
      else if (!data.status) {
        alert(data.reason);
      }
      else{alert('error occured');}
    });
  }
}
</script>
@endsection
