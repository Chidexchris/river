@if($user->referrals && $level < $maxLevel)
    <ul @class(['firstList' => $isFirst])>
        @foreach($user->referrals as $referral)
            <li>
                {{ $referral->fullname }}
                @include("{$activeTheme}partials.referralNode", ['user' => $referral, 'isFirst' => false, 'level' => $level + 1])
            </li>
        @endforeach
    </ul>
@endif
