@extends('layouts.app')

@section('title', 'Evaluator Performance Report')

@section('content')

<div class="page-header">

    <div>
        <h1>Evaluator Performance Report</h1>

        <p>
            Review evaluator workload and evaluation completion.
        </p>
    </div>

    <a
        href="{{ route('reports.index') }}"
        class="btn"
    >
        ← Reports
    </a>

</div>


<div class="card">

    <div class="inventory-report-header">

        <div>
            <h2>Evaluator Performance</h2>

            <p>
                Evaluation workload based on recorded assignments.
            </p>
        </div>

        <button
            type="button"
            onclick="window.print()"
            class="btn btn-primary print-button"
        >
            🖨 Print
        </button>

    </div>


    @if($evaluators->count() > 0)

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Evaluator</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Completed</th>
                        <th>Draft</th>
                        <th>Completion Rate</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($evaluators as $evaluator)

                        @php

                            $completionRate =
                                $evaluator->total_evaluations > 0
                                ? (
                                    $evaluator->completed_evaluations
                                    / $evaluator->total_evaluations
                                ) * 100
                                : 0;

                        @endphp

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <strong>
                                    {{ $evaluator->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $evaluator->email }}
                            </td>

                            <td>
                                {{ $evaluator->total_evaluations }}
                            </td>

                            <td>
                                {{ $evaluator->completed_evaluations }}
                            </td>

                            <td>
                                {{ $evaluator->draft_evaluations }}
                            </td>

                            <td>

                                <div class="percentage-wrapper">

                                    <div class="percentage-bar">

                                        <div
                                            class="percentage-fill"
                                           <div class="percentage-fill w-[{{ min($evaluator->completion_rate ?? $completionRate, 100) }}%]"></div>
                                        ></div>

                                    </div>

                                    <span>
                                        {{ number_format($completionRate, 1) }}%
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="empty-state">

            <div class="empty-icon">
                👤
            </div>

            <h3>No Evaluators Found</h3>

            <p>
                There are currently no users with recorded evaluations.
            </p>

        </div>

    @endif

</div>

@endsection