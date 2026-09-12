@props([
    'installments',
    'columns',
])

<table {{ $attributes->merge(['class' => 'table table--striped table-borderless table--responsive--sm']) }}>
    <thead>
        <tr>
            <th>@lang('S.N.')</th>

            @foreach($columns as $column)
                <th>@lang($column['label'])</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($installments as $installment)
            <tr>
                <td>{{ $installments->firstItem() + $loop->index }}</td>

                @foreach($columns as $column)
                    <td>
                        {!! call_user_func($column['callback'], $installment) !!}
                    </td>
                @endforeach
            </tr>
        @empty
            @include('partials.noData')
        @endforelse
    </tbody>
</table>

@if ($installments->hasPages())
    {{ $installments->links() }}
@endif
