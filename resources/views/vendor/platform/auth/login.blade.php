@extends('platform::auth')
@section('title',__('Sign in to your account'))

@section('content')
    @php
        $adminPanelDenied = session('admin_panel_denied');
        if ($adminPanelDenied === null && request()->query('admin_denied') === '1') {
            $adminPanelDenied = \App\Http\Middleware\EnsureAdminPanelAccess::ACCESS_DENIED_MESSAGE;
        }
    @endphp
    @if ($adminPanelDenied)
        <div class="alert alert-warning mb-4" role="alert">
            {{ $adminPanelDenied }}
        </div>
    @endif

    <h1 class="h4 text-body-emphasis mb-4">{{__('Sign in to your account')}}</h1>

    <form class="m-t-md"
          role="form"
          method="POST"
          data-controller="form"
          data-form-need-prevents-form-abandonment-value="false"
          data-action="form#submit"
          action="{{ route('platform.login.auth') }}">
        @csrf

        @includeWhen($isLockUser,'platform::auth.lockme')
        @includeWhen(!$isLockUser,'platform::auth.signin')
    </form>
@endsection
