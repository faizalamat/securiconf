<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div>
            <label for="conference">Select Conference:</label>
            <select id="conference" wire:model="selectedConference">
                <option value="">-- Choose a Conference --</option>
                @foreach ($conferences ?? [] as $conference)
                    <option value="{{ $conference->id }}">{{ $conference->name }}</option>
                @endforeach
            </select>
            @error('selectedConference') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" wire:model="name">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" wire:model="email">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button type="submit">Sign Up</button>
    </form>
</div>
