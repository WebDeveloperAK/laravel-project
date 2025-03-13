@extends('layouts.master')

@section('title', 'Users List')
@section('kitchen_select', 'show')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<div class="container mt-4">
    <h2 class="mb-3">Users List</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="usersTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Receiver ID</th>
                    <th>Message</th>
                    <th>Images</th>
                    <th>Audio</th>
                    <th>Created At</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include jQuery and DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('all.message') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'sender_name', name: 'sender_name' },
                { data: 'receiver_name', name: 'receiver_name' },
                { data: 'message', name: 'message' },
                { data: 'images', name: 'images', orderable: false, searchable: false, 
                    render: function(data, type, full, meta) {
                        return data ? `<img src="${data}" alt="Image" width="50">` : 'No Image';
                    }
                },
                { data: 'audio', name: 'audio', orderable: false, searchable: false,
                    render: function(data, type, full, meta) {
                        return data ? `<audio controls><source src="${data}" type="audio/mpeg"></audio>` : 'No Audio';
                    }
                },
                { data: 'created_at', name: 'created_at' }
            ]
        });
    });
</script>
@endsection
