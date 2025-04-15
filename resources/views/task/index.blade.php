@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- <h2 class="mb-4">Task List</h2> --}}

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Teachers List</h2>
            @if (Auth::user()->isTeacher())
                <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Assign New Task</a>
            @endif
        </div>

        {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}



        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Assigned By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $index => $task)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $task->student->code }}</td>
                        <td>{{ $task->student->name }}</td>
                        <td>{{ $task->title }}</td>
                        <td>
                            <span
                                class="badge bg-{{ $task->status == 'pending' ? 'warning' : ($task->status == 'approved' ? 'success' : 'danger') }}">{{ $task->status }}</span>

                        </td>
                        <td>{{ $task->teacher->name }}</td>
                        <td>

                            @if (Auth::user()->isTeacher() && !$task->approved_at)
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-info"><i
                                        class="fa fa-edit"></i></a>

                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                            class="fa fa-trash"></i>
                                        </button>
                                </form>
                            @endif

                            @php
                                $submission = $task->taskSubmissions->first();
                                $feedback = $submission->taskFeedback ?? null;
                            @endphp
                            @if (Auth::user()->isTeacher() && $task->approved_at)
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#taskViewModal{{ $task->id }}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                @if ($submission)
                                    @if ($feedback == null)
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#submitFeedbackModal{{ $task->id }}">
                                            Submit Feedback
                                        </button>
                                    @else
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#viewFeedbackModal{{ $task->id }}">
                                            View Feedback
                                        </button>
                                    @endif
                                @else
                                    <button class="btn btn-primary" disabled>
                                        Submit Feedback
                                    </button>
                                @endif
                            @endif
                            @if (Auth::user()->isStudent())
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#taskViewModal{{ $task->id }}">
                                    <i class="fa fa-eye"></i>
                                </button>
                                @if (Auth::user()->isStudent() && $task->approved_at)
                                    @if ($task->taskSubmissions->isEmpty())
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#submitTaskModal{{ $task->id }}">
                                            Submit Task
                                        </button>
                                    @else
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#viewSubmissionModal{{ $task->id }}">
                                            View Submission
                                        </button>
                                    @endif
                                @endif
                            @endif
                        </td>
                    </tr>

                    <!-- Modal for this view model -->
                    <div class="modal fade" id="taskViewModal{{ $task->id }}" tabindex="-1"
                        aria-labelledby="modalLabel{{ $task->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $task->id }}">Task Details @if (Auth::user()->isStudent())
                                            for {{ $task->student->name }}
                                        @endif
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    @if (Auth::user()->isTeacher())
                                        <p><strong>Student ID:</strong> {{ $task->student->code }}</p>
                                        <p><strong>Name:</strong> {{ $task->student->name }}</p>
                                        <p><strong>Email:</strong> {{ $task->student->email }}</p>
                                        <p><strong>Phone:</strong> {{ $task->student->phone }}</p>
                                    @endif
                                    <p><strong>Assigned By:</strong> {{ $task->teacher->name }}
                                        ({{ $task->teacher->code }})
                                    </p>
                                    <hr>
                                    <p><strong>Task Title:</strong> {{ $task->title }}</p>
                                    <p><strong>Task Description:</strong> {{ $task->description }}</p>
                                    <p><strong>Status:</strong> <span
                                            class="badge bg-{{ $task->status == 'pending' ? 'warning' : ($task->status == 'approved' ? 'success' : 'danger') }}">{{ $task->status }}</span>
                                    </p>
                                    @if (!$task->approved_at)
                                        <p><strong class="text-danger">Your tasks are pending for approval.</strong></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Modal for this task submit -->
                    <div class="modal fade" id="submitTaskModal{{ $task->id }}" tabindex="-1"
                        aria-labelledby="modalLabel{{ $task->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $task->id }}">Submit Task</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('task-submissions.store', $task->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="note" class="form-label">Note</label>
                                            <textarea name="note" id="note" class="form-control" placeholder="Enter your note here" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="files" class="form-label">Files</label>
                                            <input type="file" name="files" id="files" class="form-control"
                                                required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Viewing Task Submission -->
                    <div class="modal fade" id="viewSubmissionModal{{ $task->id }}" tabindex="-1"
                        aria-labelledby="viewSubmissionModalLabel{{ $task->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewSubmissionModalLabel{{ $task->id }}">Your
                                        Submission</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    @if ($submission)
                                        <p><strong>Note:</strong> {{ $submission->note ?? 'N/A' }}</p>

                                        @if ($submission->files)
                                            <p>
                                                <strong>Submitted File:</strong><br>
                                                <a href="{{ asset('storage/' . $submission->files) }}" target="_blank"
                                                    class="btn btn-success btn-sm me-2">
                                                    View File
                                                </a>

                                                <a href="{{ asset('storage/' . $submission->files) }}" download
                                                    class="btn btn-secondary btn-sm">
                                                    Download File
                                                </a>
                                            </p>
                                        @else
                                            <p>No file submitted.</p>
                                        @endif
                                    @else
                                        <p><em>No submission found.</em></p>
                                    @endif

                                    @if ($feedback)
                                        <hr>
                                        {{-- <h4>Feedback</h4> --}}
                                        <p><strong>Feedback:</strong> {{ $feedback->feedback ?? '' }}</p>
                                    @else
                                        <hr>
                                        <p><em>No feedback available.</em></p>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Submit Feedback Modal -->
                    <div class="modal fade" id="submitFeedbackModal{{ $task->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Submit Feedback</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @if ($submission)
                                        <form action="{{ route('task-feedbacks.store', $submission->id) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="submission_id" value="{{ $submission->id }}">
                                            <p><strong>Note:</strong> {{ $submission->note ?? 'N/A' }}</p>

                                            @if ($submission->files)
                                                <p>
                                                    <strong>Submitted File:</strong><br>
                                                    <a href="{{ asset('storage/' . $submission->files) }}"
                                                        target="_blank" class="btn btn-success btn-sm me-2">
                                                        View File
                                                    </a>

                                                    <a href="{{ asset('storage/' . $submission->files) }}" download
                                                        class="btn btn-secondary btn-sm">
                                                        Download File
                                                    </a>
                                                </p>
                                            @else
                                                <p>No file submitted.</p>
                                            @endif


                                            <hr>
                                            <div class="form-group">
                                                <label for="feedback">Feedback</label>
                                                <textarea name="feedback" class="form-control" rows="4" required></textarea>
                                            </div>
                                            <button class="btn btn-success mt-3" type="submit">Submit</button>
                                        </form>
                                    @else
                                        <p><em>No submission found.</em></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- View Feedback Modal -->
                    <div class="modal fade" id="viewFeedbackModal{{ $task->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">View Feedback</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">

                                    @if ($submission)
                                        <p><strong>Note:</strong> {{ $submission->note ?? 'N/A' }}</p>

                                        @if ($submission->files)
                                            <p>
                                                <strong>Submitted File:</strong><br>
                                                <a href="{{ asset('storage/' . $submission->files) }}" target="_blank"
                                                    class="btn btn-success btn-sm me-2">
                                                    View File
                                                </a>

                                                <a href="{{ asset('storage/' . $submission->files) }}" download
                                                    class="btn btn-secondary btn-sm">
                                                    Download File
                                                </a>
                                            </p>
                                        @else
                                            <p>No file submitted.</p>
                                        @endif
                                    @else
                                        <p><em>No submission found.</em></p>
                                    @endif

                                    <hr>

                                    @if ($feedback)
                                        <p><strong>Feedback:</strong> {{ $feedback->feedback ?? '' }}</p>
                                    @else
                                        <p><em>No feedback available.</em></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="7" class="text-center">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
