<ul class="menu-list {{ $class ?? '' }}">
    @foreach($items as $item)
        <li class="menu-item {{ $item->classes }} {{ $item->children->count() > 0 ? 'has-children' : '' }}">
            <a href="{{ $item->calculated_url }}" class="menu-link">
                {{ $item->calculated_label }}
            </a>
            
            @if($item->children->count() > 0)
                <ul class="sub-menu">
                    @foreach($item->children as $child)
                        <li class="sub-menu-item {{ $child->classes }}">
                            <a href="{{ $child->calculated_url }}" class="sub-menu-link">
                                {{ $child->calculated_label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
