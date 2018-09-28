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
          <div class="col-md-6 col-lg-6">
            <!-- Product - Grid build-->
            @foreach($groups as $group)
            <article class="product">
              <header class="product-header">
                <!-- Badge-->
                <div class="badge badge-red">hot<span class="icon material-icons-whatshot"></span>
                </div>
                <div class="product-figure"><img src="./Home_files/product-small-1.png" alt=""></div>
                <div class="product-buttons">
                  <div class="product-button product-button-share fl-bigmug-line-share27" style="font-size: 22px">
                    <ul class="product-share">
                      <li class="product-share-item"><span>Share</span></li>
                      <li class="product-share-item"><a class="icon fa fa-facebook" href="shop-elements.html#"></a></li>
                      <li class="product-share-item"><a class="icon fa fa-instagram" href="shop-elements.html#"></a></li>
                      <li class="product-share-item"><a class="icon fa fa-twitter" href="shop-elements.html#"></a></li>
                      <li class="product-share-item"><a class="icon fa fa-google-plus" href="shop-elements.html#"></a></li>
                    </ul>
                  </div><a class="product-button fl-bigmug-line-shopping202" href="shopping-cart.html" style="font-size: 26px"></a><a class="product-button fl-bigmug-line-zoom60" href="images/shop/product-1-original.jpg" data-lightgallery="item" style="font-size: 25px"></a>
                </div>
              </header>
              <footer class="product-content">
                <h6 class="product-title"><a href="shop-elements.html#">{{$group->name}}</a></h6>
                <div class="product-price"><span class="product-price-old">$400</span><span class="heading-6 product-price-new">$290</span>
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
            @endforeach
          </div>
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
        $.each($('#create_group_form').serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });
        // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : "{{route('group.create')}}", // the url where we want to POST
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
              }
              else{alert('error occured');}
            });
        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });
});
</script>
@endsection
