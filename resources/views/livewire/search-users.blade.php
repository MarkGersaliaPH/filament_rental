<div>
    <div>
    <!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->
    <input wire:model.live="search">
    @foreach ($users as $user)
        <div>{{ $user->name }}</div>
    @endforeach
</div>
</div>
