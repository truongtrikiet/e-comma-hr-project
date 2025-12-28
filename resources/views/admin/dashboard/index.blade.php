<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Admin Try Dashboard page') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    </x-slot:headerFiles>

    <div>
        <h1>Dashboard page</h1>
    </div>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.dashboard') => '',
        ]"
    />


</x-base-layout>