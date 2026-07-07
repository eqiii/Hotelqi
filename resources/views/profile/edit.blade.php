<x-hotel-app-layout>
    <x-slot name="pageTitle">Profil Saya</x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <div class="card-hotel p-4 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card-hotel p-4 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card-hotel p-4 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-hotel-app-layout>
