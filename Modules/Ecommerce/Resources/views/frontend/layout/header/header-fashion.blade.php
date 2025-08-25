<header>
        <div id="header-middle" class="header-middle">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-baseline">
                    <div class="category__menu show-on-mobile"><i class="material-symbols-outlined">menu</i></div>
                    <form action="{{route('products.search')}}" method="post" class="header-search">
                        @csrf
                        <button class="btn btn-search" type="submit" style="margin-top:-2px"><span class="d-flex"><i class="material-symbols-outlined">search</i></span></button>
                        <div class="header-search-container">
                            <input id="search" type="text" placeholder="Search products..." name="search">
                            <div class="search_result"></div>
                        </div>
                    </form>
                    <div class="logo">
                        <a href="{{url('/')}}">
                            @if(!config('database.connections.saleprosaas_landlord'))
                                @if(isset($ecommerce_setting->logo))
                                <img src="{{ url('frontend/images/') }}/{{$ecommerce_setting->logo}}" alt="{{$ecommerce_setting->site_title ?? ''}}">
                                @else
                                <img src="{{ asset('logo') }}/{{$general_setting->site_logo}}" alt="{{$ecommerce_setting->site_title ?? ''}}">
                                @endif
                            @else
                                @if(isset($ecommerce_setting->logo))
                                <img src="{{ asset('../../frontend/images/') }}/{{$ecommerce_setting->logo}}" alt="{{$ecommerce_setting->site_title ?? ''}}">
                                @else
                                <img src="{{ asset('../../logo') }}/{{$general_setting->site_logo}}" alt="{{$ecommerce_setting->site_title ?? ''}}">
                                @endif
                            @endif
                        </a>
                    </div>
                    <ul class="offset-menu-wrapper">
                        <!-- <li class="language"><a  class="active" href="">En</a> / <a href="">Bn</a></li> -->
                        @guest
                        <li>
                            <a href="{{url('customer/login')}}">Login</a>
                        </li>
                        @endguest
                        @if(auth()->user() && auth()->user()->role_id == 5)
                        <li class="user-menu">
                            <i class="material-symbols-outlined">person_add</i>
                            <ul class="user-dropdown-menu">
                                <li><a href="{{url('customer/account-details')}}">My Account</a></li>
                                <li><a href="{{url('customer/orders')}}">Order History</a></li>
                                <li><a href="{{url('customer/address')}}">Addresses</a></li>
                                <li><a href="{{url('customer/logout')}}"> {{trans('file.logout')}}</a></li>
                            </ul>
                        </li>
                        @endif
                        <li>
                            <a href="{{url('track-order')}}" title="{{trans('file.Track Order')}}"><i class="material-symbols-outlined">pin_drop</i></a>
                        </li>
                        <li class="wishlist__menu">
                            <a href="{{url('customer/wishlist')}}" title="{{trans('file.Wishlist')}}"><i class="material-symbols-outlined" title="{{trans('file.My Wishlist')}}">favorite</i></a>
                            <span class="badge badge-light cart_qty">
                            {{ $wishlist_count }}
                            </span>
                        </li>
                        @php

                        $total_qty = session()->has('total_qty') ? session()->get('total_qty') : 0;
                        $subTotal = session()->has('subTotal') ? session()->get('subTotal') : 0;

                        if($total_qty == 0){
                            $subTotal = 0;
                        }

                        @endphp
                        <li class="cart__menu">
                            <i class="material-symbols-outlined" title="{{trans('file.Cart')}}">shopping_bag</i>
                            <span class="badge badge-light cart_qty">{{ $total_qty ?? 0}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="header-bottom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-header-inner">
                            <div id="main-menu" class="main-menu">
                                <nav id="mobile-nav" class="d-flex justify-content-center">
                                    <ul class="w-100">
                                        @if(!empty($topNavItems))
                                            @foreach($topNavItems as $nav)
                                                @if(!empty($nav->children[0]))
                                                <li class="user-menu">
                                                    <a href="#">@if($nav->name == NULL) {{$nav->title}} @else {{$nav->name}} @endif <i class="caret"></i>
                                                        <ul class="user-dropdown-menu">
                                                            @foreach($nav->children[0] as $childNav)
                                                            @if($childNav->type == 'custom')
                                                            <li><a href="{{$childNav->slug}}" target="_blank">@if($childNav->name == NULL) {{$childNav->title}} @else {{$childNav->name}} @endif</a></li>
                                                            @elseif($childNav->type == 'category')
                                                            <li><a href="{{url('shop')}}/{{$childNav->slug}}">@if($childNav->name == NULL) {{$childNav->title}} @else {{$childNav->name}} @endif</a></li>
                                                            @else
                                                            <li><a href="{{url('')}}/{{$childNav->slug}}">@if($childNav->name == NULL) {{$childNav->title}} @else {{$childNav->name}} @endif</a></li>
                                                            @endif
                                                            @endforeach
                                                        </ul>
                                                    </a>
                                                </li>
                                                @else
                                                    @if($nav->type == 'custom')
                                                    <li><a href="{{$nav->slug}}" target="_blank">@if($nav->name == NULL) {{$nav->title}} @else {{$nav->name}} @endif</a></li>
                                                    @elseif($nav->type == 'category')
                                                    <li><a href="{{url('shop')}}/{{$nav->slug}}">@if($nav->name == NULL) {{$nav->title}} @else {{$nav->name}} @endif</a></li>
                                                    @elseif($nav->type == 'page' && ($nav->slug == 'home'))
                                                    <li><a href="{{url('/')}}">@if($nav->name == NULL) {{$nav->title}} @else {{$nav->name}} @endif</a></li>
                                                    @elseif($nav->type == 'collection')
                                                    <li><a href="{{url('products')}}/{{$nav->slug}}">@if($nav->name == NULL) {{$nav->title}} @else {{$nav->name}} @endif</a></li>
                                                    @elseif($nav->type == 'brand')
                                                    <li><a href="{{url('brand')}}/{{$nav->slug}}">@if($nav->name == NULL) {{$nav->title}} @else {{$nav->name}} @endif</a></li>
                                                    @else
                                                    <li><a href="{{url('')}}/{{$nav->slug}}">@if($nav->name == NULL) {{$nav->title}} @else {{$nav->name}} @endif</a></li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>