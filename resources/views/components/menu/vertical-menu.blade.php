<!--**********************************
        Sidebar start
    ***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            @foreach ($menuItems as $item)
                @if(isset($item['type']) && $item['type'] === 'label')
                    <li class="nav-label first">{{ $item['title'] }}</li>
                @elseif(isset($item['child']) && is_array($item['child']) && count($item['child']) > 0)
                    <li>
                        <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <i class="{{ $item['icon'] ?? 'icon icon-circle' }}"></i>
                            <span class="nav-text">{{ $item['title'] }}</span>
                        </a>
                        <ul aria-expanded="false">
                            @foreach($item['child'] as $child)
                                @if (!isset($child['show']) || $child['show'])
                                    <li><a href="{{ $child['url'] ?? '#' }}">{{ $child['title'] }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @elseif(!isset($item['child']) || empty($item['child']))
                    <li>
                        <a href="{{ $item['url'] ?? '#' }}">
                            <i class="{{ $item['icon'] ?? 'icon icon-circle' }}"></i>
                            <span class="nav-text">{{ $item['title'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->
