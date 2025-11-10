@props(['title', 'description' => '', 'actions' => []])

<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 mb-1">{{ $title }}</h1>
        @if($description)
            <p class="text-gray-500 text-sm">{{ $description }}</p>
        @endif
    </div>
    @if(count($actions) > 0)
        <div class="flex items-center space-x-2">
            @foreach($actions as $action)
                <a href="{{ $action['url'] ?? '#' }}" 
                   class="btn-{{ $action['type'] ?? 'primary' }} {{ $action['class'] ?? '' }}">
                    @if(isset($action['icon']))
                        <i class="{{ $action['icon'] }} mr-2"></i>
                    @endif
                    {{ $action['label'] ?? 'Action' }}
                </a>
            @endforeach
        </div>
    @endif
</div>

