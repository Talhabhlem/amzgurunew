<aside class="aside">
    <!-- START Sidebar (left)-->
    <div class="aside-inner">
        <nav data-sidebar-anyclick-close="" class="sidebar">
            <!-- START sidebar nav-->
            <ul class="nav">
                <!-- START user info-->
                <li class="has-user-block">
                    <div id="user-block" class="">
                        <div class="item user-block">
                            <!-- User picture-->
                            <div class="user-block-picture">
                                <div class="user-block-status">
                                    <img src="{{url('/assets/images/profile.png')}}" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle">
                                    <div class="circle circle-success circle-lg"></div>
                                </div>
                            </div>
                            <!-- Name and Job-->
                            <div class="user-block-info">
                                <span class="user-block-name">Hello, {{Auth::user()->name}}</span>
                                <span class="user-block-role">{{Auth::user()->role}}</span>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- END user info-->
                <!-- Iterates over all sidebar items-->
                @foreach (Nav::$nav->where('nav', 'main')->sortBy('sort') as $chunk => $c)
                    <li class="@if($currentnav==$c['slug']) active @endif">
                        <a href="{!! $c['link']!!}" title="{{ $c['title']}}">
                            {{--<em class="@if($c['slug']== 'settings/email') {{'fa fa-envelope-o'}} @else {{'fa fa-money'}} @endif"></em>--}}
                            <em class="{{$c['icon']}}"></em>
                            <span data-localize="sidebar.nav.{{ $c['title']}}">{{ $c['title']}}</span>
                        </a>

                        @if(isset($c['submenu']))
                            <ul id="dashboard" class="nav sidebar-subnav collapse">
                                @foreach($c['submenu'] as $cc)
                                    <li class=" active">
                                        <a href="{{ $cc['link']}}" title="{{ $cc['title']}}">
                                            <span>{{ $cc['title']}}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                <li class="@if(Route::getFacadeRoot()->current()->uri()=='settings/amazon') {{'active'}} @endif">
                    <a href="{{url('/settings/amazon')}}" title="Amazon settings">
                        <em class="icon-settings"></em>
                        <span>Amazon settings</span>
                    </a>
                </li>
            </ul>
            <!-- END sidebar nav-->
        </nav>
    </div>
    <!-- END Sidebar (left)-->
</aside>