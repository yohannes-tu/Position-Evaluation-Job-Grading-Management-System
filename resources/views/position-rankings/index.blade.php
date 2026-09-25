@extends('layouts.app')

@section('title', 'Position Rankings')

@section('content')

<div class="page-header">
    <div>
        <h1>Position Rankings</h1>
        <p>
            Positions ranked according to their completed evaluation scores.
        </p>
    </div>
</div>

<div class="card">

    <div class="card-header">
        <h2>Ranked Positions</h2>
    </div>

    @if($rankings->count() > 0)

        <div class="table-container">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Evaluation Score</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($rankings as $item)

                        <tr>

                            <td>
                                <strong>
                                    #{{ $item['rank'] }}
                                </strong>
                            </td>

                            <td>
                                <strong>
                                    {{ $item['position']->title }}
                                </strong>
                            </td>

                            <td>
                                {{ $item['position']->department->name ?? 'N/A' }}
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($item['score'], 2) }}
                                </strong>
                            </td>

                            <td>

                                @if($item['evaluation'])

                                    <span class="status-badge status-success">
                                        Evaluated
                                    </span>

                                @else

                                    <span class="status-badge status-warning">
                                        Not Evaluated
                                    </span>

                                @endif

                            </td>

                            <td>

                                <a
                                    href="{{ route('positions.show', $item['position']) }}"
                                    class="btn btn-sm"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="empty-state">

            <div class="empty-icon">
                📊
            </div>

            <h3>No Position Rankings Available</h3>

            <p>
                Completed position evaluations will appear here automatically.
            </p>

        </div>

    @endif

</div>

@endsection