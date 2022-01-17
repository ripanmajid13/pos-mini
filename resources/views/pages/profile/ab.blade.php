@if ($sts)
    @if ($profile->place_of_birth)
        <hr class="my-1">

        <strong><i class="fas fa-envelope mr-1"></i> Born</strong>
        <p class="text-muted mb-0">
            <span class="auth-place-of-birth">{{ $profile->place_of_birth }}</span>,
            {{ date_format(date_create($profile->date_of_birth), "d/m/Y") }}
        </p>

        <hr class="my-1">

        <strong><i class="fas fa-envelope mr-1"></i> Gender</strong>
        <p class="text-muted mb-0 auth-email">{{ $profile->gender == 'm' ? 'Man' : 'Woman' }}</p>

        <hr class="my-1">

        <strong><i class="fas fa-envelope mr-1"></i> Handphone</strong>
        <p class="text-muted mb-0 auth-email">{{ $profile->hp }}</p>

        <hr class="my-1">

        <strong><i class="fas fa-envelope mr-1"></i> Address</strong>
        <p class="text-muted mb-0 auth-email">{{ $profile->address }}</p>
    @endif
@endif
